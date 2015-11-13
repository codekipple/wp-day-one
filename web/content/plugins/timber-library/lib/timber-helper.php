<?php

/**
 * As the name suggests these are helpers for Timber (and you!) when developing. You can find additional (mainly internally-focused helpers) in TimberURLHelper
 */
class TimberHelper {

	/**
	 * A utility for a one-stop shop for Transients
	 * @api
	 * @example
	 * ```php
	 * $favorites = Timber::transient('user-'.$uid.'-favorites', function() use ($uid) {
	 *  	//some expensive query here that's doing something you want to store to a transient
	 *  	return $favorites;
	 * }, 600);
	 * Timber::context['favorites'] = $favorites;
	 * Timber::render('single.twig', $context);
	 * ```
	 *
	 * @param string  $slug           Unique identifier for transient
	 * @param callable $callback      Callback that generates the data that's to be cached
	 * @param int     $transient_time (optional) Expiration of transients in seconds
	 * @param int     $lock_timeout   (optional) How long (in seconds) to lock the transient to prevent race conditions
	 * @param bool    $force          (optional) Force callback to be executed when transient is locked
	 * @return mixed
	 */
	public static function transient( $slug, $callback, $transient_time = 0, $lock_timeout = 5, $force = false ) {

		$enable_transients = ( $transient_time === false || ( defined( 'WP_DISABLE_TRANSIENTS' ) && WP_DISABLE_TRANSIENTS ) ) ? false : true;
		$data = $enable_transients ? get_transient( $slug ) : false;

		if ( false === $data ) {

			if ( $enable_transients && self::_is_transient_locked( $slug ) ) {

				$force = apply_filters( 'timber_force_transients', $force );
				$force = apply_filters( 'timber_force_transient_' . $slug, $force );

				if ( !$force ) {
					//the server is currently executing the process.
					//We're just gonna dump these users. Sorry!
					return false;
				}

				$enable_transients = false;
			}

			// lock timeout shouldn't be higher than 5 seconds, unless
			// remote calls with high timeouts are made here
			if ( $enable_transients )
				self::_lock_transient( $slug, $lock_timeout );

			$data = $callback();

			if ( $enable_transients ) {
				set_transient( $slug, $data, $transient_time );
				self::_unlock_transient( $slug );
			}

		}

		return $data;

	}

	/**
	 * @internal
	 * @param string $slug
	 * @param integer $lock_timeout
	 */
	static function _lock_transient( $slug, $lock_timeout ) {
		set_transient( $slug . '_lock', true, $lock_timeout );
	}

	/**
	 * @internal
	 * @param string $slug
	 */
	static function _unlock_transient( $slug ) {
		delete_transient( $slug . '_lock', true );
	}

	/**
	 * @internal
	 * @param string $slug
	 */
	static function _is_transient_locked( $slug ) {
		return (bool)get_transient( $slug . '_lock' );
	}

	/* These are for measuring page render time */

	/**
	 * For measuring time, this will start a timer
	 * @api
	 * @return float
	 */
	public static function start_timer() {
		$time = microtime();
		$time = explode( ' ', $time );
		$time = $time[1] + $time[0];
		return $time;
	}

	/**
	 * For stopping time and getting the data
	 * @example
	 * ```php
	 * $start = TimberHelper::start_timer();
	 * // do some stuff that takes awhile
	 * echo TimberHelper::stop_timer( $start );
	 * ```
	 * @param int     $start
	 * @return string
	 */
	public static function stop_timer( $start ) {
		$time = microtime();
		$time = explode( ' ', $time );
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round( ( $finish - $start ), 4 );
		return $total_time . ' seconds.';
	}

	/* Function Utilities
	======================== */

	/**
	 * Calls a function with an output buffer. This is useful if you have a function that outputs text that you want to capture and use within a twig template.
	 * @example
	 * ```php
	 * function the_form() {
	 *     echo '<form action="form.php"><input type="text" /><input type="submit /></form>';
	 * }
	 *
	 * $context = Timber::get_context();
	 * $context['post'] = new TimberPost();
	 * $context['my_form'] = TimberHelper::ob_function('the_form');
	 * Timber::render('single-form.twig', $context);
	 * ```
	 * ```twig
	 * <h1>{{ post.title }}</h1>
	 * {{ my_form }}
	 * ```
	 * ```html
	 * <h1>Apply to my contest!</h1>
	 * <form action="form.php"><input type="text" /><input type="submit /></form>
	 * ```
	 * @api
	 * @param callback $function
	 * @param array   $args
	 * @return string
	 */
	public static function ob_function( $function, $args = array( null ) ) {
		ob_start();
		call_user_func_array( $function, $args );
		$data = ob_get_contents();
		ob_end_clean();
		return $data;
	}

