=== MetaMax ===
Contributors: andersdosen, eoutvik, heintore
Tags: seo, open graph, sharing, meta
Requires at least: 4.6
Tested up to: 4.7.2
Stable tag: 1.1.1
Tags: SEO, Google, meta description, meta title, noindex, Facebook, Twitter, LinkedIn, search engine optimization
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

MetaMax automagically inserts meta tags in your html to make your site more SEO friendly.

== Description ==

MetaMax is meant to be a lightweight and super simple replacement for Yoast SEO. He does not have the advanced GUI or content analysis that Yoast SEO offers, but instead focuses on good standards and less noise &mdash; all ready to go in one click. MetaMax is also more performant than Yoast SEO, and will not have the same impact on your pageload. But of course you always cache your site anyways, right? Made with passion by Appex!

= Tags =
SEO, Yoast, social, sharing, meta, meta tags, facebook, twitter, google, description

== Installation ==

For most use cases the MetaMax defaults will be sufficient, but if, for some insane reason, MetaMax is unable to generate the desired output on one or more meta tags, you can always hook on to his filters and tell him how he should handle your content. If you do, he will blindly follow your instructions even if it sets your server RAM on fire. Good luck!

== Filters ==

> Appex_Metamax::FILTER_META_OG_TITLE<br>
> Appex_Metamax::FILTER_META_DESCRIPTION<br>
> Appex_Metamax::FILTER_META_OG_DESCRIPTION<br>
> Appex_Metamax::FILTER_META_OG_LOCALE<br>
> Appex_Metamax::FILTER_META_OG_SITE_NAME<br>
> Appex_Metamax::FILTER_META_OG_URL<br>
> Appex_Metamax::FILTER_META_OG_VIDEO<br>
> Appex_Metamax::FILTER_META_OG_TYPE<br>
> Appex_Metamax::FILTER_META_PROFILE_FIRST_NAME<br>
> Appex_Metamax::FILTER_META_PROFILE_LAST_NAME<br>
> Appex_Metamax::FILTER_META_TWITTER_CARD<br>
> Appex_Metamax::FILTER_META_FB_APP_ID

The filters above will expect a string in return

= Example =

	if ( class_exists( 'Appex_Metamax' ) ) {

		add_filter( Appex_Metamax::FILTER_META_OG_TITLE, function( $content ) {

			// Override title if not on home page
			if( !is_home() ) {
			
				$content = 'My new fancy title';

			}

			return $content;

		});

	}



> Appex_Metamax::FILTER_META_OG_IMAGE<br>
> Appex_Metamax::FILTER_META_DEFAULT_OG_IMAGE

The image filters will expect an array of arrays with url, width and height in the following format: array(array(url, width, height));

= Example =

	// Default image fallback filter
	if ( class_exists( 'Appex_Metamax' ) ) {

		add_filter( Appex_Metamax::FILTER_META_DEFAULT_OG_IMAGE, function( $images ) {

			$images[] = wp_get_attachment_image_src( $attachment_id, 'large' );

			return $images;
			
		});

	}

	// Add images
	if ( class_exists( 'Appex_Metamax' ) ) {

		add_filter( Appex_Metamax::FILTER_META_OG_IMAGE, function( $images ) {

			$images[] = array(
							'http://example.com/images/waycoolimage.jpg', 
							1200, 
							800 
						);

			if( is_archive() ) {
							
				$images[] = wp_get_attachment_image_src( $attachment_id, 'large' );

			}

			return $images;

		});

	}

== Frequently Asked Questions ==

= Does MetaMax support WooCommerce? =

We don't know. He certainly does if you tell him how to handle WooCommerce content in his filters.

= Does MetaMax support multisite? =

Again, we don't know. Maybe you could try it out and give us some feedback? :)

== Changelog ==

= 1.1.1 =
* Fixed linebreak on 404 pages
* Fixed missing global $post object

= 1.1 =
* Fixed undefined $post_thumbnail_id bug
* Removed attached images from og:image array

= 1.0 =
* Initial release