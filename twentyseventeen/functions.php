<?php
/**
 * Twenty Seventeen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 */

/**
 * Twenty Seventeen only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function twentyseventeen_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enables custom line height for blocks
	 */
	add_theme_support( 'custom-line-height' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );

	add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 525;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(
		array(
			'top'    => __( 'Top Menu', 'twentyseventeen' ),
			'social' => __( 'Social Links Menu', 'twentyseventeen' ),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
			'navigation-widgets',
		)
	);

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://developer.wordpress.org/advanced-administration/wordpress/post-formats/
	 */
	add_theme_support(
		'post-formats',
		array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		)
	);

	// Add theme support for Custom Logo.
	add_theme_support(
		'custom-logo',
		array(
			'width'      => 250,
			'height'     => 250,
			'flex-width' => true,
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width. When fonts are
	 * self-hosted, the theme directory needs to be removed first.
	 */
	$font_stylesheet = str_replace(
		array( get_template_directory_uri() . '/', get_stylesheet_directory_uri() . '/' ),
		'',
		(string) twentyseventeen_fonts_url()
	);
	add_editor_style( array( 'assets/css/editor-style.css', $font_stylesheet ) );

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets'     => array(
			// Place three core-defined widgets in the sidebar area.
			'sidebar-1' => array(
				'text_business_info',
				'search',
				'text_about',
			),

			// Add the core-defined business info widget to the footer 1 area.
			'sidebar-2' => array(
				'text_business_info',
			),

			// Put two core-defined widgets in the footer 2 area.
			'sidebar-3' => array(
				'text_about',
				'search',
			),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts'       => array(
			'home',
			'about'            => array(
				'thumbnail' => '{{image-sandwich}}',
			),
			'contact'          => array(
				'thumbnail' => '{{image-espresso}}',
			),
			'blog'             => array(
				'thumbnail' => '{{image-coffee}}',
			),
			'homepage-section' => array(
				'thumbnail' => '{{image-espresso}}',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'image-espresso' => array(
				'post_title' => _x( 'Espresso', 'Theme starter content', 'twentyseventeen' ),
				'file'       => 'assets/images/espresso.jpg', // URL relative to the template directory.
			),
			'image-sandwich' => array(
				'post_title' => _x( 'Sandwich', 'Theme starter content', 'twentyseventeen' ),
				'file'       => 'assets/images/sandwich.jpg',
			),
			'image-coffee'   => array(
				'post_title' => _x( 'Coffee', 'Theme starter content', 'twentyseventeen' ),
				'file'       => 'assets/images/coffee.jpg',
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options'     => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set the front page section theme mods to the IDs of the core-registered pages.
		'theme_mods'  => array(
			'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
			'panel_4' => '{{contact}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus'   => array(
			// Assign a menu to the "top" location.
			'top'    => array(
				'name'  => __( 'Top Menu', 'twentyseventeen' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),

			// Assign a menu to the "social" location.
			'social' => array(
				'name'  => __( 'Social Links Menu', 'twentyseventeen' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	);

	/**
	 * Filters Twenty Seventeen array of starter content.
	 *
	 * @since Twenty Seventeen 1.1
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'twentyseventeen_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
}
add_action( 'after_setup_theme', 'twentyseventeen_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function twentyseventeen_content_width() {

	$content_width = $GLOBALS['content_width'];

	// Get layout.
	$page_layout = get_theme_mod( 'page_layout' );

	// Check if layout is one column.
	if ( 'one-column' === $page_layout ) {
		if ( twentyseventeen_is_frontpage() ) {
			$content_width = 644;
		} elseif ( is_page() ) {
			$content_width = 740;
		}
	}

	// Check if is single post and there is no sidebar.
	if ( is_single() && ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 740;
	}

	/**
	 * Filters Twenty Seventeen content width of the theme.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'twentyseventeen_content_width', $content_width );
}
add_action( 'template_redirect', 'twentyseventeen_content_width', 0 );

if ( ! function_exists( 'twentyseventeen_fonts_url' ) ) :
	/**
	 * Register custom fonts.
	 *
	 * @since Twenty Seventeen 1.0
	 * @since Twenty Seventeen 3.2 Replaced Google URL with self-hosted fonts.
	 *
	 * @return string Fonts URL for the theme.
	 */
	function twentyseventeen_fonts_url() {
		$fonts_url = '';

		/*
		 * translators: If there are characters in your language that are not supported
		 * by Libre Franklin, translate this to 'off'. Do not translate into your own language.
		 */
		$libre_franklin = _x( 'on', 'Libre Franklin font: on or off', 'twentyseventeen' );

		if ( 'off' !== $libre_franklin ) {
			$fonts_url = get_template_directory_uri() . '/assets/fonts/font-libre-franklin.css';
		}

		return esc_url_raw( $fonts_url );
	}
endif;

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 * @deprecated Twenty Seventeen 3.2 Disabled filter because, by default, fonts are self-hosted.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function twentyseventeen_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentyseventeen-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
// add_filter( 'wp_resource_hints', 'twentyseventeen_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentyseventeen_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'twentyseventeen' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'twentyseventeen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'twentyseventeen' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'twentyseventeen' ),
			'id'            => 'sidebar-3',
			'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'twentyseventeen_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $link Link to single post/page.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentyseventeen_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf(
		'<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Post title. Only visible to screen readers. */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentyseventeen_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Seventeen 1.0
 */
function twentyseventeen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentyseventeen_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function twentyseventeen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'twentyseventeen_pingback_header' );

/**
 * Display custom color CSS.
 */
function twentyseventeen_colors_css_wrap() {
	if ( 'custom' !== get_theme_mod( 'colorscheme' ) && ! is_customize_preview() ) {
		return;
	}

	require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
	$hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );

	$customize_preview_data_hue = '';
	if ( is_customize_preview() ) {
		$customize_preview_data_hue = 'data-hue="' . $hue . '"';
	}
	?>
	<style type="text/css" id="custom-theme-colors" <?php echo $customize_preview_data_hue; ?>>
		<?php echo twentyseventeen_custom_colors_css(); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'twentyseventeen_colors_css_wrap' );

/**
 * Enqueues scripts and styles.
 */
function twentyseventeen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	$font_version = ( 0 === strpos( (string) twentyseventeen_fonts_url(), get_template_directory_uri() . '/' ) ) ? '20230328' : null;
	wp_enqueue_style( 'twentyseventeen-fonts', twentyseventeen_fonts_url(), array(), $font_version );

	// Theme stylesheet.
	wp_enqueue_style( 'twentyseventeen-style', get_stylesheet_uri(), array(), '20241112' );

	// Theme block stylesheet.
	wp_enqueue_style( 'twentyseventeen-block-style', get_theme_file_uri( '/assets/css/blocks.css' ), array( 'twentyseventeen-style' ), '20240729' );

	// Load the dark colorscheme.
	if ( 'dark' === get_theme_mod( 'colorscheme', 'light' ) || is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-colors-dark', get_theme_file_uri( '/assets/css/colors-dark.css' ), array( 'twentyseventeen-style' ), '20240412' );
	}

	// Register the Internet Explorer 9 specific stylesheet, to fix display issues in the Customizer.
	if ( is_customize_preview() ) {
		wp_register_style( 'twentyseventeen-ie9', get_theme_file_uri( '/assets/css/ie9.css' ), array( 'twentyseventeen-style' ), '20161202' );
		wp_style_add_data( 'twentyseventeen-ie9', 'conditional', 'IE 9' );
	}

	// Register the Internet Explorer 8 specific stylesheet.
	wp_register_style( 'twentyseventeen-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'twentyseventeen-style' ), '20161202' );
	wp_style_add_data( 'twentyseventeen-ie8', 'conditional', 'lt IE 9' );

	// Register the html5 shiv.
	wp_register_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '20161020' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	// Skip-link fix is no longer enqueued by default.
	wp_register_script( 'twentyseventeen-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.js' ), array(), '20161114', array( 'in_footer' => true ) );

	wp_enqueue_script(
		'twentyseventeen-global',
		get_theme_file_uri( '/assets/js/global.js' ),
		array( 'jquery' ),
		'20211130',
		array(
			'in_footer' => false, // Because involves header.
			'strategy'  => 'defer',
		)
	);

	$twentyseventeen_l10n = array(
		'quote' => twentyseventeen_get_svg( array( 'icon' => 'quote-right' ) ),
	);

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script(
			'twentyseventeen-navigation',
			get_theme_file_uri( '/assets/js/navigation.js' ),
			array( 'jquery' ),
			'20210122',
			array(
				'in_footer' => false, // Because involves header.
				'strategy'  => 'defer',
			)
		);
		$twentyseventeen_l10n['expand']   = __( 'Expand child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['collapse'] = __( 'Collapse child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['icon']     = twentyseventeen_get_svg(
			array(
				'icon'     => 'angle-down',
				'fallback' => true,
			)
		);
	}

	wp_localize_script( 'twentyseventeen-global', 'twentyseventeenScreenReaderText', $twentyseventeen_l10n );

	wp_enqueue_script(
		'jquery-scrollto',
		get_theme_file_uri( '/assets/js/jquery.scrollTo.js' ),
		array( 'jquery' ),
		'2.1.3',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'twentyseventeen_scripts' );

/**
 * Enqueues styles for the block-based editor.
 *
 * @since Twenty Seventeen 1.8
 */
function twentyseventeen_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'twentyseventeen-block-editor-style', get_theme_file_uri( '/assets/css/editor-blocks.css' ), array(), '20240824' );
	// Add custom fonts.
	$font_version = ( 0 === strpos( (string) twentyseventeen_fonts_url(), get_template_directory_uri() . '/' ) ) ? '20230328' : null;
	wp_enqueue_style( 'twentyseventeen-fonts', twentyseventeen_fonts_url(), array(), $font_version );
}
add_action( 'enqueue_block_editor_assets', 'twentyseventeen_block_editor_styles' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentyseventeen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			$sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentyseventeen_content_image_sizes_attr', 10, 2 );

/**
 * Filters the `sizes` value in the header image markup.
 *
 * @since Twenty Seventeen 1.0
 * @since Twenty Seventeen 3.7 Added larger image size for small screens.
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function twentyseventeen_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '(max-width: 767px) 200vw, 100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'twentyseventeen_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string[]     $attr       Array of attribute values for the image markup, keyed by attribute name.
 *                                 See wp_get_attachment_image().
 * @param WP_Post      $attachment Image attachment post.
 * @param string|int[] $size       Requested image size. Can be any registered image size name, or
 *                                 an array of width and height values in pixels (in that order).
 * @return string[] The filtered attributes for the image markup.
 */
function twentyseventeen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentyseventeen_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $template front-page.php.
 * @return string The template to be used: blank if is_home() is true (defaults to index.php),
 *                otherwise $template.
 */
function twentyseventeen_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'twentyseventeen_front_page_template' );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Seventeen 1.4
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentyseventeen_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentyseventeen_widget_tag_cloud_args' );

/**
 * Gets unique ID.
 *
 * This is a PHP implementation of Underscore's uniqueId method. A static variable
 * contains an integer that is incremented with each call. This number is returned
 * with the optional prefix. As such the returned value is not universally unique,
 * but it is unique across the life of the PHP process.
 *
 * @since Twenty Seventeen 2.0
 *
 * @see wp_unique_id() Themes requiring WordPress 5.0.3 and greater should use this instead.
 *
 * @param string $prefix Prefix for the returned ID.
 * @return string Unique ID.
 */
function twentyseventeen_unique_id( $prefix = '' ) {
	static $id_counter = 0;
	if ( function_exists( 'wp_unique_id' ) ) {
		return wp_unique_id( $prefix );
	}
	return $prefix . (string) ++$id_counter;
}

if ( ! function_exists( 'wp_get_list_item_separator' ) ) :
	/**
	 * Retrieves the list item separator based on the locale.
	 *
	 * Added for backward compatibility to support pre-6.0.0 WordPress versions.
	 *
	 * @since 6.0.0
	 */
	function wp_get_list_item_separator() {
		/* translators: Used between list items, there is a space after the comma. */
		return __( ', ', 'twentyseventeen' );
	}
endif;

/**
 * Show the featured image below the header on single posts and pages, unless the
 * page is the front page.
 *
 * Use the filter `twentyseventeen_should_show_featured_image` in a child theme or
 * plugin to change when the image is shown. This example prevents the image
 * from showing:
 *
 *     add_filter(
 *         'twentyseventeen_should_show_featured_image',
 *         '__return_false'
 *     );
 *
 * @since Twenty Seventeen 3.7
 *
 * @return bool Whether the post thumbnail should be shown.
 */
function twentyseventeen_should_show_featured_image() {
	$show_featured_image = ( is_single() || ( is_page() && ! twentyseventeen_is_frontpage() ) ) && has_post_thumbnail( get_queried_object_id() );
	return apply_filters( 'twentyseventeen_should_show_featured_image', $show_featured_image );
}

// function registrar_post_type_cursos() {
//     register_post_type('cursos', array(
//         'labels' => array(
//             'name'          => 'Cursos',
//             'singular_name' => 'Curso',
//         ),
//         'public'       => true,
//         'has_archive'  => true,
//         'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
//         'taxonomies'   => array('category'), // Habilita categorias padrão
//         'rewrite'      => array('slug' => 'cursos/%tipo_de_curso%', 'with_front' => false),
//     ));
// }
// add_action('init', 'registrar_post_type_cursos');

function registrar_post_type_graduacao() {
	register_post_type('posgraduacao', array(
        'labels' => array(
			'name'          => 'Pós-Graduação',
			'singular_name' => 'posgraduacao',
        ),
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies'   => array('category'), // Habilita categorias padrão
		'rewrite'      => array('slug' => 'posgraduacao', 'with_front' => false),
    ));
}
add_action('init', 'registrar_post_type_graduacao');

function registrar_rewrite_posgraduacao_para_posts_graduacao() {
	add_rewrite_rule('^posgraduacao/([^/]+)/?$', 'index.php?post_type=graduacao&name=$matches[1]', 'top');
}
add_action('init', 'registrar_rewrite_posgraduacao_para_posts_graduacao');

function flush_rewrite_posgraduacao_uma_vez() {
	$rewrite_version = '3';
	if (get_option('posclone_posgraduacao_rewrite_flushed') === $rewrite_version) {
		return;
	}

	flush_rewrite_rules(false);
	update_option('posclone_posgraduacao_rewrite_flushed', $rewrite_version, false);
}
add_action('init', 'flush_rewrite_posgraduacao_uma_vez', 99);

function remover_sufixo_modalidade_de_slug($slug) {
	$slug = sanitize_title((string) $slug);
	return preg_replace('/-(digital|aovivo)$/', '', $slug);
}

function obter_sufixo_por_modalidade_normalizada($modalidade_normalizada) {
	$modalidade = strtolower(trim((string) $modalidade_normalizada));
	if ($modalidade === 'digital') {
		return '-digital';
	}
	if ($modalidade === 'digitalaovivo') {
		return '-aovivo';
	}
	return '';
}

function obter_nome_categoria_modalidade_por_normalizada($modalidade_normalizada) {
	$modalidade = strtolower(trim((string) $modalidade_normalizada));
	if ($modalidade === 'digital') {
		return 'DIGITAL (EAD)';
	}
	if ($modalidade === 'digitalaovivo') {
		return 'DIGITAL AO VIVO';
	}
	return 'PRESENCIAL';
}

function obter_termo_categoria_modalidade($modalidade_normalizada) {
	$nome = obter_nome_categoria_modalidade_por_normalizada($modalidade_normalizada);
	$slug_candidato = sanitize_title($nome);
	$term = get_term_by('slug', $slug_candidato, 'category');
	if ($term instanceof WP_Term) {
		return $term;
	}

	$term = get_term_by('name', $nome, 'category');
	if ($term instanceof WP_Term) {
		return $term;
	}

	return null;
}

function anexar_categoria_modalidade_sem_apagar($post_id, $termo_modalidade) {
	if (!$post_id || !($termo_modalidade instanceof WP_Term)) {
		return;
	}

	$post_id = (int) $post_id;
	$termo_id = (int) $termo_modalidade->term_id;

	$termos_atuais = wp_get_post_terms($post_id, 'category', array('fields' => 'ids'));
	if (is_wp_error($termos_atuais) || !is_array($termos_atuais)) {
		$termos_atuais = array();
	}

	if (!in_array($termo_id, $termos_atuais, true)) {
		wp_add_object_terms($post_id, array($termo_id), 'category');
	}
}

function categoria_eh_modalidade_bloqueada($nome_categoria) {
	$nome = function_exists('remove_accents') ? remove_accents((string) $nome_categoria) : (string) $nome_categoria;
	$nome = strtolower(trim($nome));
	$bloqueadas = array('presencial', 'digital (ead)', 'sem categoria', '100digital', 'semipresencial', 'digital ao vivo');
	return in_array($nome, $bloqueadas, true);
}

function coletar_ids_categorias_area_de_post($post_id) {
	$post_id = (int) $post_id;
	if ($post_id <= 0) {
		return array();
	}

	$adicionar_term_id_area = static function($term_id, array &$ids_ref) {
		$term_id = (int) $term_id;
		if ($term_id <= 0) {
			return;
		}

		$term = get_term($term_id, 'category');
		if (!($term instanceof WP_Term)) {
			return;
		}

		if (categoria_eh_modalidade_bloqueada($term->name)) {
			return;
		}

		$ids_ref[$term_id] = $term_id;
	};

	$adicionar_por_slug_ou_nome = static function($valor, array &$ids_ref) {
		$valor = trim((string) $valor);
		if ($valor === '') {
			return;
		}

		$slug = sanitize_title($valor);
		$match = null;
		if ($slug !== '') {
			$match = get_term_by('slug', $slug, 'category');
		}
		if (!$match instanceof WP_Term) {
			$match = get_term_by('name', $valor, 'category');
		}
		if (!($match instanceof WP_Term)) {
			return;
		}
		if (categoria_eh_modalidade_bloqueada($match->name)) {
			return;
		}

		$ids_ref[(int) $match->term_id] = (int) $match->term_id;
	};

	$ids = array();
	$terms = get_the_terms($post_id, 'category');
	if (!is_wp_error($terms) && !empty($terms)) {
		foreach ($terms as $term) {
			if (!($term instanceof WP_Term)) {
				continue;
			}
			$adicionar_term_id_area((int) $term->term_id, $ids);
		}
	}

	// Se a origem usa taxonomia custom de area, tenta mapear o termo para category por slug/nome.
	$post_type = get_post_type($post_id);
	$taxonomias = get_object_taxonomies($post_type, 'objects');
	if (is_array($taxonomias)) {
		foreach ($taxonomias as $tax_obj) {
			if (!($tax_obj instanceof WP_Taxonomy)) {
				continue;
			}
			$tax_name = (string) $tax_obj->name;
			if ($tax_name === 'category' || $tax_name === 'post_tag' || $tax_name === 'post_format') {
				continue;
			}

			$terms_outros = get_the_terms($post_id, $tax_name);
			if (is_wp_error($terms_outros) || empty($terms_outros)) {
				continue;
			}

			foreach ($terms_outros as $term_outro) {
				if (!($term_outro instanceof WP_Term)) {
					continue;
				}

				$adicionar_por_slug_ou_nome((string) $term_outro->slug, $ids);
				$adicionar_por_slug_ou_nome((string) $term_outro->name, $ids);
			}
		}
	}

	// Fallback para origens que guardam area em meta (ACF/campos custom), inclusive arrays serializados.
	$metas = get_post_meta($post_id);
	if (is_array($metas) && !empty($metas)) {
		$campos_preferenciais = array(
			'area', 'areas', 'categoria', 'categorias', 'area_interesse', 'areas_interesse',
			'area_de_interesse', 'categoria_area', 'categoria_areas', 'area_do_curso',
		);

		$normalizar_chave = static function($chave) {
			$chave = function_exists('remove_accents') ? remove_accents((string) $chave) : (string) $chave;
			$chave = strtolower($chave);
			$chave = preg_replace('/[^a-z0-9]+/', '_', $chave);
			return trim((string) $chave, '_');
		};

		$deve_tentar_meta = static function($meta_key) use ($normalizar_chave, $campos_preferenciais) {
			$key_norm = $normalizar_chave($meta_key);
			if ($key_norm === '') {
				return false;
			}

			foreach ($campos_preferenciais as $preferencial) {
				if ($key_norm === $preferencial || strpos($key_norm, $preferencial) !== false) {
					return true;
				}
			}

			return false;
		};

		$extrair_valores_meta = static function($valor_meta) {
			$coletados = array();
			$pilha = array($valor_meta);

			while (!empty($pilha)) {
				$item = array_pop($pilha);

				if (is_array($item)) {
					foreach ($item as $subitem) {
						$pilha[] = $subitem;
					}
					continue;
				}

				if (is_object($item)) {
					if (isset($item->term_id)) {
						$coletados[] = (int) $item->term_id;
					}
					if (isset($item->slug)) {
						$coletados[] = (string) $item->slug;
					}
					if (isset($item->name)) {
						$coletados[] = (string) $item->name;
					}
					continue;
				}

				$item = maybe_unserialize($item);
				if (is_array($item) || is_object($item)) {
					$pilha[] = $item;
					continue;
				}

				if (is_scalar($item)) {
					$coletados[] = (string) $item;
				}
			}

			return $coletados;
		};

		foreach ($metas as $meta_key => $meta_values) {
			if (!$deve_tentar_meta($meta_key)) {
				continue;
			}

			if (!is_array($meta_values)) {
				$meta_values = array($meta_values);
			}

			foreach ($meta_values as $meta_valor_bruto) {
				$valores_extraidos = $extrair_valores_meta($meta_valor_bruto);
				foreach ($valores_extraidos as $valor_extraido) {
					if (is_numeric($valor_extraido) && (int) $valor_extraido > 0) {
						$adicionar_term_id_area((int) $valor_extraido, $ids);
						continue;
					}
					$adicionar_por_slug_ou_nome($valor_extraido, $ids);
				}
			}
		}
	}

	return array_values($ids);
}

function obter_ids_categorias_area_do_post($post_id) {
	$post_id = (int) $post_id;
	if ($post_id <= 0) {
		return array();
	}

	$ids = array();
	$terms = get_the_terms($post_id, 'category');
	if (is_wp_error($terms) || empty($terms)) {
		return array();
	}

	foreach ($terms as $term) {
		if (!($term instanceof WP_Term)) {
			continue;
		}
		if (categoria_eh_modalidade_bloqueada($term->name)) {
			continue;
		}
		$ids[(int) $term->term_id] = (int) $term->term_id;
	}

	return array_values($ids);
}

function anexar_categorias_area_sem_apagar($post_id, $term_ids) {
	$post_id = (int) $post_id;
	if ($post_id <= 0 || !is_array($term_ids) || empty($term_ids)) {
		return;
	}

	$atuais = wp_get_post_terms($post_id, 'category', array('fields' => 'ids'));
	if (is_wp_error($atuais) || !is_array($atuais)) {
		$atuais = array();
	}

	$para_adicionar = array();
	foreach ($term_ids as $term_id) {
		$term_id = (int) $term_id;
		if ($term_id <= 0) {
			continue;
		}
		if (!in_array($term_id, $atuais, true)) {
			$para_adicionar[] = $term_id;
		}
	}

	if (!empty($para_adicionar)) {
		wp_add_object_terms($post_id, $para_adicionar, 'category');
	}
}

function obter_ids_categorias_modalidade_do_post($post_id) {
	$post_id = (int) $post_id;
	if ($post_id <= 0) {
		return array();
	}

	$ids = array();
	$terms = get_the_terms($post_id, 'category');
	if (is_wp_error($terms) || empty($terms)) {
		return array();
	}

	foreach ($terms as $term) {
		if (!($term instanceof WP_Term)) {
			continue;
		}
		if (!categoria_eh_modalidade_bloqueada($term->name)) {
			continue;
		}
		$ids[(int) $term->term_id] = (int) $term->term_id;
	}

	return array_values($ids);
}

function sincronizar_categorias_area_curso_home($post_destino_id, $mneumonico = '', $titulo = '') {
	$post_destino_id = (int) $post_destino_id;
	if ($post_destino_id <= 0) {
		return;
	}

	$ids_origem = array();
	$mneumonico = trim((string) $mneumonico);
	$titulo = trim((string) $titulo);

	if ($mneumonico !== '') {
		$q_meta = new WP_Query(array(
			'post_type' => 'any',
			'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
			'posts_per_page' => 200,
			'fields' => 'ids',
			'suppress_filters' => true,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'mneumonico',
					'value' => $mneumonico,
					'compare' => '=',
				),
				array(
					'key' => 'mnemonico',
					'value' => $mneumonico,
					'compare' => '=',
				),
			),
		));
		if (!empty($q_meta->posts)) {
			$ids_origem = array_merge($ids_origem, $q_meta->posts);
		}
		wp_reset_postdata();
	}

	if ($titulo !== '') {
		$titulo_norm = function_exists('remove_accents') ? remove_accents($titulo) : $titulo;
		$titulo_norm = strtolower(trim(preg_replace('/\s+/', ' ', (string) $titulo_norm)));

		$ids_titulo = get_posts(array(
			'post_type' => 'any',
			'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
			'posts_per_page' => 200,
			'fields' => 'ids',
			's' => $titulo,
			'suppress_filters' => true,
		));

		if (is_array($ids_titulo) && !empty($ids_titulo)) {
			foreach ($ids_titulo as $id_titulo) {
				$id_titulo = (int) $id_titulo;
				if ($id_titulo <= 0) {
					continue;
				}
				$titulo_post = get_the_title($id_titulo);
				$titulo_post_norm = function_exists('remove_accents') ? remove_accents((string) $titulo_post) : (string) $titulo_post;
				$titulo_post_norm = strtolower(trim(preg_replace('/\s+/', ' ', $titulo_post_norm)));
				if ($titulo_post_norm === $titulo_norm) {
					$ids_origem[] = $id_titulo;
				}
			}
		}
	}

	$ids_origem = array_values(array_unique(array_filter(array_map('intval', $ids_origem))));
	if (empty($ids_origem)) {
		return;
	}

	$ids_area = array();
	foreach ($ids_origem as $id_origem) {
		if ($id_origem === $post_destino_id) {
			continue;
		}
		$ids_area = array_merge($ids_area, coletar_ids_categorias_area_de_post($id_origem));
	}

	$ids_area = array_values(array_unique(array_filter(array_map('intval', $ids_area))));

	// Se nenhuma origem trouxer area, preserva as areas ja existentes no destino.
	if (empty($ids_area)) {
		$ids_area = obter_ids_categorias_area_do_post($post_destino_id);
	}

	// Sincroniza de forma deterministica: preserva somente categorias de modalidade locais
	// e substitui categorias de area pelas categorias encontradas nas origens.
	$ids_modalidade_destino = obter_ids_categorias_modalidade_do_post($post_destino_id);
	$ids_finais_destino = array_values(array_unique(array_filter(array_map('intval', array_merge($ids_modalidade_destino, $ids_area)))));

	wp_set_post_terms($post_destino_id, $ids_finais_destino, 'category', false);
}

function buscar_curso_graduacao_por_mneumonico_e_modalidade($mneumonico, $modalidade_normalizada) {
	$mneumonico = trim((string) $mneumonico);
	if ($mneumonico === '') {
		return null;
	}

	$tax_query = array();
	$termo_modalidade = obter_termo_categoria_modalidade($modalidade_normalizada);
	if ($termo_modalidade instanceof WP_Term) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field' => 'term_id',
			'terms' => array((int) $termo_modalidade->term_id),
		);
	}

	$args = array(
		'post_type' => 'graduacao',
		'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
		'posts_per_page' => 1,
		'fields' => 'ids',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'mneumonico',
				'value' => $mneumonico,
				'compare' => '=',
			),
			array(
				'key' => 'mnemonico',
				'value' => $mneumonico,
				'compare' => '=',
			),
		),
	);

	if (!empty($tax_query)) {
		$args['tax_query'] = $tax_query;
	}

	$query = new WP_Query($args);
	if (!empty($query->posts)) {
		$post_id = (int) $query->posts[0];
		return $post_id > 0 ? get_post($post_id) : null;
	}

	return null;
}

