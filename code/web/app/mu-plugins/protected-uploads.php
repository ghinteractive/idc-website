<?php
/**
 * Plugin Name:  Protected Attachments
 * Description:  Moves all uploads out of webroot, and enables media gating
 * Version:      1.0.0
 * Author:       GH Interactive
 * Author URI:   https://garrisonhughes.com
 * License:      Proprietary
 */

namespace GHInt\Gate;

use Exception;
use WP_Post;

class Token
{
    private const HASH_ALGORITHM = 'sha256';
    private const CIPHER_ALGORITHM = 'aes-256-cbc';

    /**
     * @var string|false
     */
    private string $key;

    public function __construct(string $key)
    {
        $this->key = hash(self::HASH_ALGORITHM, $key, true);
    }

    /**
     * @param string $plaintext
     * @return string
     */
    public function encrypt(string $plaintext): string
    {
        $vector = openssl_random_pseudo_bytes(16);
        $cipher = openssl_encrypt(
            $plaintext,
            self::CIPHER_ALGORITHM,
            $this->key,
            OPENSSL_RAW_DATA,
            $vector
        );
        return $vector . $this->hash($cipher . $vector) . $cipher;
    }

    /**
     * @param string $encrypted
     * @return string|null
     */
    public function decrypt(string $encrypted): ?string
    {
        $vector = substr($encrypted, 0, 16);
        $hash = substr($encrypted, 16, 32);
        $cipher = substr($encrypted, 48);

        if (hash_equals($this->hash($cipher . $vector), $hash)) {
            return openssl_decrypt(
                $cipher,
                self::CIPHER_ALGORITHM,
                $this->key,
                OPENSSL_RAW_DATA,
                $vector
            );
        }
        return null;
    }

    /**
     * @param string $cipher
     * @return string
     */
    private function hash(string $cipher): string
    {
        return hash_hmac(self::HASH_ALGORITHM, $cipher, $this->key, true);
    }
}

class LoginException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 403, null);
    }
}

class User
{
    /**
     * @var Token
     */
    private Token $token;
    /**
     * @var string
     */
    private string $username;

    public function __construct(string $username, Token $token)
    {
        $this->username = $username;
        $this->token = $token;
    }

    /**
     * Create a new user record in the database
     * Contains the Encrypted User data (just the username)
     * Can only be decrypted with the correct key generated from the REST endpoint
     *
     * @param int $expiry
     * @return bool
     */
    public function create(int $expiry = WEEK_IN_SECONDS): bool
    {
        return set_transient($this->getId(), (string)$this, $expiry);
    }

    /**
     * Not *really* a login
     * Just a check to ensure that the user data hasn't been tampered with
     *
     * @return bool
     * @throws LoginException
     */
    public function login(): bool
    {
        $encrypted = base64_decode(get_transient($this->getId()));
        if ($encrypted && ($this->token->decrypt($encrypted) === $this->username)) {
            return true;
        }
        throw new LoginException(__('Invalid Credentials', 'ghint'));
    }

    /**
     * Transient Key
     *
     * @return string
     */
    public function getId(): string
    {
        return sprintf('gated_user_%d', intval($this->username, 32));
    }

    /**
     * Base64 Representation of the User
     *
     * @return string
     */
    public function __toString(): string
    {
        return base64_encode($this->token->encrypt($this->username));
    }
}

class ProtectedUploads
{
    private const DEFAULT_UPLOADS_DIR = 'uploads';
    private const DEFAULT_UPLOADS_URL = 'uploads';

    /**
     * @var string
     */
    private string $baseUploadsDirName;
    /**
     * @var string
     */
    private string $baseUploadsUrlName;

