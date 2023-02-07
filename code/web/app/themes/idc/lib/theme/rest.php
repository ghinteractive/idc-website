<?php

namespace GHInt\Rest;

use GHInt\Gate\LoginException;
use GHInt\Gate\Token;
use GHInt\Gate\User;
use GuzzleHttp\Exception\ClientException;
use WP_REST_Request;
use WP_REST_Response;

add_action('rest_api_init', function () {
    register_rest_route('ghint/v1', '/gate/user', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => function (WP_REST_Request $request) {
            $secret = uniqid();
            try {
                $isValid = apply_filters('ghint/recaptcha_validate', false, 'newuser', $request);
                if($isValid) {
                    do_action('ghint/store_pardot_data', '2022-06-01/2pn9fl', $request);
                    (new User($request->get_param('email'), new Token($secret)))->create(MONTH_IN_SECONDS);
                    return new WP_REST_Response(['secret' => $secret], 200);
                }
                return new WP_REST_Response(['success' => false], 403);
            } catch (ClientException $e) {
                return new WP_REST_Response(['error' => $e->getMessage()], $e->getCode());
            }
        },
    ]);

    register_rest_route('ghint/v1', '/gate/login', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => function (WP_REST_Request $request) {
            $user = new User(
                $request->get_param('email'),
                new Token($request->get_param('secret'))
            );
            try {
                return new WP_REST_Response(['success' => $user->login()], 200);
            } catch (ClientException $e) {
                return new WP_REST_Response(
                    json_decode($e->getResponse()->getBody()->getContents()),
                    $e->getCode(),
                );
            } catch (LoginException $e) {
                return new WP_REST_Response(['error' => $e->getMessage()], 403);
            }
        },
    ]);

    register_rest_route('ghint/v1', '/form/get-started', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => function (WP_REST_Request $request) {
            try {
                $isValid = apply_filters('ghint/recaptcha_validate', false, 'getstarted', $request);
                if ($isValid) {
                    do_action('ghint/store_pardot_data', '2022-06-15/2v9q9s', $request);
                    return new WP_REST_Response(['success' => true], 200);
                }
                return new WP_REST_Response(['success' => false], 403);
            } catch (ClientException $e) {
                return new WP_REST_Response(
                    json_decode($e->getResponse()->getBody()->getContents()),
                    $e->getCode(),
                );
            }
        },
    ]);

    register_rest_route('ghint/v1', '/form/error', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function (WP_REST_Request $request) {
            return new WP_REST_Response([
                'params' => $request->get_params(),
                'message' => $request->get_param('errorMessage'),
            ], 400);
        },
    ]);
});