function buscar_curso_graduacao_base_por_mneumonico($mneumonico) {
	$mneumonico = trim((string) $mneumonico);
	if ($mneumonico === '') {
		return null;
	}

	$query = new WP_Query(array(
		'post_type' => 'graduacao',
		'post_status' => array('publish', 'draft', 'pending', 'private', 'future'),
		'posts_per_page' => 1,
		'fields' => 'ids',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'mneumonico',
				'value' => $mneumonico,
				'compare' => '=',
			),
			array(
				'key' => 'mnemonico',
				'value' => $mneumonico,
				'compare' => '=',
			),
		),
	));

	if (!empty($query->posts)) {
		$post_id = (int) $query->posts[0];
		return $post_id > 0 ? get_post($post_id) : null;
	}

	return null;
}

function garantir_pagina_curso_por_card_home($curso, $modalidade_normalizada) {
	if (!is_array($curso)) {
		return 0;
	}

	$titulo = trim((string) ($curso['curso'] ?? $curso['nome'] ?? ''));
	if ($titulo === '') {
		return 0;
	}

	$slug_fonte = (string) ($curso['slug'] ?? '');
	$slug_base = $slug_fonte !== '' ? sanitize_title($slug_fonte) : sanitize_title($titulo);
	$slug_base = remover_sufixo_modalidade_de_slug($slug_base);
	if ($slug_base === '') {
		return 0;
	}

	$mneumonico = trim((string) ($curso['mnemonico'] ?? $curso['mneumonico'] ?? ''));
	$termo_modalidade = obter_termo_categoria_modalidade($modalidade_normalizada);

	$sufixo = obter_sufixo_por_modalidade_normalizada($modalidade_normalizada);
	$slug_alvo = $slug_base . $sufixo;

	$post_existente = get_page_by_path($slug_alvo, OBJECT, 'graduacao');
	if ($post_existente instanceof WP_Post) {
		if ($mneumonico !== '') {
			update_post_meta($post_existente->ID, 'mneumonico', $mneumonico);
			update_post_meta($post_existente->ID, 'mnemonico', $mneumonico);
		}
		if ($termo_modalidade instanceof WP_Term) {
			anexar_categoria_modalidade_sem_apagar($post_existente->ID, $termo_modalidade);
		}
		sincronizar_categorias_area_curso_home((int) $post_existente->ID, $mneumonico, $titulo);
		return (int) $post_existente->ID;
	}

	$post_por_mneumonico = buscar_curso_graduacao_por_mneumonico_e_modalidade($mneumonico, $modalidade_normalizada);
	if ($post_por_mneumonico instanceof WP_Post) {
		$update_result = wp_update_post(array(
			'ID' => (int) $post_por_mneumonico->ID,
			'post_title' => $titulo,
			'post_name' => $slug_alvo,
			'post_status' => 'publish',
		), true);

		if (!is_wp_error($update_result)) {
			if ($mneumonico !== '') {
				update_post_meta($post_por_mneumonico->ID, 'mneumonico', $mneumonico);
				update_post_meta($post_por_mneumonico->ID, 'mnemonico', $mneumonico);
			}
			if ($termo_modalidade instanceof WP_Term) {
				anexar_categoria_modalidade_sem_apagar($post_por_mneumonico->ID, $termo_modalidade);
			}
			sincronizar_categorias_area_curso_home((int) $post_por_mneumonico->ID, $mneumonico, $titulo);
			return (int) $post_por_mneumonico->ID;
		}
	}

	$conteudo_clonado = '';
	$resumo_clonado = '';
	$thumb_id_clonado = 0;
	$post_base = buscar_curso_graduacao_base_por_mneumonico($mneumonico);
	if ($post_base instanceof WP_Post) {
		$conteudo_clonado = (string) $post_base->post_content;
		$resumo_clonado = (string) $post_base->post_excerpt;
		$thumb_id_clonado = (int) get_post_thumbnail_id($post_base->ID);
	}

	$post_data = array(
		'post_type' => 'graduacao',
		'post_status' => 'publish',
		'post_title' => $titulo,
		'post_name' => $slug_alvo,
		'post_content' => $conteudo_clonado,
		'post_excerpt' => $resumo_clonado,
	);

	$post_id = wp_insert_post($post_data, true);
	if (is_wp_error($post_id) || !$post_id) {
		error_log('[curso-home] Falha ao criar curso ' . $slug_alvo . ': ' . (is_wp_error($post_id) ? $post_id->get_error_message() : 'retorno vazio'));
		return 0;
	}

	if ($mneumonico !== '') {
		update_post_meta($post_id, 'mneumonico', $mneumonico);
		update_post_meta($post_id, 'mnemonico', $mneumonico);
	}

	if ($termo_modalidade instanceof WP_Term) {
		anexar_categoria_modalidade_sem_apagar($post_id, $termo_modalidade);
	}

	if ($thumb_id_clonado > 0) {
		set_post_thumbnail($post_id, $thumb_id_clonado);
	}

	sincronizar_categorias_area_curso_home((int) $post_id, $mneumonico, $titulo);

	return (int) $post_id;
}

