<?php
/**
 * Template for displaying single posgraduacao posts.
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 */

get_header();
?>

<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()); ?>/home.css">

<div class="wrap" style="padding: 32px 0 56px;">
	<div id="primary" class="content-area" style="max-width: 980px; margin: 0 auto;">
		<main id="main" class="site-main" role="main">
			<?php while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header" style="margin-bottom: 20px;">
						<p style="font-weight:700; color:#1f3c88; letter-spacing:.04em; margin:0 0 8px;">PÓS-GRADUAÇÃO</p>
						<?php the_title('<h1 class="entry-title" style="margin:0;">', '</h1>'); ?>
					</header>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<footer style="margin-top: 24px;">
						<a class="btnInscreva btn" href="<?php echo esc_url(home_url('/')); ?>" style="text-decoration:none;">VER OUTROS CURSOS DE PÓS</a>
					</footer>
				</article>
			<?php endwhile; ?>
		</main>
	</div>
</div>

<?php get_footer();
