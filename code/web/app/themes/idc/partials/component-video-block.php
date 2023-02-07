<?php
//generate the proper responsive embed player for Youtube or Vimeo

//Vimeo or YouTube
$videoType = "";
//URL from ACF admin field
$embedURL = "";

//regex to determine video type and proper embed
$shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
$longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

//YouTube version 1
if (preg_match($longUrlRegex, get_field('video_url'), $matches)) {
    $youtube_id = $matches[count($matches) - 1];
    $videoType = "youtube";
    $embedURL = 'https://www.youtube.com/embed/' . $youtube_id . '&amp;enablejsapi=1';
}

//YouTube version 2
if (preg_match($shortUrlRegex, get_field('video_url'), $matches)) {
    $youtube_id = $matches[count($matches) - 1];
    $videoType = "youtube";
    $embedURL = 'https://www.youtube.com/embed/' . $youtube_id . '&amp;enablejsapi=1';
}

//Vimeo
if (preg_match('/https:\/\/vimeo.com\/(\\d+)/', get_field('video_url'), $matches)) {
    $vimeo_id = $matches[1] . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff&amp;enablejsapi=1';
    $videoType = "vimeo";
    $embedURL = 'https://player.vimeo.com/video/' . $vimeo_id;
}

if ($videoType === "youtube") :
    //YouTube videos require unique player variables
    //The API load enqued in the footer and fires onYouTubeIframeAPIReady for each video
    //Once ready, the dynamic function, onPlayerReady_ $args['id'] , fires and adds a data-ready attribute
?>
    <script>
        function onPlayerReady_<?= $args['id'] ?>(e) {
            e.target.i.setAttribute('data-ready', 'data-ready');
        }
    </script>
<?php endif; ?>
<div class="testimonial">
    <div class="video-testimonial video-testimonial--<?= $args['width'] ?>" data-testimonial-id="<?= $args['id'] ?>">
        <?php
        $posterBg = '';
        if (get_field('video_poster_image')) {
            $attachment_id = get_field('video_poster_image');
            $size = "large";
            $image = wp_get_attachment_image_src($attachment_id, $size);
            $posterBg = " style=\"background-image: url('" . $image[0] . "');\"";
        }
        ?>
        <div class="poster" data-testimonial-id="<?= $args['id'] ?>" <?= $posterBg ?>>
            <div class="icon-container"></div>
        </div>
        <?php if ($videoType === "vimeo") : ?>
            <div class='embed-container' data-type='<?= $videoType ?>' data-testimonial-id="<?= $args['id'] ?>"><iframe data-testimonial-id="<?= $args['id'] ?>" allow="autoplay" src='<?= $embedURL; ?>' frameborder='0' allowfullscreen></iframe></div>
        <?php else : ?>
            <div class='embed-container' data-type="<?= $videoType ?>" data-testimonial-id="<?= $args['id'] ?>" data-video-id="<?= $youtube_id ?>">
                <div id="<?= $args['id'] ?>"></div>
            </div>
        <?php endif; ?>
    </div>
    <figure>
        <?php
        $quoteStr = get_field('quote');

        echo '<blockquote>' . $quoteStr . '</blockquote>';
        echo '<figcaption>' . get_field('name_title');
        if (get_field('company')) {
            echo '<cite>' . get_field('company') . '</cite>';
        }
        echo '</figcaption>';
        ?>
    </figure>
</div>