function obter_modalidades_graduacao($post_id) {
	$modalidades = array(
		'presencial' => false,
		'digital' => false,
		'aovivo' => false,
	);

	$terms = get_the_terms($post_id, 'category');
	if (empty($terms) || is_wp_error($terms)) {
		return $modalidades;
	}

	foreach ($terms as $term) {
		$nome = function_exists('remove_accents') ? remove_accents((string) $term->name) : (string) $term->name;
		$slug = function_exists('remove_accents') ? remove_accents((string) $term->slug) : (string) $term->slug;

		$nome = strtolower(trim($nome));
		$slug = strtolower(trim($slug));
		$nome_limpo = preg_replace('/\s+/', ' ', str_replace(array('(', ')'), '', $nome));

		if ($nome === 'presencial' || $slug === 'presencial') {
			$modalidades['presencial'] = true;
			continue;
		}

		if (
			$nome === 'digital ao vivo' ||
			$nome === 'semipresencial' ||
			$nome_limpo === 'digital ao vivo' ||
			$slug === 'digital-ao-vivo' ||
			$slug === 'digitalaovivo' ||
			$slug === 'semipresencial'
		) {
			$modalidades['aovivo'] = true;
			continue;
		}

		if (
			$nome === 'digital (ead)' ||
			$nome === '100digital' ||
			$nome_limpo === 'digital ead' ||
			$slug === 'digital-ead' ||
			$slug === 'digitalead' ||
			$slug === '100digital'
		) {
			$modalidades['digital'] = true;
			continue;
		}
	}

	return $modalidades;
}

