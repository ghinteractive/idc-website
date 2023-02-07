<?php while (have_posts()) : the_post(); ?>
	<?php get_template_part('partials/content-page', $post->post_name); ?>
<?php endwhile; ?>