    /**
     * @param ?string $baseUploadsDirName
     * @param ?string $baseUploadsUrlName
     */
    public function __construct(
        ?string $baseUploadsDirName = null,
        ?string $baseUploadsUrlName = null
    )
    {
        $this->baseUploadsDirName = $baseUploadsDirName ?? self::DEFAULT_UPLOADS_DIR;
        $this->baseUploadsUrlName = $baseUploadsUrlName ?? self::DEFAULT_UPLOADS_URL;
    }

    /**
     * Calculate the base uploads directory relative to webroot
     *
     * @param ...$paths
     * @return string
     */
    public function getBaseDir(...$paths): string
    {
        return $this->joinPaths($this->getRootDir(), $this->baseUploadsDirName, ...$paths);
    }

    /**
     * Calculate the base uploads URL
     *
     * @param ...$paths
     * @return string
     */
    public function getBaseUrl(...$paths): string
    {
        return $this->joinPaths(home_url(), $this->baseUploadsUrlName, ...$paths);
    }

    /**
     * Initialize all hooks
     *
     * @return void
     */
    public function init(): void
    {
        add_action('init', [$this, 'addRewriteRules']);
        add_action('pre_get_posts', [$this, 'processQuery']);

        add_filter('query_vars', [$this, 'addQueryVars']);
        add_filter('upload_dir', [$this, 'uploadDir']);
        add_filter('wp_get_attachment_url', [$this, 'getAttachmentUrl'], 10, 2);
        add_filter('wp_get_attachment_image_src', [$this, 'getAttachmentImageSrc'], 10, 3);
        add_filter('wp_prepare_attachment_for_js', [$this, 'prepareAttachmentForJs'], 10, 2);
    }

    /**
     * Add a special rewrite rule for uploads
     *
     * @return void
     */
    public function addRewriteRules(): void
    {
        $rewrite = sprintf('%s/([^/]*)/?([^/]*)/?', $this->baseUploadsUrlName);
        add_rewrite_rule($rewrite, ['post_type' => 'attachment', 'attachment_id' => '$matches[1]', 'attachment_size' => '$matches[2]'], 'top');
    }

    /**
     * Determine if we have the necessary data
     * to create a file response
     *
     * @return void
     */
    public function processQuery()
    {
        $type = get_query_var('post_type');
        $id = get_query_var('attachment_id');
        $size = get_query_var('attachment_size');
        if ($type === 'attachment' && $id) {
            remove_filter('pre_get_posts', [$this, 'processQuery']);

            $attachment = get_post($id);
            $isAllowed = self::userCanView($attachment);

            if ($isAllowed && $attachment) {
                $file = get_attached_file($attachment->ID, true);
                $isFull = empty($size) || $size === 'full';
                $info = image_get_intermediate_size($id, $size ?: 'thumbnail');
                $this->serveHandler(
                    'protected-media',
                    [$this, 'handleProtectedMedia'],
                    isset($info['file']) && !$isFull
                        ? str_replace(wp_basename($file), $info['file'], $file)
                        : $file,
                    $attachment,
                    $size,
                    $id,
                    $this
                );
            } else if (!$isAllowed) {
                $this->serveHandler('unauthorized', [$this, 'handleUnauthorized'], $attachment, $size, $id, $this);
            }

            $this->serveHandler('not-found', [$this, 'handleNotFound'], $size, $id, $this);
        }
    }

    /**
     * @param WP_Post|null $attachment
     * @return mixed|void
     */
    public static function userCanView(?WP_Post $attachment)
    {
        $isGated = apply_filters('ghint/protected-attachment/is_gated', false, $attachment);
        $userCanView = apply_filters('ghint/protected-attachment/is_viewable', (is_admin() || is_user_logged_in()), $attachment);
        return apply_filters('ghint/protected-attachment/user_is_allowed', (!$isGated || $userCanView), $attachment);
    }