function obter_sufixos_modalidade_graduacao($post_id) {
	$modalidades = obter_modalidades_graduacao($post_id);
	$sufixos = array();

	if (!empty($modalidades['digital'])) {
		$sufixos[] = '-digital';
	}

	if (!empty($modalidades['aovivo'])) {
		$sufixos[] = '-aovivo';
	}

	return $sufixos;
}

function obter_sufixo_modalidade_graduacao($post_id) {
	$modalidades = obter_modalidades_graduacao($post_id);

	if (!empty($modalidades['presencial'])) {
		return '';
	}

	if (!empty($modalidades['digital'])) {
		return '-digital';
	}

	if (!empty($modalidades['aovivo'])) {
		return '-aovivo';
	}

	return '';
}

function forcar_permalink_publico_graduacao_em_posgraduacao($post_link, $post) {
	if (!is_object($post) || !isset($post->post_type) || $post->post_type !== 'graduacao') {
		return $post_link;
	}

	$slug = remover_sufixo_modalidade_de_slug($post->post_name ?: sanitize_title($post->post_title));
	$sufixo = obter_sufixo_modalidade_graduacao($post->ID);
	return home_url('/posgraduacao/' . $slug . $sufixo . '/');
}
add_filter('post_type_link', 'forcar_permalink_publico_graduacao_em_posgraduacao', 10, 2);

