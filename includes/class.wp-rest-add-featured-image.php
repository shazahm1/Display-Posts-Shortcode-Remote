<?php

if ( ! class_exists( 'Display_Posts_Remote_Add_Featured_Image' ) ) {

	/**
	 * Class Display_Posts_Remote_Add_Featured_Image
	 *
	 * Add the featured image meta to REST responses.
	 *
	 * @since 1.0
	 */
	class Display_Posts_Remote_Add_Featured_Image {

		/**
		 * @since 1.0
		 * @var array
		 */
		public $postTypes = array( 'post' );

		/**
		 * Display_Posts_Remote_Add_Featured_Image constructor.
		 *
		 * @since 1.0
		 */
		public function __construct() {

			add_action( 'rest_api_init', array( $this, 'addFeaturedImage' ) );
		}

		/**
		 * Register the REST field to the post object.
		 *
		 * @since 1.0
		 */
		public function addFeaturedImage() {

			/**
			 * Add 'featured_image'
			 */
			register_rest_field(
				$this->postTypes,
				'featured_image',
				array(
					'get_callback'    => array( $this, 'getFeaturedImageMeta' ),
					'update_callback' => NULL,
					'schema'          => NULL,
				)
			);
		}

		/**
		 * Callback for the REST field for `featured_image`.
		 *
		 * Populate the field with the featured image meta.
		 *
		 * @since 1.0
		 *
		 * @return array
		 */
		public function getFeaturedImageMeta() {

			$data = array( 'full' => $this->getImageMeta( 'full' ) );

			foreach ( $this->getImageSizes() as $size => $meta ) {

				$imageMeta = $this->getImageMeta( $size );

				if ( FALSE !== $imageMeta ) {

					$data[ $size ] = $imageMeta;
				}
			}

			return $data;
		}

		/**
		 * Get all the registered image sizes.
		 *
		 * @since 1.0
		 *
		 * @return array
		 */
		public function getImageSizes() {

			global $_wp_additional_image_sizes;

			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $size ) {

				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

					$sizes[ $size ]['width']  = get_option( "{$size}_size_w" );
					$sizes[ $size ]['height'] = get_option( "{$size}_size_h" );
					$sizes[ $size ]['crop']   = (bool) get_option( "{$size}_crop" );

				} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {

					$sizes[ $size ] = array(
						'width'  => $_wp_additional_image_sizes[ $size ]['width'],
						'height' => $_wp_additional_image_sizes[ $size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $size ]['crop'],
					);
				}
			}

			return $sizes;
		}

		/**
		 * Get the image meta for the supplied image size.
		 *
		 * @since 1.0
		 *
		 * @param $size
		 *
		 * @return array|false
		 */
		public function getImageMeta( $size ) {

			$id = get_the_ID();

			if ( has_post_thumbnail( $id ) ) {

				return wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );

			} else {

				return FALSE;
			}
		}
	}

	new Display_Posts_Remote_Add_Featured_Image();
}
