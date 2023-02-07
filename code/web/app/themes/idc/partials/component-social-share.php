<?php //variables
    $className = 'c-social-share';

    $socialShares = [
        'shareFacebook' => [
            'href' =>  'https://www.facebook.com/sharer/sharer.php?u=' . get_permalink() . '&quote=',
            'title' => 'Share on Facebook',
            'onClick' => 'window.open(\'https://www.facebook.com/sharer/sharer.php?u=\' + encodeURIComponent(document.URL) + \'&quote=\' + encodeURIComponent(document.URL)); return false;',
            'iconClass' => 'fab fa-facebook-f'
        ],
        'shareTwitter' => [
            'href' =>  'https://twitter.com/intent/tweet?source=' . get_permalink() . '%2F&text=:%20' . get_permalink() . '%2F&via=IDConnect1',
            'title' => 'Share on Twitter',
            'onClick' => 'window.open(\'https://twitter.com/intent/tweet?text=\' + encodeURIComponent(document.title) + \':%20\' + encodeURIComponent(document.URL)); return false;',
            'iconClass' => 'fab fa-twitter'
        ],
        'shareLinkedIn' => [
            'href' =>  'http://www.linkedin.com/shareArticle?mini=true&url=' . get_permalink() . '%2F&title=&summary=&source=' . get_permalink() . '%2F',
            'title' => 'Share on LinkedIn',
            'onClick' => 'window.open(\'http://www.linkedin.com/shareArticle?mini=true&url=\' + encodeURIComponent(document.URL) + \'&title=\' + encodeURIComponent(document.title)); return false;',
            'iconClass' => 'fab fa-linkedin-in'
        ],
        'shareEmail' => [
            'href' =>  'mailto:?subject=&body=:%20' . get_permalink() . '%2F',
            'title' => 'Share on Email',
            'onClick' => 'window.open(\'mailto:?subject=\' + encodeURIComponent(document.title) + \'&body=\' + encodeURIComponent(document.URL)); return false;',
            'iconClass' => 'far fa-envelope'
        ]
    ];
?>
<div class="<?= $className ?> container container--padding-x container--padding-y">

    <h5 class="text--charcoal">Share</h5>

    <div class="<?= $className ?>__social-icons">
        <?php foreach($socialShares as $socialShare): ?>
            <a href="<?= $socialShare['href'] ?>" class="<?= $className ?>__social-icon" title="<?= $socialShare['title'] ?>" aria-label="<?= $socialShare['title'] ?>" onclick="<?= $socialShare['onClick'] ?>" target="_blank"><i class="<?= $socialShare['iconClass'] ?>"></i></a>
        <?php endforeach; ?>
    </div>
</div>