function ajustar_request_curso_com_sufixo_modalidade($query_vars) {
	if (is_admin() || !is_array($query_vars)) {
		return $query_vars;
	}

	$post_type = $query_vars['post_type'] ?? '';
	$name = $query_vars['name'] ?? '';

	if ($post_type !== 'graduacao' || $name === '') {
		return $query_vars;
	}

	$name = sanitize_title((string) $name);

	// Se houver um post com slug exato, respeita o match direto.
	$post_exato = get_page_by_path($name, OBJECT, 'graduacao');
	if ($post_exato instanceof WP_Post) {
		return $query_vars;
	}

	// Se vier URL base e não existir slug exato, tenta mapear para slugs legados com sufixo.
	if (!preg_match('/-(digital|aovivo)$/', $name)) {
		foreach (array('-digital', '-aovivo') as $sufixo_legado) {
			$post_legado = get_page_by_path($name . $sufixo_legado, OBJECT, 'graduacao');
			if ($post_legado instanceof WP_Post) {
				$query_vars['name'] = $name . $sufixo_legado;
				return $query_vars;
			}
		}
	}

	if (!preg_match('/^(.*)-(digital|aovivo)$/', (string) $name, $matches)) {
		return $query_vars;
	}

	$slug_base = remover_sufixo_modalidade_de_slug($matches[1]);
	$sufixo_requisitado = $matches[2] === 'aovivo' ? '-aovivo' : '-digital';
	$post = get_page_by_path($slug_base, OBJECT, 'graduacao');

	if ($post instanceof WP_Post) {
		// A versão -digital deve existir quando o curso base existe.
		if ($sufixo_requisitado === '-digital') {
			$query_vars['name'] = $slug_base;
			return $query_vars;
		}

		$sufixos_permitidos = obter_sufixos_modalidade_graduacao($post->ID);
		if (in_array($sufixo_requisitado, $sufixos_permitidos, true)) {
			$query_vars['name'] = $slug_base;
			return $query_vars;
		}
	}

	// Fallback para slugs legados já salvos com sufixo no próprio post_name.
	$post_legado = get_page_by_path($slug_base . $sufixo_requisitado, OBJECT, 'graduacao');
	if ($post_legado instanceof WP_Post) {
		$query_vars['name'] = $slug_base . $sufixo_requisitado;
	}

	return $query_vars;
}
add_filter('request', 'ajustar_request_curso_com_sufixo_modalidade', 10, 1);

