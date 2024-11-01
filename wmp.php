<?php
/*
Plugin Name: WP Media Player
Plugin URI: https://wordpress.org/plugins/wp-media-player-addons/
Description: A media player plugin for WordPress. Extends and makes the default media player smarter.
Version: 1.0.2
Author: ThemeStones
Author URI: http://themestones.net
Text Domain: wmp
*/

/**
* 
*/
class TS_WMP {

	private static $_instance;

	public static function getInstance() {

		if( !(self::$_instance instanceof self) ) {
			self::$_instance = new self;
		}

		return self::$_instance;

	}
	
	function __construct() {

		add_filter( 'shortcode_atts_playlist', array( $this, 'sc_atts' ) );

		add_filter( 'do_shortcode_tag', array( $this, 'filter_op' ), 10, 3 );

		add_filter( 'wp_audio_shortcode', array( $this, 'audio_mod' ), 10, 5 );

		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );

		add_action( 'wp_print_footer_scripts', array( $this, 'mejs' ) );
	}

	function scripts() {

		wp_enqueue_style( 'wmp-icons', plugins_url( 'icons/style.css', __FILE__ ) );
		wp_enqueue_style( 'wmp-icons-ie7', plugins_url( 'icons/ie7/ie7.css', __FILE__ ) );
		wp_enqueue_style( 'wmp-main', plugins_url( 'css/main.css', __FILE__ ), array(), time() );

		wp_style_add_data( 'wmp-icons-ie7', 'conditional', 'lt IE 8' );

	}

	function admin_scripts( $hook ) {

		if( $hook == 'post.php' ) {
			wp_enqueue_script( 'wmp-options', plugins_url( 'js/options.js', __FILE__ ), array( 'media-views' ), time() );
		}

	}

	function p( $p, $var = false ) {
		echo '<pre>';
		if( $var ) {
			var_dump( (bool)$p );
		} else {
			print_r( $p );
		}
		echo '</pre>';
	}

	function sc_atts( $atts ) {

		if( $atts['tracklist'] == false || $atts['tracklist'] == 'false' ) {
			$atts['tracklist'] = true;
			$atts['wmp_compact_mode'] = true;
		}

		return $atts;

	}

	function filter_op( $output, $tag, $attr ) {

		$skin = '';
		if( isset( $attr['color'] ) && $attr['color'] == 'sunset' ) {
			$skin = ' color-skin';
		}

		if( 'playlist' == $tag ) {
			if( isset( $attr['tracklist'] ) && ( $attr['tracklist'] == false || $attr['tracklist'] == 'false' ) ) {
				$output = '<div class="wmp-playlist wmp-compact-playlist' . esc_attr( $skin ) . '">' . $output . '</div>';
			} else {
				$output = '<div class="wmp-playlist' . esc_attr( $skin ) . '">' . $output . '</div>';
			}
		}

		if( 'audio' == $tag || 'video' == $tag ) {
			$output = '<div class="wmp-player' . esc_attr( $skin ) . '">' . $output . '</div>';
		}

		return $output;
	}

	function get_id_by_guid( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) ); 
		return isset( $attachment[0] ) ? $attachment[0] : false; 
	}

	function audio_mod( $html, $atts, $audio, $post_id, $library ) {
	
		$attachment_available = true;

		$atts_mod = wp_parse_args( $atts,array(
			'src' => '',
			'mp3' => '',
			'ogg' => '',
			'wma' => '',
			'm4a' => '',
			'wav' => '',
		) );
		
		if( empty( $audio ) ) {
			$attachment_id = false;
			$attachment_available = false;
			$attachment_id_src = $this->get_id_by_guid( $atts_mod['src'] );
			$attachment_id_mp3 = $this->get_id_by_guid( $atts_mod['mp3'] );
			$attachment_id_ogg = $this->get_id_by_guid( $atts_mod['ogg'] );
			$attachment_id_wma = $this->get_id_by_guid( $atts_mod['wma'] );
			$attachment_id_m4a = $this->get_id_by_guid( $atts_mod['m4a'] );
			$attachment_id_wav = $this->get_id_by_guid( $atts_mod['wav'] );
			if( !empty( $atts_mod['src'] ) && !empty( $attachment_id_src ) ) {
				$attachment_available = true;
				$attachment_id = $attachment_id_src;
			} elseif( !empty( $atts_mod['mp3'] ) && !empty( $attachment_id_mp3 ) ) {
				$attachment_available = true;
				$attachment_id = $attachment_id_mp3;
			} elseif( !empty( $atts_mod['ogg'] ) && !empty( $attachment_id_ogg ) ) {
				$attachment_available = true;
				$attachment_id = $attachment_id_ogg;
			} elseif( !empty( $atts_mod['wma'] ) && !empty( $attachment_id_wma ) ) {
				$attachment_available = true;
				$attachment_id = $attachment_id_wma;
			} elseif( !empty( $atts_mod['m4a'] ) && !empty( $attachment_id_m4a ) ) {
				$attachment_available = true;
				$attachment_id = $attachment_id_m4a;
			} elseif( !empty( $atts_mod['wav'] ) && !empty( $attachment_id_wav ) ) {
				$attachment_available = true;
				$attachment_id = $attachment_id_wav;
			}
			if( $attachment_available ) {
				$audio = get_post( $attachment_id );
			}
		}
		
		if( $attachment_available ) {
			$meta = get_post_meta( $audio->ID, '_wp_attachment_metadata', true );
			/* translators: audio player secondary line. %1$s: Album, %2$s: Album Year, %3$s: Artist  */
			$secondary_line = sprintf( esc_html__( '%1$s (%2$s) | %3$s', 'suparnova' ), esc_attr( $meta['album'] ), esc_attr( $meta['year'] ), esc_attr( $meta['artist'] ) );
			$html = str_replace( '<audio ', sprintf( '<audio data-title="%s" data-album-artist="%s" ', esc_attr( $meta['title'] ), esc_attr( $secondary_line ) ), $html );
		}
		
		if( has_post_thumbnail( $audio ) ) {
			$id = get_post_thumbnail_id( $audio->ID );
			$src = wp_get_attachment_image_src( $id, 'full' );
			$poster = $src[0];
		} else {
			$poster = plugins_url( 'img/album-art.jpg', __FILE__ );
		}
		
		$html = str_replace( '<audio ', sprintf( '<audio poster="%s" ', esc_url( $poster ) ), $html );
		
		return $html;
		
	}

	function mejs() {
		if ( ! wp_script_is( 'mediaelement', 'done' ) || ! wp_script_is( 'jquery', 'done' ) ) {
			return;
		}
		?>
		<script>
		(function($) {
			"use strict";
			
			var settings = window._wpmejsSettings || {}, buttons, player, container, tracks, n, next, info;
			settings.features = settings.features || mejs.MepDefaults.features;
			settings.features.push( 'wmp' );
			
			MediaElementPlayer.prototype.buildwmp = function(player, controls, layers, media) {
				if( !player.isVideo ) {
					player.options.shuffle = false;
					buttons = '<div class="mejs-button mejs-wmp-button mejs-loop-button ' + ((player.options.loop) ? 'mejs-loop-on' : 'mejs-loop-off') + '"><button type="button" aria-controls="' + player.id + '" title="Loop" aria-label="Loop"></button></div>';
					
					// Add some handful information
					player.container.addClass('wmp-main-player').find('audio').attr('data-mejs-id', player.id);
				
					// Playlist
					container = player.container.closest('.wp-playlist').addClass('wmp-main-playlist');

					if( container.length > 0 ) {
						player.isWPplaylist = true;
						$('#' + player.id + ' audio').on('ended', function(e) {
							player = mejs.players[e.target.getAttribute('data-mejs-id')];
							tracks = player.container.closest('.wp-playlist').find('.wp-playlist-tracks');
							if( player.options.loop ) {
								next = tracks.children('.wp-playlist-playing');
								setTimeout(function() {
									next.click();
								}, 10);
							} else if( player.options.shuffle ) {
								n = Math.floor( Math.random() * tracks.children().length );
								next = tracks.children('.wp-playlist-item').not('.wp-playlist-playing').eq(n);
								setTimeout(function() {
									next.click();
								}, 10);
							}
						});
						container.find('.wp-playlist-prev').addClass('mejs-button').attr('data-mejs-id', player.id).html('<button type="button" aria-controls="' + player.id + '" title="Previous" aria-label="Previous"></button>').prependTo(controls);
						container.find('.wp-playlist-next').addClass('mejs-button').attr('data-mejs-id', player.id).html('<button type="button" aria-controls="' + player.id + '" title="Next" aria-label="Next"></button>').appendTo(controls);
						controls.prepend('<div class="mejs-button mejs-wmp-button mejs-shuffle-button mejs-shuffle-off"><button type="button" aria-controls="' + player.id + '" title="Shuffle" aria-label="Shuffle"></button></div>');
					} else {
						// Not a playlist, Add track info
						info = '<h6 class="mejs-track-title">' + player.$node.data('title') + '</h6><h6 class="mejs-track-metadata"><small>' + player.$node.data('album-artist') + '</small></h6>';
						layers.prepend(info);
					}
					
					controls.append(buttons);
				} else {
					player.container.addClass('wmp-main-video-player');
				
					// Playlist
					container = player.container.closest('.wp-playlist').addClass('wmp-main-playlist');
					if( container.length > 0 ) {
						container.find('.wp-playlist-prev').addClass('mejs-button').attr('data-mejs-id', player.id).html('<button type="button" aria-controls="' + player.id + '" title="Previous" aria-label="Previous"></button>').insertBefore(controls.find('.mejs-playpause-button'));
						container.find('.wp-playlist-next').addClass('mejs-button').attr('data-mejs-id', player.id).html('<button type="button" aria-controls="' + player.id + '" title="Next" aria-label="Next"></button>').insertAfter(controls.find('.mejs-playpause-button'));
					}
				}
			}
			
			$(document).on('click', '.mejs-wmp-button.mejs-shuffle-button button', function(e) {
				e.preventDefault();
				var loop = $(this);
				player = mejs.players[loop.attr('aria-controls')];
				player.options.shuffle = !player.options.shuffle;
				if (player.options.shuffle) {
					loop.parent().removeClass('mejs-shuffle-off').addClass('mejs-shuffle-on');
				} else {
					loop.parent().removeClass('mejs-shuffle-on').addClass('mejs-shuffle-off');
				}
			});
			
			$(document).on('click', '.mejs-wmp-button.mejs-loop-button button', function(e) {
				e.preventDefault();
				var loop = $(this);
				player = mejs.players[loop.attr('aria-controls')];
				player.options.loop = !player.options.loop;
				if (player.options.loop) {
					loop.parent().removeClass('mejs-loop-off').addClass('mejs-loop-on');
				} else {
					loop.parent().removeClass('mejs-loop-on').addClass('mejs-loop-off');
				}
			});
			
			$(document).on('click', '.wp-playlist-prev, .wp-playlist-next', function(e) {
				player = mejs.players[$(this).attr('data-mejs-id')];
				tracks = player.container.closest('.wp-playlist').find('.wp-playlist-tracks');
				if( player.options.loop ) {
					next = tracks.children('.wp-playlist-playing');
					setTimeout(function() {
						next.click();
					}, 10);
				} else if( player.options.shuffle ) {
					n = Math.floor( Math.random() * tracks.children().length );
					next = tracks.children('.wp-playlist-item').not('.wp-playlist-playing').eq(n);
					setTimeout(function() {
						next.click();
					}, 10);
				}
			});
			
		})(jQuery);
		</script>
		<?php
	}

}

TS_WMP::getInstance();