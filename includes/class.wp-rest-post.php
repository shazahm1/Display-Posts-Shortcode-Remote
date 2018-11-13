<?php

if ( ! class_exists( 'Display_Posts_Remote_Post' ) ) {

	/**
	 * Class Display_Posts_Remote_Post
	 *
	 * Helper class for working with a post object from a REST response.
	 *
	 * The helper methods are intended to mimic the functionality of the like named counterparts in WP core
	 * for working with posts data within the loop.
	 *
	 * @since 1.0
	 */
	class Display_Posts_Remote_Post {

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 *
		 * @param array $post
		 */
		public function __construct( $post ) {

			foreach ( $post as $key => $value ) {

				$this->$key = $value;
			}
		}

		/**
		 * @since 1.0
		 *
		 * @param string $key Property to check if set.
		 *
		 * @return bool
		 */
		public function __isset( $key ) {

			return isset( $this->$key );
		}

		/**
		 * @since 1.0
		 *
		 * @param string $key Key to get.
		 *
		 * @return mixed
		 */
		public function __get( $key ) {

			return isset( $this->$key ) ? $this->$key : NULL;
		}

		/**
		 * Return the post ID.
		 *
		 * @since 1.0
		 *
		 * @return bool|mixed
		 */
		public function get_the_ID() {

			return isset( $this->id ) ? $this->id : FALSE;
		}

		/**
		 * Display the post ID.
		 *
		 * @since 1.0
		 */
		public function the_ID() {

			echo $this->get_the_ID();
		}

		/**
		 * Get the post permalink.
		 *
		 * @since 1.0
		 *
		 * @return bool|string
		 */
		public function get_permalink() {

			return isset( $this->link ) ? $this->link : FALSE;
		}

		/**
		 * Display the post permalink escaped for display.
		 *
		 * @since 1.0
		 *
		 * @return bool|string
		 */
		public function the_permalink() {

			$permalink = $this->get_permalink();

			if ( FALSE === $permalink ) {

				return FALSE;
			}

			return esc_url( $permalink );
		}

		/**
		 * Get the post date.
		 *
		 * @since 1.0
		 *
		 * @param string $format PHP date format defaults to the date_format option if not specified.
		 *
		 * @return bool|string
		 */
		public function get_the_date( $format = '' ) {

			if ( '' == $format ) {

				$date = mysql2date( get_option( 'date_format' ), $this->date );

			} else {

				$date = mysql2date( $format, $this->date );
			}

			return $date;
		}

		/**
		 * Display the post date.
		 *
		 * @since 1.0
		 *
		 * @param string $format PHP date format defaults to the date_format option if not specified.
		 * @param string $before Output before the date.
		 * @param string $after  Output after the date.
		 * @param bool   $echo   Whether to echo the date or return it.
		 *
		 * @return string|void
		 */
		public function the_date( $format = '', $before = '', $after = '', $echo = TRUE ) {

			$date = $before . $this->get_the_date( $format ) . $after;

			if ( $echo ) {

				echo $date;

			} else {

				/** @noinspection PhpInconsistentReturnPointsInspection */
				return $date;
			}

		}

		/**
		 * Get the post last modified date.
		 *
		 * @since 1.0
		 *
		 * @param string $format PHP date format defaults to the date_format option if not specified.
		 *
		 * @return bool|string
		 */
		public function get_the_modified_date( $format = '' ) {

			if ( '' == $format ) {

				$date = mysql2date( get_option( 'date_format' ), $this->modified );

			} else {

				$date = mysql2date( $format, $this->modified );
			}

			return $date;
		}

		/**
		 * Display the post last modified date.
		 *
		 * @since 1.0
		 *
		 * @param string $format PHP date format defaults to the date_format option if not specified.
		 * @param string $before Output before the date.
		 * @param string $after  Output after the date.
		 * @param bool   $echo   Whether to echo the date or return it.
		 *
		 * @return string|void
		 */
		public function the_modified_date( $format = '', $before = '', $after = '', $echo = TRUE ) {

			$date = $before . $this->get_the_date( $format ) . $after;

			if ( $echo ) {

				echo $date;

			} else {

				/** @noinspection PhpInconsistentReturnPointsInspection */
				return $date;
			}

		}

		/**
		 * Get the post title.
		 *
		 * @since 1.0
		 */
		public function get_the_title() {

			return isset( $this->title->rendered ) ? $this->title->rendered : '';
		}

		/**
		 * Display the post title.
		 *
		 * @since 1.0
		 *
		 * @param string $before
		 * @param string $after
		 * @param bool   $echo
		 *
		 * @return string|void
		 */
		public function the_title( $before = '', $after = '', $echo = TRUE ) {

			$title = $this->get_the_title();

			if ( strlen( $title ) == 0 ) {

				return;
			}

			$title = $before . $title . $after;

			if ( $echo ) {

				echo $title;

			} else {

				/** @noinspection PhpInconsistentReturnPointsInspection */
				return $title;
			}
		}

		/**
		 * Display the post title escaped for display.
		 *
		 * @since 1.0
		 *
		 * @param string|array $untrusted {
		 *     Title attribute arguments. Optional.
		 *
		 *     @type string  $before Markup to prepend to the title. Default empty.
		 *     @type string  $after  Markup to append to the title. Default empty.
		 *     @type bool    $echo   Whether to echo or return the title. Default true for echo.
		 *     @type WP_Post $post   Current post object to retrieve the title for.
		 * }
		 *
		 * @return string|void String when echo is false.
		 */
		public function the_title_attribute( $untrusted = '' ) {

			$defaults = array(
				'before' => '',
				'after'  => '',
				'echo'   => TRUE,
			);

			$atts = wp_parse_args( $untrusted, $defaults );

			$title = $this->get_the_title();

			if ( strlen( $title ) == 0 ) {
				return;
			}

			$title = $atts['before'] . $title . $atts['after'];
			$title = esc_attr( strip_tags( $title ) );

			if ( $atts['echo'] ) {

				echo $title;

			} else {

				/** @noinspection PhpInconsistentReturnPointsInspection */
				return $title;
			}
		}

		/**
		 * Get the post content.
		 *
		 * @since 1.0
		 *
		 * @return string
		 */
		public function get_the_content() {

			return isset( $this->content ) ? $this->content->rendered : '';
		}

		/**
		 * Display the post content.
		 *
		 * @since 1.0
		 */
		public function the_content() {

			echo $this->get_the_content();
		}

		/**
		 * Whether or not the post has a featured image attached.
		 *
		 * This checks the REST response for the `featured_image` property which would exist if this plugin
		 * is installed on the remote site sending the REST response.
		 *
		 * @since 1.0
		 *
		 * @return bool
		 */
		public function has_post_thumbnail() {

			return isset( $this->featured_image->full );
		}

		/**
		 * Whether or not the post has a featured image attached.
		 *
		 * This checks the REST response for the `wp:featuredmedia` property would would exists if `_embed` was
		 * sent as part of the GET request.
		 *
		 * @since 1.0
		 *
		 * @return bool
		 */
		public function has_featured_media() {

			$featured = 'wp:featuredmedia';

			return isset( $this->_embedded->$featured[0]->source_url );
		}

		/**
		 * Get the image meta of the requested image size.
		 *
		 * This checks the REST response for the `featured_image` property which would exist if this plugin
		 * is installed on the remote site sending the REST response.
		 *
		 * @since 1.0
		 *
		 * @param string $size
		 *
		 * @return bool
		 */
		public function get_attachment_image_src( $size = 'thumbnail' ) {

			return isset( $this->featured_image->$size ) ? $this->featured_image->$size : FALSE;
		}

		/**
		 * Get the image meta of the attached featured image.
		 *
		 * This checks the REST response for the `wp:featuredmedia` property would would exists if `_embed` was
		 * sent as part of the GET request.
		 *
		 * @since 1.0
		 *
		 * @return array|bool
		 */
		public function get_featured_media_src() {

			if ( ! $this->has_featured_media() ) {

				return FALSE;
			}

			$featured = 'wp:featuredmedia';

			$image = $this->_embedded->$featured[0];

			return array(
				$image->source_url,
				$image->media_details->width,
				$image->media_details->height,
			);
		}

		/**
		 * Returns the post's attached featured image HTML.
		 *
		 * @since 1.0
		 *
		 * @param string|array $size Image size to use. Accepts any valid image size, or
		 *                           an array of width and height values in pixels (in that order).
		 *                           Default 'post-thumbnail'.
		 * @param string|array $attr Query string or array of attributes. Default empty.
		 *
		 * @return string The post thumbnail image tag.
		 */
		public function get_the_post_thumbnail( $size = 'thumbnail', $attr = '' ) {

			return $this->get_attachment_image( $size, $attr );
		}

		/**
		 * Return the HTML img element representing an image attachment.
		 *
		 * @since 1.0
		 *
		 * @param string|array $size Image size. Accepts any valid image size, or an array of width
		 *                           and height values in pixels (in that order).
		 * @param string|array $attr Attributes for the image markup.
		 *
		 * @return string HTML img element or empty string on failure.
		 */
		public function get_attachment_image( $size = 'thumbnail', $attr = '' ) {

			$html  = '';
			$image = $this->get_attachment_image_src( $size );

			if ( FALSE == $image ) {

				$size  = 'full';
				$image = $this->get_featured_media_src();
			}

			if ( $image ) {

				list( $src, $width, $height ) = $image;

				$hwstring   = image_hwstring( $width, $height );
				$size_class = $size;

				if ( is_array( $size_class ) ) {
					$size_class = join( 'x', $size_class );
				}

				$default_attr = array(
					'src'	=> $src,
					'class'	=> "attachment-$size_class size-$size_class",
				);

				$attr = wp_parse_args( $attr, $default_attr );

				$attr = array_map( 'esc_attr', $attr );
				$html = rtrim("<img $hwstring");

				foreach ( $attr as $name => $value ) {
					$html .= " $name=" . '"' . $value . '"';
				}

				$html .= ' />';
			}

			return $html;
		}

	}
}
