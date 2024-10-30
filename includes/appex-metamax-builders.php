<?php
class Appex_Metamax_Builders {

	public static function build_meta_og_title( $content ) {

		$content = apply_filters( Appex_Metamax::FILTER_META_OG_TITLE, $content );

		if ( empty( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'og:title',
			'content' => esc_attr( $content )
		);

		return $output;

	}

	public static function build_meta_og_site_name( $site_name ) {

		$site_name = apply_filters( Appex_Metamax::FILTER_META_OG_SITE_NAME, $site_name );

		if ( empty ( $site_name ) ) {

			return;

		}

		$output = array(
			'property' => 'og:site_name',
			'content' => esc_attr( $site_name )
		);

		return $output;

	}

	public static function build_meta_description( $content ) {

		$content = apply_filters( Appex_Metamax::FILTER_META_DESCRIPTION, $content );

		if ( empty( $content ) ) {

			return;

		}

		$output = array(
			'name' => 'description',
			'content' => esc_attr( $content )
		);

		return $output;

	}

	public static function build_meta_og_description( $content ) {

		$content = apply_filters( Appex_Metamax::FILTER_META_OG_DESCRIPTION, $content );

		if ( empty( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'og:description',
			'content' => esc_attr( $content )
		);

		return $output;

	}

	public static function build_meta_og_url( $content ) {

		$content = apply_filters( Appex_Metamax::FILTER_META_OG_URL, $content );

		if ( empty( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'og:url',
			'content' => $content
		);

		return $output;

	}

	public static function build_meta_fb_app_id() {

		$content = apply_filters( Appex_Metamax::FILTER_META_FB_APP_ID, '' );

		if ( empty( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'fb:app_id',
			'content' => $content
		);

		return $output;

	}

	public static function build_meta_og_image( $image ) {

		if ( empty( $image ) ) {

			return;

		}

		$output = array(
			'property' => 'og:image',
			'content' => $image[0]
		);

		return $output;

	}

	public static function build_meta_og_image_width( $image ) {

		if ( empty( $image ) ) {

			return;

		}

		$output = array(
			'property' => 'og:image:width',
			'content' => $image[1]
		);

		return $output;

	}

	public static function build_meta_og_image_height( $image ) {

		if ( empty( $image ) ) {

			return;

		}

		$output = array(
			'property' => 'og:image:height',
			'content' => $image[2]
		);

		return $output;

	}

	public static function build_meta_og_locale( $locale ) {

		$locale = apply_filters( Appex_Metamax::FILTER_META_OG_LOCALE, $locale );

		if ( empty( $locale ) ) {

			return;

		}

		$output = array(
			'property' => 'og:locale',
			'content' => $locale
		);

		return $output;

	}

	public static function build_meta_og_video( $video_url ) {

		$video_url = apply_filters( Appex_Metamax::FILTER_META_OG_VIDEO, $video_url );

		if ( empty( $video_url ) ) {

			return;

		}

		$output = array(
			'property' => 'og:video',
			'content' => $video_url
		);

		return $output;

	}

	public static function build_meta_og_type( $type ) {

		$type = apply_filters( Appex_Metamax::FILTER_META_OG_TYPE, $type );

		if ( empty ( $type ) ) {

			return;

		}

		$output = array(
			'property' => 'og:type',
			'content' => $type
		);

		return $output;

	}

	public static function build_meta_profile_first_name( $content ) {

		$content = apply_filters( Appex_Metamax::FILTER_META_PROFILE_FIRST_NAME, $content );

		if ( empty ( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'profile:first_name',
			'content' => $content
		);

		return $output;

	}

	public static function build_meta_profile_last_name( $content ) {

		$content = apply_filters( Appex_Metamax::FILTER_META_PROFILE_LAST_NAME, $content );

		if ( empty ( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'profile:last_name',
			'content' => $content
		);

		return $output;

	}

	public static function build_category_meta_description( $content ) {

		if ( empty ( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'og:description',
			'content' => esc_attr( $content )
		);

		return $output;

	}

	public static function build_category_meta_og_description( $content ) {

		if ( empty ( $content ) ) {

			return;

		}

		$output = array(
			'name' => 'description',
			'content' => esc_attr( $content )
		);

		return $output;

	}

	public static function build_meta_article_published_time( $content ) {

		if ( empty ( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'article:published_time',
			'content' => esc_attr( $content )
		);

		return $output;

	}

	public static function build_meta_article_modified_time( $content ) {

		if ( empty ( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'article:modified_time',
			'content' => esc_attr( $content )
		);

		return $output;

	}

	public static function build_meta_og_updated_time( $content ) {

		if ( empty ( $content ) ) {

			return;

		}

		$output = array(
			'property' => 'og:updated_time',
			'content' => esc_attr( $content )
		);

		return $output;

	}

}
