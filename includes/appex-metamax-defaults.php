<?php
class Appex_Metamax_Defaults {

	public static function get_document_title() {

		global $post;
		$title = '';

		if ( current_theme_supports( 'title-tag') ) {

			$title = wp_get_document_title();

		}
		elseif ( is_singular() ) {

			$title = get_the_title() .' &mdash; '. get_bloginfo( 'name' );

		}

		return $title;

	}

	public static function get_document_excerpt() {

		global $post;

		$settings = appex_metamax()->settings;

		if ( empty( $max_length ) ) {
			$max_length = $settings['description_length'];
		}

		// Get excerpt if it exists, otherwise get the full content
		$excerpt_raw = empty( $post->post_excerpt ) ? $post->post_content : $post->post_excerpt;

		if( $excerpt_raw == '' ) {

			$excerpt_raw = get_bloginfo( 'description' );

		}

		// Remove unwanted
		$excerpt = strip_tags( $excerpt_raw );
		$excerpt = strip_shortcodes( $excerpt );

		// Trim down the length
		if ( mb_strlen( $excerpt ) > $max_length ) {
			$excerpt = mb_strimwidth( $excerpt, 0, $max_length, '&hellip;' );
		}

		return $excerpt;

	}

	public static function get_document_images() {

		global $post;
		
		$maximum_number_of_images = 5;
		$i = 0;
		$document_images = array();

		if ( $post ) {

			$post_thumbnail_id = null;

			// Let's output the post thumbnail first, if it exists
			if ( has_post_thumbnail() ) {

				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				$document_images[] = Appex_Metamax_Defaults::get_document_image_data( $img );
				$post_thumbnail_id = get_post_thumbnail_id( $post->ID );

				$i++;

			}

			// If author page lets try getting the avatar
			if( is_author() ) {

				$author_id = $post->post_author;
				$avatar_url = get_avatar_url( $author_id, array( 'size' => 512 ) );

				if ( $avatar_url ) {

					$document_images[] = array(
						$avatar_url, 
						512, 
						512 
					);

				}
			}

		}

		$document_images = apply_filters( Appex_Metamax::FILTER_META_OG_IMAGE, $document_images );

		if( count( $document_images ) == 0 ) {
			$document_images = apply_filters( Appex_Metamax::FILTER_META_DEFAULT_OG_IMAGE, $document_images );
		}

		return $document_images;

	}

	public static function get_document_image_data( $img ) {

		global $post;

		if ( count( $img ) > 0 ) {

			$longest_side = $img[1] > $img[2] ? $img[1] : $img[2];

			// Original image might be to large for the 5MB max limit, unqualified quess, should use filesize.
			if ( $longest_side > 2500 ) {

				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );

				// Global content_width variable returns wrong w/h when using wp_get_attachment_image_src()
				if ( isset( $GLOBALS['content_width']) ) {

					$image_meta = wp_get_attachment_metadata( get_post_thumbnail_id( $post->ID ), false );
					$image_sizes = $image_meta['sizes'];

					if ( $image_sizes['large'] ) {

						$large = $image_sizes['large'];
						$img[1] = $large['width'];
						$img[2] = $large['height'];

					}

				}

			}

		}

		return $img;

	}

	public static function get_first_embed_url() {
		
		global $post;
		$content = $post->post_content;
		
		if ( preg_match( '|^\s*(https?://[^\s"]+)\s*$|im', $content, $matches ) ) {

			return $matches[1];
		
		}

		return false;
		
	}

	public static function get_document_type() {

		global $post;
		$document_type = 'website';

		if ( $post && !is_archive() ) {

			$post_type = get_post_type( $post->ID );

			if ( !is_home() && !is_front_page() && $post_type == 'post' ) {

				$document_type = 'article';

			}

			if ( current_theme_supports( 'post-formats' ) ) {

				$post_format = get_post_format( $post->ID );

				if ( $post_format == 'video' ) {

					$document_type = 'video';

				}

			}

		}

		if( is_author() ) {

			$document_type = 'profile';

		}

		return $document_type;

	}

	public static function get_category_description() {

		$description = strip_tags( term_description() );

		if( $description == '' ) {

			$description = get_bloginfo( 'description' );

		}

		$settings = appex_metamax()->settings;

		if ( empty( $max_length ) ) {

			$max_length = $settings['description_length'];

		}

		// Trim down the length
		if ( mb_strlen( $description ) > $max_length ) {

			$description = mb_strimwidth( $description, 0, $max_length, '&hellip;' );

		}

		return $description;

	}

	public static function get_author_description() {

		$description = strip_tags( get_the_author_meta( 'description' ) );

		if( $description == '' ) {

			$description = get_bloginfo( 'description' );

		}

		$settings = appex_metamax()->settings;

		if ( empty( $max_length ) ) {

			$max_length = $settings['description_length'];

		}

		// Trim down the length
		if ( mb_strlen( $description ) > $max_length ) {

			$description = mb_strimwidth( $description, 0, $max_length, '&hellip;' );

		}

		return $description;

	}

}