function redirecionar_urls_legadas_graduacao_para_posgraduacao() {
	if (is_admin()) {
		return;
	}

	$request_uri = $_SERVER['REQUEST_URI'] ?? '';
	if ($request_uri === '') {
		return;
	}

	$script_dir = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '';
	$base_path = '/' . trim((string) $script_dir, '/');
	if ($base_path === '//') {
		$base_path = '/';
	}

	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'] ?? wp_parse_url(home_url('/'), PHP_URL_HOST);
	$build_url = static function($path) use ($scheme, $host) {
		$normalized_path = '/' . ltrim((string) $path, '/');
		return $scheme . '://' . $host . $normalized_path;
	};

	// Evita URLs com base duplicada (ex.: /posclone/posclone/...) vindas de links antigos/cache.
	if ($base_path !== '/') {
		$prefixo_duplicado = $base_path . $base_path . '/';
		if (strpos($request_uri, $prefixo_duplicado) === 0) {
			$uri_normalizada = substr($request_uri, strlen($base_path));
			$destino_normalizado = $build_url($uri_normalizada);
			wp_redirect($destino_normalizado, 301);
			exit;
		}
	}

	// Canonical do projeto: mantém URLs de cursos em /posgraduacao/.
	if (strpos($request_uri, '/graduacao/') !== false) {
		$destino_request_uri = preg_replace('#/graduacao/#', '/posgraduacao/', $request_uri, 1);
		if (is_string($destino_request_uri) && $destino_request_uri !== '' && $destino_request_uri !== $request_uri) {
			$destino = $build_url($destino_request_uri);
			wp_redirect($destino, 301);
			exit;
		}
	}

	if (is_singular('graduacao')) {
		$post_id = get_queried_object_id();
		$post = $post_id ? get_post($post_id) : null;
		$slug_real = ($post instanceof WP_Post) ? $post->post_name : '';
		$slug_base = remover_sufixo_modalidade_de_slug($slug_real);
		$path_atual = wp_parse_url(home_url($request_uri), PHP_URL_PATH);

		$paths_permitidos = array();
		if ($slug_base !== '') {
			$paths_permitidos[] = '/posgraduacao/' . $slug_base;
			$paths_permitidos[] = '/posgraduacao/' . $slug_base . '-digital';
			foreach (obter_sufixos_modalidade_graduacao($post_id) as $sufixo_modalidade) {
				$paths_permitidos[] = '/posgraduacao/' . $slug_base . $sufixo_modalidade;
			}

			// Compatibilidade temporária com URL antiga baseada no slug real.
			if ($slug_real !== $slug_base) {
				$paths_permitidos[] = '/posgraduacao/' . $slug_real;
			}
		}

		$path_atual_sem_barra = $path_atual ? untrailingslashit($path_atual) : '';
		$eh_path_permitido = false;
		foreach ($paths_permitidos as $path_permitido) {
			if ($path_atual_sem_barra === untrailingslashit($path_permitido)) {
				$eh_path_permitido = true;
				break;
			}
		}

		if ($eh_path_permitido) {
			return;
		}

		$permalink_canonico = $post_id ? get_permalink($post_id) : '';
		$path_canonico = wp_parse_url($permalink_canonico, PHP_URL_PATH);

		if ($path_canonico && $path_atual && untrailingslashit($path_atual) !== untrailingslashit($path_canonico)) {
			wp_redirect($permalink_canonico, 301);
			exit;
		}
	}
}
add_action('template_redirect', 'redirecionar_urls_legadas_graduacao_para_posgraduacao');