    /**
     * @param string $path
     * @param WP_Post $attachment
     * @return void
     */
    public function handleProtectedMedia(string $path, WP_Post $attachment): void
    {
        $realpath = realpath($path);
        $headers = [
            'Content-Type' => $attachment->post_mime_type,
            'Content-Length' => filesize($realpath),
            'Content-Disposition' => 'inline; filename=' . basename($path),
        ];
        foreach ($headers as $header => $value) {
            header("$header: $value");
        }
        readfile($realpath);
        exit;
    }

    /**
     * Default Unauthorized User handler
     *
     * @return void
     */
    public function handleUnauthorized(): void
    {
        status_header(403);
        exit;
    }

    /**
     * Default Not Found handler
     *
     * @return void
     */
    public function handleNotFound()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    }

    /**
     * Final step to allow a theme the chance to handle media responses with custom behavior
     *
     * @param string $name
     * @param callable $handler
     * @param ...$args
     * @return void
     */
    private function serveHandler(string $name, callable $handler, ...$args)
    {
        $hook = sprintf('ghint/protected-attachment/override-%s-handler', $name);
        do_action($hook, ...$args);
        call_user_func_array($handler, $args);
    }

    /**
     * Merge whitelisted query var names
     *
     * @param array $vars
     * @return array
     */
    public function addQueryVars(array $vars): array
    {
        return array_merge($vars, ['attachment_size']);
    }

    /**
     * Rewrite base uploads paths
     *
     * @param array $dirs
     * @return array
     */
    public function uploadDir(array $dirs): array
    {
        return [
            'path' => $this->getBaseDir($dirs['subdir']),
            'url' => $this->getBaseUrl($dirs['subdir']),
            'basedir' => $this->getBaseDir(),
            'baseurl' => $this->getBaseUrl(),
            'subdir' => $dirs['subdir'],
            'error' => $dirs['error'],
        ];
    }

    /**
     * Override the attachment urls
     *
     * @param string $url
     * @param int $id
     * @return string
     */
    public function getAttachmentUrl(string $url, int $id): string
    {
        return $this->getProtectedUrl($id);
    }

    /**
     * Override attachment image src
     *
     * @param array|bool $image
     * @param int $id
     * @param string $size
     * @return array|bool
     */
    public function getAttachmentImageSrc($image, int $id, string $size)
    {
        if (!$image || !$id) {
            return $image;
        }
        $image[0] = $this->getProtectedUrl($id, $size);
        return $image;
    }

    /**
     * Override WP AJAX callback to use new URLs
     *
     * @param array $data
     * @param WP_Post $attachment
     * @return array
     */
    public function prepareAttachmentForJs(array $data, WP_Post $attachment): array
    {
        if (!isset($data['sizes'])) {
            return $data;
        }

        foreach ($data['sizes'] as $size => &$source) {
            $source['url'] = $this->getProtectedUrl($attachment->ID, $size);
        }
        return $data;
    }

    /**
     * Calculates the application root
     *
     * When in a web context, we can grab this from the server globals
     * If WP CLI is running, this is relative to Wordpress
     *
     * @return false|string
     */
    private function getRootDir(): string
    {
        $docroot = $_SERVER['DOCUMENT_ROOT'] . '/../';
        if (defined('WP_CLI') && WP_CLI) {
            $docroot = ABSPATH . '/../../';
        }
        return realpath($docroot);
    }

    /**
     * Utility method to join strings
     *
     * @param ...$paths
     * @return string
     */
    private function joinPaths(...$paths): string
    {
        return implode('/', array_filter($paths));
    }

    /**
     * Use new baseurl to create protected URL
     *
     * @param int $id
     * @param string|null $size
     * @return string
     */
    private function getProtectedUrl(int $id, string $size = null): string
    {
        return $this->joinPaths(wp_get_upload_dir()['baseurl'], $id, $size);
    }
}

$config = [
    $_ENV['WP_OVERRIDE_UPLOADS_DIR'] ?? null,
    $_ENV['WP_OVERRIDE_UPLOADS_URL'] ?? null,
];

$uploads = new ProtectedUploads(...$config);
$uploads->init();