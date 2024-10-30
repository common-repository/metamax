<?php
/*
Plugin Name: MetaMax
Description: MetaMax automagically inserts meta tags in your html to make your site more SEO friendly.
Version: 1.1.1
Author: Appex
Author URI: https://appex.no/
Copyright: Appex
Text Domain: appex-metamax
Domain Path: /languages
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'Appex_metamax' ) ) :

	class Appex_Metamax {

		/*filters*/
		const FILTER_META_OG_TITLE = 'metamax_meta_og_title';
		const FILTER_META_DESCRIPTION = 'metamax_meta_description';
		const FILTER_META_OG_DESCRIPTION = 'metamax_meta_og_description';
		const FILTER_META_OG_IMAGE = 'metamax_meta_og_image';
		const FILTER_META_DEFAULT_OG_IMAGE = 'metamax_meta_default_og_image';
		const FILTER_META_OG_LOCALE = 'metamax_meta_og_locale';
		const FILTER_META_OG_SITE_NAME = 'metamax_meta_og_site_name';
		const FILTER_META_OG_URL = 'metamax_meta_og_url';
		const FILTER_META_OG_VIDEO = 'metamax_meta_og_video';
		const FILTER_META_OG_TYPE = 'metamax_meta_og_type';
		const FILTER_META_PROFILE_FIRST_NAME = 'metamax_meta_profile_first_name';
		const FILTER_META_PROFILE_LAST_NAME = 'metamax_meta_profile_last_name';
		const FILTER_META_FB_APP_ID = 'metamax_meta_fb_app_id';
		const FILTER_META_TWITTER_CARD = 'metamax_meta_twitter_card';

		function __construct() {

			/* Do nothing here */

		}

		public function initialize() {

			include_once( 'includes/appex-metamax-builders.php' );
			include_once( 'includes/appex-metamax-defaults.php' );

			// Notify if Yoast! is active
			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( in_array( 'wordpress-seo/wp-seo.php', $active_plugins ) ) {

				add_action( 'admin_notices', function() {
					$class = 'notice notice-warning';
					$message = __( 'MetaMax has detected that you have Yoast SEO enabled. Yoast and MetaMax does not play nice together. MetaMax is disabled while Yoast is actice.', 'appex-metamax' );
					echo "<div class=\"$class\"> <p>$message</p></div>";
				});

				return;

			}

			$this->settings = array(
				'plugin_path' => plugin_dir_path( __FILE__ ),
				'plugin_dir' => plugin_dir_url( __FILE__ ),
				'description_length' => 150
			);

			load_textdomain( 'appex-metamax', $this->settings['plugin_path'] . 'languages/' . get_locale() . '.mo' );

			Appex_Metamax::add_actions();

		}



		public static function output_meta_element( $array ) {

			if ( is_array( $array ) ) {
				$element = "<meta ";

				foreach ( $array as $attribute => $value ) {
					$element .= $attribute . '="' . $value . '" ';
				}

				$element .= "/>\n";

				echo $element;
			}

		}

		public static function add_actions() {

			add_action( 'wp_head', array( 'appex_metamax', 'output_metamax' ) );

		}

		public static function output_metamax() {

			echo "\n<!-- ";
			echo __( 'MetaMax loves meta tags! One-click install and configure.', 'appex-metamax' );
			echo " -->\n";

			
			// Title
			$title = Appex_Metamax_Defaults::get_document_title();
			Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_title( $title ) );

			// Site name
			Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_site_name( get_bloginfo( 'name' ) ) );

			if ( is_home() ) {

				$blog_description = get_bloginfo( 'description' );

				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_description( $blog_description ) );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_description( $blog_description ) );

				// Images
				$post_images = Appex_Metamax_Defaults::get_document_images();
				foreach ( $post_images as $post_image ) {

					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_width( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_height( $post_image ) );

				}

			}
			elseif ( is_singular() ) {

				global $post;
				setup_postdata( $post );

				// Description
				$excerpt = Appex_Metamax_Defaults::get_document_excerpt();

				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_description( $excerpt ) );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_description( $excerpt ) );

				// Post data
				if(  get_post_type( $post->ID ) == 'post' ) {

					$published_date = get_the_date( DATE_W3C );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_article_published_time( $published_date ) );

					$modified_date = get_the_modified_date( DATE_W3C );

					if ( $published_date != $modified_date ) {

						Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_article_modified_time( $modified_date ) );
						Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_updated_time( $modified_date ) );

					}
				}

				// Images
				$post_images = Appex_Metamax_Defaults::get_document_images();
				foreach ( $post_images as $post_image ) {

					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_width( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_height( $post_image ) );

				}

				// Video

				if ( get_post_format( $post->ID ) == 'video' || has_filter( Appex_Metamax::FILTER_META_OG_VIDEO ) ) {
	
					$video_url = Appex_Metamax_Defaults::get_first_embed_url();

					if( $video_url ) {

						Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_video( $video_url ) );

					}

				}

			}
			elseif ( is_author() ) {
				
				$author_description = Appex_Metamax_Defaults::get_author_description();
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_description( $author_description ) );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_description( $author_description ) );

				$author_first_name = get_the_author_meta( 'first_name' );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_profile_first_name( $author_first_name ) );

				$author_last_name = get_the_author_meta( 'last_name' );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_profile_last_name( $author_last_name ) );

				// Images
				$post_images = Appex_Metamax_Defaults::get_document_images();

				foreach ( $post_images as $post_image ) {

					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_width( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_height( $post_image ) );

				}

			}
			// Archive pages, category, tag
			elseif ( is_archive() ) {

				$tag_default_description = Appex_Metamax_Defaults::get_category_description();

				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_description( $tag_default_description ) );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_description( $tag_default_description ) );

				// Images
				$post_images = apply_filters( Appex_Metamax::FILTER_META_OG_IMAGE, array() );

				if( count( $post_images ) == 0 ) {
					$post_images = apply_filters( Appex_Metamax::FILTER_META_DEFAULT_OG_IMAGE, $post_images );
				}

				foreach ( $post_images as $post_image ) {

					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_width( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_height( $post_image ) );

				}

			}
			elseif ( is_search() ) {
				$blog_description = get_bloginfo( 'description' );

				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_description( $blog_description ) );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_description( $blog_description ) );

				// Images
				$post_images = apply_filters( Appex_Metamax::FILTER_META_OG_IMAGE, array() );

				if( count( $post_images ) == 0 ) {
					$post_images = apply_filters( Appex_Metamax::FILTER_META_DEFAULT_OG_IMAGE, $post_images );
				}

				foreach ( $post_images as $post_image ) {

					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_width( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_height( $post_image ) );

				}

				echo '<meta name="robots" content="noindex,follow">\n';

			}
			elseif ( is_404() ) {
				$blog_description = get_bloginfo( 'description' );

				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_description( $blog_description ) );
				Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_description( $blog_description ) );

				// Images
				$post_images = apply_filters( Appex_Metamax::FILTER_META_OG_IMAGE, array() );

				if( count( $post_images ) == 0 ) {
					$post_images = apply_filters( Appex_Metamax::FILTER_META_DEFAULT_OG_IMAGE, $post_images );
				}

				foreach ( $post_images as $post_image ) {

					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_width( $post_image ) );
					Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_image_height( $post_image ) );

				}

				echo '<meta name="robots" content="noindex,follow">';
				echo "\n";

			}

			// Type	
			Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_type( Appex_Metamax_Defaults::get_document_type() ) );

			//Url
			$protocol = is_ssl() ? 'https://' : 'http://';
			Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_url( "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ) );

			// Locale
			Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_og_locale( get_locale() ) );

			// fb:app_id
			Appex_Metamax::output_meta_element( Appex_Metamax_Builders::build_meta_fb_app_id(  ) );			

			// Twitter card style
			$twitter_card_style = apply_filters( Appex_Metamax::FILTER_META_TWITTER_CARD, 'summary_large_image' );
			echo "<meta name='twitter:card' content='".$twitter_card_style."'>";

			echo "<!-- ";
			echo __( 'MetaMax all done. Now drink coffee!', 'appex-metamax' );
			echo " -->\n\n";

		}

	}

	function appex_metamax() {

		global $appex_metamax;

		if( !isset( $appex_metamax ) ) {

			$appex_metamax = new Appex_Metamax();
			$appex_metamax->initialize();

		}

		return $appex_metamax;

	}

	appex_metamax();

endif;