function desabilitar_redirects_nativos_em_rotas_de_curso() {
	if (is_admin()) {
		return;
	}

	$uri = $_SERVER['REQUEST_URI'] ?? '';
	if ($uri === '') {
		return;
	}

	if (strpos($uri, '/graduacao/') !== false || strpos($uri, '/posgraduacao/') !== false) {
		remove_action('template_redirect', 'redirect_canonical');
		remove_action('template_redirect', 'wp_old_slug_redirect');
	}
}
add_action('template_redirect', 'desabilitar_redirects_nativos_em_rotas_de_curso', 0);

function bloquear_canonical_em_urls_de_curso($redirect_url, $requested_url) {
	$url = is_string($requested_url) ? $requested_url : '';
	if ($url === '') {
		$url = $_SERVER['REQUEST_URI'] ?? '';
	}

	if (strpos($url, '/graduacao/') !== false || strpos($url, '/posgraduacao/') !== false) {
		return false;
	}

	return $redirect_url;
}
add_filter('redirect_canonical', 'bloquear_canonical_em_urls_de_curso', 10, 2);

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );

/**
 * Register block patterns and pattern categories.
 *
 * @since Twenty Seventeen 3.8
 */
function twentyseventeen_register_block_patterns() {
	require get_template_directory() . '/inc/block-patterns.php';
}

add_action( 'init', 'twentyseventeen_register_block_patterns' );



// // Taxonomia: Tipo de Curso
// function criar_taxonomia_tipo_de_curso() {
//     register_taxonomy('tipo_de_curso', 'cursos', array(
//         'label' => 'Tipo de Curso',
//         'hierarchical' => true,
//         'rewrite' => array('slug' => 'cursos', 'with_front' => false),
//         'show_ui' => true,
//         'show_admin_column' => true,
//         'show_in_rest' => true,
//     ));
// }
// add_action('init', 'criar_taxonomia_tipo_de_curso');

// // Permalink personalizado do curso
// function personalizar_link_curso($post_link, $post) {
//     if ('cursos' === get_post_type($post)) {
//         $termos = wp_get_object_terms($post->ID, 'tipo_de_curso');
//         if (!empty($termos) && !is_wp_error($termos)) {
//             return str_replace('%tipo_de_curso%', $termos[0]->slug, $post_link);
//         }
//     }
//     return $post_link;
// }
// add_filter('post_type_link', 'personalizar_link_curso', 10, 2);

// // Regras de rewrite para as URLs
// function adicionar_regras_customizadas() {
//     add_rewrite_rule(
//         '^cursos/([^/]+)/([^/]+)/?$',
//         'index.php?cursos=$matches[2]',
//         'top'
//     );
//     add_rewrite_rule(
//         '^cursos/([^/]+)/?$',
//         'index.php?tipo_de_curso=$matches[1]',
//         'top'
//     );
// }
// add_action('init', 'adicionar_regras_customizadas');



if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title'    => 'Configurações Gerais',
		'menu_title'    => 'Geral',
		'menu_slug'     => 'configuracoes-gerais',
		'capability'    => 'edit_posts',
		'redirect'      => false,
		'position'      => 2, // Altere a posição se quiser
		'icon_url'      => 'dashicons-admin-tools'
	));
}