	/**
	 *
	 *
	 * @param string  $function_name
	 * @param integer[]   $defaults
	 * @param bool    $return_output_buffer
	 * @return TimberFunctionWrapper
	 */
	public static function function_wrapper( $function_name, $defaults = array(), $return_output_buffer = false ) {
		return new TimberFunctionWrapper( $function_name, $defaults, $return_output_buffer );
	}

	/**
	 *
	 *
	 * @param mixed $arg that you want to error_log
	 * @return void
	 */
	public static function error_log( $arg ) {
		if ( !WP_DEBUG ) {
			return;
		}
		if ( is_object( $arg ) || is_array( $arg ) ) {
			$arg = print_r( $arg, true );
		}
		return error_log( $arg );
	}

	/**
	 *
	 *
	 * @param string  $separator
	 * @param string  $seplocation
	 * @return string
	 */
	public static function get_wp_title( $separator = ' ', $seplocation = 'left' ) {
		$separator = apply_filters( 'timber_wp_title_seperator', $separator );
		return trim( wp_title( $separator, false, $seplocation ) );
	}

	/* Text Utilities
	======================== */

	/**
	 *
	 *
	 * @param string  $text
	 * @param int     $num_words
	 * @param string|null|false  $more text to appear in "Read more...". Null to use default, false to hide
	 * @param string  $allowed_tags
	 * @return string
	 */
	public static function trim_words( $text, $num_words = 55, $more = null, $allowed_tags = 'p a span b i br blockquote' ) {
		if ( null === $more ) {
			$more = __( '&hellip;' );
		}
		$original_text = $text;
		$allowed_tag_string = '';
		foreach ( explode( ' ', apply_filters( 'timber/trim_words/allowed_tags', $allowed_tags ) ) as $tag ) {
			$allowed_tag_string .= '<' . $tag . '>';
		}
		$text = strip_tags( $text, $allowed_tag_string );
		/* translators: If your word count is based on single characters (East Asian characters),
		enter 'characters'. Otherwise, enter 'words'. Do not translate into your own language. */
		if ( 'characters' == _x( 'words', 'word count: words or characters?' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
			preg_match_all( '/./u', $text, $words_array );
			$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
			$sep = '';
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
			$sep = ' ';
		}
		if ( count( $words_array ) > $num_words ) {
			array_pop( $words_array );
			$text = implode( $sep, $words_array );
			$text = $text . $more;
		} else {
			$text = implode( $sep, $words_array );
		}
		$text = self::close_tags( $text );
		return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
	}

	/**
	 *
	 *
	 * @param string  $html
	 * @return string
	 */
	public static function close_tags( $html ) {
		//put all opened tags into an array
		preg_match_all( '#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result );
		$openedtags = $result[1];
		//put all closed tags into an array
		preg_match_all( '#</([a-z]+)>#iU', $html, $result );
		$closedtags = $result[1];
		$len_opened = count( $openedtags );
		// all tags are closed
		if ( count( $closedtags ) == $len_opened ) {
			return $html;
		}
		$openedtags = array_reverse( $openedtags );
		// close tags
		for ( $i = 0; $i < $len_opened; $i++ ) {
			if ( !in_array( $openedtags[$i], $closedtags ) ) {
				$html .= '</' . $openedtags[$i] . '>';
			} else {
				unset( $closedtags[array_search( $openedtags[$i], $closedtags )] );
			}
		}
		$html = str_replace(array('</br>','</hr>','</wbr>'), '', $html);
		$html = str_replace(array('<br>','<hr>','<wbr>'), array('<br />','<hr />','<wbr />'), $html);
		return $html;
	}

	/* WordPress Query Utilities
	======================== */

	/**
	 * @param string  $key
	 * @param string  $value
	 * @return array|int
	 * @deprecated 0.20.0
	 */
	public static function get_posts_by_meta( $key, $value ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", $key, $value );
		$results = $wpdb->get_col( $query );
		$pids = array();
		foreach ( $results as $result ) {
			if ( get_post( $result ) ) {
				$pids[] = $result;
			}
		}
		if ( count( $pids ) ) {
			return $pids;
		}
		return 0;
	}

	/**
	 *
	 *
	 * @param string  $key
	 * @param string  $value
	 * @return int
	 * @deprecated 0.20.0
	 */
	public static function get_post_by_meta( $key, $value ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ORDER BY post_id", $key, $value );
		$results = $wpdb->get_col( $query );
		foreach ( $results as $result ) {
			if ( $result && get_post( $result ) ) {
				return $result;
			}
		}
		return 0;
	}

	/**
	 *
	 * @deprecated 0.21.8
	 * @param int     $ttid
	 * @return mixed
	 */
	public static function get_term_id_by_term_taxonomy_id( $ttid ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT term_id FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = %s", $ttid );
		return $wpdb->get_var( $query );
	}

	/* Object Utilities
	======================== */

	/**
	 *
	 *
	 * @param array   $array
	 * @param string  $prop
	 * @return void
	 */
	public static function osort( &$array, $prop ) {
		usort( $array, function ( $a, $b ) use ( $prop ) {
				return $a->$prop > $b->$prop ? 1 : -1;
			} );
	}

	/**
	 *
	 *
	 * @param array   $arr
	 * @return bool
	 */
	public static function is_array_assoc( $arr ) {
		if ( !is_array( $arr ) ) {
			return false;
		}
		return (bool)count( array_filter( array_keys( $arr ), 'is_string' ) );
	}

	/**
	 *
	 *
	 * @param array   $array
	 * @return stdClass
	 */
	public static function array_to_object( $array ) {
		$obj = new stdClass;
		foreach ( $array as $k => $v ) {
			if ( is_array( $v ) ) {
				$obj->{$k} = self::array_to_object( $v ); //RECURSION
			} else {
				$obj->{$k} = $v;
			}
		}
		return $obj;
	}

	/**
	 *
	 *
	 * @param array   $array
	 * @param string  $key
	 * @param mixed   $value
	 * @return bool|int
	 */
	public static function get_object_index_by_property( $array, $key, $value ) {
		if ( is_array( $array ) ) {
			$i = 0;
			foreach ( $array as $arr ) {
				if ( is_array( $arr ) ) {
					if ( $arr[$key] == $value ) {
						return $i;
					}
				} else {
					if ( $arr->$key == $value ) {
						return $i;
					}
				}
				$i++;
			}
		}
		return false;
	}

	/**
	 *
	 *
	 * @param array   $array
	 * @param string  $key
	 * @param mixed   $value
	 * @return array|null
	 * @throws Exception
	 */
	public static function get_object_by_property( $array, $key, $value ) {
		if ( is_array( $array ) ) {
			foreach ( $array as $arr ) {
				if ( $arr->$key == $value ) {
					return $arr;
				}
			}
		} else {
			throw new Exception( '$array is not an array, given value: ' . $array );
		}
	}

	/**
	 *
	 *
	 * @param array   $array
	 * @param int     $len
	 * @return array
	 */
	public static function array_truncate( $array, $len ) {
		if ( sizeof( $array ) > $len ) {
			$array = array_splice( $array, 0, $len );
		}
		return $array;
	}

	/* Bool Utilities
	======================== */

	/**
	 *
	 *
	 * @param mixed   $value
	 * @return bool
	 */
	public static function is_true( $value ) {
		if ( isset( $value ) ) {
			if (is_string($value)) {
				$value = strtolower($value);
			}
			if ( ($value == 'true' || $value === 1 || $value === '1' || $value == true) && $value !== false && $value !== 'false') {
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 *
	 * @param int     $i
	 * @return bool
	 */
	public static function iseven( $i ) {
		return ( $i % 2 ) == 0;
	}

	/**
	 *
	 *
	 * @param int     $i
	 * @return bool
	 */
	public static function isodd( $i ) {
		return ( $i % 2 ) != 0;
	}

	/* Links, Forms, Etc. Utilities
	======================== */

	/**
	 *
	 * Gets the comment form for use on a single article page
	 * @deprecated 0.21.8 use `{{ function('comment_form') }}` instead
	 * @param int     $post_id which post_id should the form be tied to?
	 * @param array   $args this $args thing is a fucking mess, [fix at some point](http://codex.wordpress.org/Function_Reference/comment_form)
	 * @return string
	 */
	public static function get_comment_form( $post_id = null, $args = array() ) {
		return self::ob_function( 'comment_form', array( $args, $post_id ) );
	}

	/**
	 *
	 *
	 * @param string  $args
	 * @return array
	 */
	public static function paginate_links( $args = '' ) {
		$defaults = array(
			'base' => '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
			'format' => '?page=%#%', // ?page=%#% : %#% is replaced by the page number
			'total' => 1,
			'current' => 0,
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __( '&laquo; Previous' ),
			'next_text' => __( 'Next &raquo;' ),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'array',
			'add_args' => false, // array of query args to add
			'add_fragment' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		// Who knows what else people pass in $args
		$args['total'] = intval( (int)$args['total'] );
		if ( $args['total'] < 2 ) {
			return array();
		}
		$args['current'] = (int)$args['current'];
		$args['end_size'] = 0 < (int)$args['end_size'] ? (int)$args['end_size'] : 1; // Out of bounds?  Make it the default.
		$args['mid_size'] = 0 <= (int)$args['mid_size'] ? (int)$args['mid_size'] : 2;
		$args['add_args'] = is_array( $args['add_args'] ) ? $args['add_args'] : false;
		$page_links = array();
		$dots = false;
		if ( $args['prev_next'] && $args['current'] && 1 < $args['current'] ) {
			$link = str_replace( '%_%', 2 == $args['current'] ? '' : $args['format'], $args['base'] );
			$link = str_replace( '%#%', $args['current'] - 1, $link );
			if ( $args['add_args'] ) {
				$link = add_query_arg( $args['add_args'], $link );
			}
			$link .= $args['add_fragment'];
			$link = untrailingslashit( $link );
			$page_links[] = array(
				'class' => 'prev page-numbers',
				'link' => esc_url( apply_filters( 'paginate_links', $link ) ),
				'title' => $args['prev_text']
			);
		}
		for ( $n = 1; $n <= $args['total']; $n++ ) {
			$n_display = number_format_i18n( $n );
			if ( $n == $args['current'] ) {
				$page_links[] = array(
					'class' => 'page-number page-numbers current',
					'title' => $n_display,
					'text' => $n_display,
					'name' => $n_display,
					'current' => true
				);
				$dots = true;
			} else {
				if ( $args['show_all'] || ( $n <= $args['end_size'] || ( $args['current'] && $n >= $args['current'] - $args['mid_size'] && $n <= $args['current'] + $args['mid_size'] ) || $n > $args['total'] - $args['end_size'] ) ) {
					$link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
					$link = str_replace( '%#%', $n, $link );
					$link = trailingslashit( $link ) . ltrim( $args['add_fragment'], '/' );
					if ( $args['add_args'] ) {
						$link = rtrim( add_query_arg( $args['add_args'], $link ), '/' );
					}
					$link = str_replace(' ', '+', $link);
					$link = untrailingslashit( $link );
					$page_links[] = array(
						'class' => 'page-number page-numbers',
						'link' => esc_url( apply_filters( 'paginate_links', $link ) ),
						'title' => $n_display,
						'name' => $n_display,
						'current' => $args['current'] == $n
					);
					$dots = true;
				} elseif ( $dots && !$args['show_all'] ) {
					$page_links[] = array(
						'class' => 'dots',
						'title' => __( '&hellip;' )
					);
					$dots = false;
				}
			}
		}
		if ( $args['prev_next'] && $args['current'] && ( $args['current'] < $args['total'] || -1 == $args['total'] ) ) {
			$link = str_replace( '%_%', $args['format'], $args['base'] );
			$link = str_replace( '%#%', $args['current'] + 1, $link );
			if ( $args['add_args'] ) {
				$link = add_query_arg( $args['add_args'], $link );
			}
			$link = untrailingslashit( trailingslashit( $link ) . $args['add_fragment'] );
			$page_links[] = array(
				'class' => 'next page-numbers',
				'link' => esc_url( apply_filters( 'paginate_links', $link ) ),
				'title' => $args['next_text']
			);
		}
		return $page_links;
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function get_current_url() {
		return TimberURLHelper::get_current_url();
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function is_url( $url ) {
		return TimberURLHelper::is_url( $url );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function get_path_base() {
		return TimberURLHelper::get_path_base();
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function get_rel_url( $url, $force = false ) {
		return TimberURLHelper::get_rel_url( $url, $force );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function is_local( $url ) {
		return TimberURLHelper::is_local( $url );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function get_full_path( $src ) {
		return TimberURLHelper::get_full_path( $src );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function get_rel_path( $src ) {
		return TimberURLHelper::get_rel_path( $src );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function remove_double_slashes( $url ) {
		return TimberURLHelper::remove_double_slashes( $url );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function prepend_to_url( $url, $path ) {
		return TimberURLHelper::prepend_to_url( $url, $path );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function preslashit( $path ) {
		return TimberURLHelper::preslashit( $path );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function is_external( $url ) {
		return TimberURLHelper::is_external( $url );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function download_url( $url, $timeout = 300 ) {
		return TimberURLHelper::download_url( $url, $timeout );
	}

	/**
	 * @deprecated 0.18.0
	 */
	static function get_params( $i = -1 ) {
		return TimberURLHelper::get_params( $i );
	}

}