// Adiciona CSS inline para editores na área admin
add_action('admin_head', function() {
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		if ( in_array('editor', (array) $user->roles) ) {
			echo '<style>
				li#menu-posts {
					display: none;
				}
				li#menu-media {
					display: none;
				}
				li#menu-pages {
					display: none;
				}
				li#menu-comments {
					display: none;
				}
				li#toplevel_page_leadin {
					display: none;
				}
				li#menu-appearance {
					display: none;
				}
				li#menu-plugins {
					display: none;
				}
				li#menu-users {
					display: none;
				}
				li#menu-tools {
					display: none;
				}
				li#toplevel_page_ai1wm_export {
					display: none;
				}
				li#menu-settings {
					display: none;
				}
				li#toplevel_page_edit-post_type-acf-field-group {
					display: none;
				}
				li#toplevel_page_wpseo_dashboard {
					display: none;
				}
				li#toplevel_page_cptui_main_menu {
					display: none;
				}
				#adminmenu, #adminmenuback, #adminmenuwrap {
					background-color: #4d4d4d !important;
				}
				li#menu-dashboard {
					display: none;
				}
				li#wp-admin-bar-archive {
					display: none;
				}
				li#wp-admin-bar-new-content {
					display: none;
				}
				#wpadminbar {
					color: #fff;
					background: #000 !important;
				}
				li#wp-admin-bar-comments {
					display: none;
				}
				div#postdivrich {
					display: none;
				}
			</style>';
		}
	}
});

// cria pagina em função dos cards 
add_action('wp_ajax_criar_graduacoes_automaticamente', 'criar_graduacoes_automaticamente');
add_action('wp_ajax_nopriv_criar_graduacoes_automaticamente', 'criar_graduacoes_automaticamente');

function criar_graduacoes_automaticamente() {
	$body = file_get_contents('php://input');
	error_log('Recebido: ' . $body); // LOG PARA DEPURAÇÃO
	$data = json_decode($body, true);

	$upload_base_url = wp_upload_dir()['baseurl'] ?? content_url('uploads');
	$featured_image_url = trailingslashit($upload_base_url) . '2025/09/bgHome.jpg';

	$target_post_type = 'graduacao';
	if (!post_type_exists($target_post_type)) {
		error_log('Post type obrigatório não encontrado: graduacao');
		wp_send_json_error('Post type graduacao não encontrado.');
		return;
	}

	if (!isset($data['cards']) || !is_array($data['cards'])) {
		error_log('Dados inválidos');
		wp_send_json_error('Dados inválidos');
		return;
	}

	foreach ($data['cards'] as $card) {
		$titulo = sanitize_text_field($card['nome'] ?? $card['curso'] ?? '');
		$mneumonico = sanitize_text_field($card['mneumonico'] ?? $card['mnemonico'] ?? '');
		$categoria = sanitize_text_field($card['categoria'] ?? $card['modalidade'] ?? '');

		if ($titulo === '') {
			continue;
		}

		// Evita duplicidade por mneumônico (quando disponível) ou por slug do título.
		$query_args = [
			'post_type'      => $target_post_type,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		];

		if ($mneumonico !== '') {
			$query_args['meta_query'] = [
				'relation' => 'OR',
				[
					'key'     => 'mneumonico',
					'value'   => $mneumonico,
					'compare' => '='
				],
				[
					'key'     => 'mnemonico',
					'value'   => $mneumonico,
					'compare' => '='
				],
			];
		} else {
			$query_args['name'] = sanitize_title($titulo);
		}

		$query = new WP_Query($query_args);

		if ($query->have_posts()) {
			continue; // Já existe, não cria novamente
		}

		$subtitulo_base = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
		$post_content_base = "";

		$post_id = wp_insert_post([
			'post_title'   => $titulo,
			'post_content' => wp_kses_post($post_content_base),
			'post_excerpt' => $subtitulo_base,
			'post_type'    => $target_post_type,
			'post_status'  => 'publish'
		]);

		error_log("Criando post: $titulo | ID: $post_id");

		if ($post_id && !is_wp_error($post_id)) {
			if ($mneumonico !== '') {
				update_post_meta($post_id, 'mneumonico', $mneumonico);
				update_post_meta($post_id, 'mnemonico', $mneumonico);
			}
			update_post_meta($post_id, 'sub_titulo', $subtitulo_base);
			update_post_meta($post_id, 'texto_apoio', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');
			if ($categoria) {
				// Verifica se a categoria já existe, se não existir, cria
				$term = term_exists($categoria, 'category');
				if (!$term) {
					$term = wp_insert_term($categoria, 'category');
				}
				if (!is_wp_error($term) && $term) {
					// Pega o ID da categoria (novo ou existente)
					$term_id = is_array($term) ? $term['term_id'] : $term;
					// Associa ao post sem remover outras categorias
					wp_add_object_terms($post_id, [$term_id], 'category');
				}
			}

			// Define a imagem destacada
			$image_url = $featured_image_url;
			$image_id = attachment_url_to_postid($image_url);

			if ($image_id) {
				set_post_thumbnail($post_id, $image_id);
		 } else {
			 // Se não existe, tenta baixar e anexar
			 require_once(ABSPATH . 'wp-admin/includes/image.php');
			 require_once(ABSPATH . 'wp-admin/includes/file.php');
			 require_once(ABSPATH . 'wp-admin/includes/media.php');
			 $image_id = media_sideload_image($image_url, $post_id, null, 'id');
			 if (!is_wp_error($image_id)) {
				 set_post_thumbnail($post_id, $image_id);
			 }
		 }
		}
	}

	wp_send_json_success('Posts criados');
}
// cria pagina em função dos cards 

	// Admin: habilita e filtra categoria na listagem do post type "graduacao".
	add_action('init', function() {
		if (post_type_exists('graduacao')) {
			register_taxonomy_for_object_type('category', 'graduacao');
		}
	}, 20);

	add_action('restrict_manage_posts', function($post_type) {
		if ($post_type !== 'graduacao') {
			return;
		}

		wp_dropdown_categories(array(
			'show_option_all' => 'Todas as categorias',
			'taxonomy'        => 'category',
			'name'            => 'cat',
			'orderby'         => 'name',
			'selected'        => isset($_GET['cat']) ? (int) $_GET['cat'] : 0,
			'hierarchical'    => true,
			'show_count'      => true,
			'hide_empty'      => false,
		));
	}, 10, 1);

	add_action('pre_get_posts', function($query) {
		if (!is_admin() || !$query->is_main_query()) {
			return;
		}

		if ($query->get('post_type') !== 'graduacao') {
			return;
		}

		if (!empty($_GET['cat'])) {
			$query->set('cat', (int) $_GET['cat']);
		}
	});