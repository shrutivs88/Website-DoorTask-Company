<?php
/**
 * Plugin Name: Wordpress Lucky Wheel
 * Description: Collect customers emails by let them play interesting Lucky wheel game to get interesting awards
 * Version: 1.0.3.3
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: wp-lucky-wheel
 * Domain Path: /languages
 * Copyright 2018 VillaTheme.com. All rights reserved.
 * Tested up to: 5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define( 'VI_WP_LUCKY_WHEEL_VERSION', '1.0.3.3' );
define( 'WP_LUCKY_WHEEL_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "wp-lucky-wheel" . DIRECTORY_SEPARATOR );
define( 'WP_LUCKY_WHEEL_INCLUDES', WP_LUCKY_WHEEL_DIR . "includes" . DIRECTORY_SEPARATOR );
if ( is_plugin_active( 'wordpress-lucky-wheel/wordpress-lucky-wheel.php' ) ) {
	return;
}
require_once WP_LUCKY_WHEEL_INCLUDES . "includes.php";

if ( ! class_exists( 'WP_Lucky_Wheel' ) ) {
	class WP_Lucky_Wheel {
		protected $settings;
		protected $default;

		public function __construct() {
			$this->default = array(
				'general'    => array(
					'enable'     => "on",
					'mobile'     => "on",
					'spin_num'   => 1,
					'delay'      => 24,
					'delay_unit' => 'h'
				),
				'notify'     => array(
					'position'           => 'bottom-right',
					'size'               => 40,
					'color'              => '',
					'intent'             => 'popup_icon',
					'hide_popup'         => 'off',
					'show_wheel'         => '1,5',
					'scroll_amount'      => '60',
					'show_again'         => 24,
					'show_again_unit'    => 'h',
					'show_only_front'    => 'off',
					'show_only_blog'     => 'off',
					'conditional_tags'   => '',
					'time_on_close'      => '1',
					'time_on_close_unit' => 'd',
				),
				'wheel_wrap' => array(
					'description'          => '<h2><span style="color: #ffffff;">Spin now to get amazing prize</span></h2>
<ul>
 	<li><span style="color: #ffffff;">There\'s no cheating</span></li>
 	<li><span style="color: #ffffff;">1 spin per email</span></li>
 	<li><span style="color: #ffffff;">Just enter email and spin</span></li>
</ul>',
					'bg_image'             => '',
					'bg_color'             => '#37567b',
					'spin_button'          => 'Try Your Lucky',
					'spin_button_color'    => '#000000',
					'spin_button_bg_color' => '#ffbe10',
					'pointer_position'     => 'center',
					'pointer_color'        => '#6d6d6d',
					'wheel_center_image'   => '',
					'wheel_center_color'   => '#ffffff',
					'wheel_border_color'   => '#ffffff',
					'wheel_dot_color'      => '#000000',
					'close_option'         => 'off',
					'font'                 => 'Open+Sans',
					'gdpr'                 => 'off',
					'gdpr_message'         => 'I agree with the <a href="">term and condition</a>',
				),
				'wheel'      => array(
					'spinning_time'    => 8,
					'prize_type'       => array(
						"non",
						"custom",
						"non",
						"custom",
						"non",
						"custom",
					),
					'custom_value'     => array( "", "prize_code", "", "prize_code", "", "prize_code" ),
					'custom_label'     => array(
						"Not Lucky",
						"Prize_Label",
						"Not Lucky",
						"Prize_Label",
						"Not Lucky",
						"Prize_Label",
					),
					'probability'      => array( '20', '10', '20', '15', '20', '15' ),
					'bg_color'         => array(
						'#cfd8dc',
						'#263238',
						'#cfd8dc',
						'#263238',
						'#cfd8dc',
						'#263238',
					),
					'slice_text_color' => '#fff',

				),
				'result'     => array(
					'auto_close'   => 0,
					'email'        => array(
						'from_name'             => '',
						'from_address'          => '',
						'subject'               => 'Wordpress lucky wheel award',
						'heading'               => 'Congratulations!',
						'content'               => "Dear {customer_name},\n You spinned and won the {prize_label}. The code is {prize_value}. Please use this code and contact with us to receive the prize. Thank you.\n Your Sincerely!",
						'header_image'          => '',
						'footer_text'           => '',
						'base_color'            => '#a1fbf2',
						'background_color'      => '#5b9dd9',
						'body_background_color' => '#ffffff',
						'body_text_color'       => '#0f0f0f',
					),
					'notification' => array(
						'win'  => 'Congratulations! You have won a {prize_label}. Code was sent to {customer_email}. Please check your inbox. Thank you!',
						'lost' => 'Just almost win. Maybe you\'ll be lucky next time.',
					),

				),

				'mailchimp'       => array(
					'enable'  => 'off',
					'api_key' => '',
					'lists'   => ''
				),
				'key'             => '',
				'active_campaign' => array(
					'enable' => 'off',
					'key'    => '',
					'url'    => '',
					'list'   => '',
				),
				'sendgrid'        => array(
					'enable' => 'off',
					'key'    => '',
					'list'   => '',
				),
			);
			if ( ! get_option( '_wplwl_settings' ) ) {
				add_option( '_wplwl_settings', $this->default );
			}
			$this->settings = wp_parse_args( get_option( '_wplwl_settings', array() ), $this->default );
			add_action( 'wp_ajax_wplwl_get_email', array( $this, 'get_email' ) );
			add_action( 'wp_ajax_nopriv_wplwl_get_email', array( $this, 'get_email' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'create_custom_post_type' ) );
			add_action( 'admin_init', array( $this, 'export_emails' ) );
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
			add_filter( 'manage_wplwl_email_posts_columns', array( $this, 'add_column' ), 10, 1 );
			add_action( 'manage_wplwl_email_posts_custom_column', array( $this, 'add_column_data' ), 10, 2 );
			add_filter(
				'plugin_action_links_wp-lucky-wheel/wp-lucky-wheel.php', array(
					$this,
					'settings_link'
				)
			);
			add_action( 'wp_footer', array( $this, 'draw_wheel' ) );
			add_action( 'wplwl_schedule_add_recipient_to_list', array( $this, 'add_recipient_to_list' ), 10, 2 );
		}

		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=wp-lucky-wheel" title="' . __( 'Settings', 'wp-lucky-wheel' ) . '">' . __( 'Settings', 'wp-lucky-wheel' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}

		public function create_custom_post_type() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( post_type_exists( 'wplwl_email' ) ) {
				return;
			}
			$args = array(
				'labels'              => array(
					'name'               => _x( 'Lucky Wheel Email', 'wp-lucky-wheel' ),
					'singular_name'      => _x( 'Email', 'wp-lucky-wheel' ),
					'menu_name'          => _x( 'Emails', 'Admin menu', 'wp-lucky-wheel' ),
					'name_admin_bar'     => _x( 'Emails', 'Add new on Admin bar', 'wp-lucky-wheel' ),
					'view_item'          => __( 'View Email', 'wp-lucky-wheel' ),
					'all_items'          => __( 'Email Subscribe', 'wp-lucky-wheel' ),
					'search_items'       => __( 'Search Email', 'wp-lucky-wheel' ),
					'parent_item_colon'  => __( 'Parent Email:', 'wp-lucky-wheel' ),
					'not_found'          => __( 'No Email found.', 'wp-lucky-wheel' ),
					'not_found_in_trash' => __( 'No Email found in Trash.', 'wp-lucky-wheel' )
				),
				'description'         => __( 'Wordpress lucky wheel emails.', 'wp-lucky-wheel' ),
				'public'              => false,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_in_menu'        => false,
				'hierarchical'        => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
			);
			register_post_type( 'wplwl_email', $args );
		}

		public function add_column( $columns ) {
			$columns['customer_name'] = __( 'Customer name', 'wp-lucky-wheel' );
			$columns['spins']         = __( 'Number of spins', 'wp-lucky-wheel' );
			$columns['spin_time']     = __( 'Time', 'wp-lucky-wheel' );
			$columns['label']         = __( 'Labels', 'wp-lucky-wheel' );
			$columns['value']         = __( 'Values/Code', 'wp-lucky-wheel' );

			return $columns;
		}

		public function add_column_data( $column, $post_id ) {
			$spin_meta = get_post_meta( $post_id, '_wpwlw_meta_data', false );
			$spin_num  = sizeof( $spin_meta );
			switch ( $column ) {
				case 'customer_name':
					if ( get_post( $post_id )->post_content ) {
						echo get_post( $post_id )->post_content;
					}
					break;
				case 'spins':
					echo $spin_num;
					break;
				case 'spin_time':
					if ( $spin_num > 0 ) {
						for ( $i = $spin_num - 1; $i >= 0; $i -- ) {
							echo '<p>' . ( ( isset( $spin_meta[ $i ]['time'] ) && $spin_meta[ $i ]['time'] ) ? ( date( 'Y-m-d h:i:s', $spin_meta[ $i ]['time'] ) ) : "&nbsp;" ) . '</p>';
						}
					}
					break;

				case 'label':
					if ( $spin_num > 0 ) {
						for ( $i = $spin_num - 1; $i >= 0; $i -- ) {
							echo '<p>' . ( ( isset( $spin_meta[ $i ]['label'] ) && $spin_meta[ $i ]['label'] ) ? ( $spin_meta[ $i ]['label'] ) : "&nbsp;" ) . '</p>';
						}
					}
					break;
				case 'value':
					if ( $spin_num > 0 ) {
						for ( $i = $spin_num - 1; $i >= 0; $i -- ) {
							echo '<p>' . ( ( isset( $spin_meta[ $i ]['value'] ) && $spin_meta[ $i ]['value'] ) ? ( $spin_meta[ $i ]['value'] ) : "&nbsp;" ) . '</p>';
						}
					}
					break;
			}
		}

		public function admin_enqueue() {

			if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'wp-lucky-wheel' ) {
				global $wp_scripts;
				$scripts = $wp_scripts->registered;
				foreach ( $scripts as $k => $script ) {
					preg_match( '/^\/wp-/i', $script->src, $result );
					if ( count( array_filter( $result ) ) < 1 ) {
						wp_dequeue_script( $script->handle );
					}
				}
				wp_enqueue_script( 'wp-lucky-wheel-fontselect-js', VI_WP_LUCKY_WHEEL_JS . 'jquery.fontselect.min.js', array( 'jquery' ) );
				wp_enqueue_style( 'wp-lucky-wheel-fontselect-css', VI_WP_LUCKY_WHEEL_CSS . 'fontselect-default.css' );
				wp_enqueue_script( 'wp-lucky-wheel-semantic-js-form', VI_WP_LUCKY_WHEEL_JS . 'form.js', array( 'jquery' ) );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-form', VI_WP_LUCKY_WHEEL_CSS . 'form.min.css' );
				wp_enqueue_script( 'wp-lucky-wheel-semantic-js-dropdown', VI_WP_LUCKY_WHEEL_JS . 'dropdown.min.js', array( 'jquery' ) );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-dropdown', VI_WP_LUCKY_WHEEL_CSS . 'dropdown.min.css' );
				wp_enqueue_script( 'wp-lucky-wheel-semantic-js-transition', VI_WP_LUCKY_WHEEL_JS . 'transition.min.js', array( 'jquery' ) );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-transition', VI_WP_LUCKY_WHEEL_CSS . 'transition.min.css' );
				wp_enqueue_script( 'wp-lucky-wheel-semantic-js-checkbox', VI_WP_LUCKY_WHEEL_JS . 'checkbox.js', array( 'jquery' ) );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-checkbox', VI_WP_LUCKY_WHEEL_CSS . 'checkbox.min.css' );

				wp_enqueue_script( 'wp-lucky-wheel-select2-js', VI_WP_LUCKY_WHEEL_JS . 'select2.js', array( 'jquery' ) );
				wp_enqueue_style( 'wp-lucky-wheel-select2-css', VI_WP_LUCKY_WHEEL_CSS . 'select2.min.css' );
				wp_enqueue_script( 'wp-lucky-wheel-semantic-js-tab', VI_WP_LUCKY_WHEEL_JS . 'tab.js', array( 'jquery' ) );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-tab', VI_WP_LUCKY_WHEEL_CSS . 'tab.css' );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-input', VI_WP_LUCKY_WHEEL_CSS . 'button.min.css' );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-table', VI_WP_LUCKY_WHEEL_CSS . 'table.min.css' );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-segment', VI_WP_LUCKY_WHEEL_CSS . 'segment.min.css' );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-label', VI_WP_LUCKY_WHEEL_CSS . 'label.min.css' );
				wp_enqueue_style( 'wp-lucky-wheel-semantic-css-menu', VI_WP_LUCKY_WHEEL_CSS . 'menu.min.css' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				/*Color picker*/
				wp_enqueue_script(
					'iris', admin_url( 'js/iris.min.js' ), array(
					'jquery-ui-draggable',
					'jquery-ui-slider',
					'jquery-touch-punch'
				), false, 1
				);

				wp_enqueue_script( 'media-upload' );
				if ( ! did_action( 'wp_enqueue_media' ) ) {
					wp_enqueue_media();
				}

				wp_enqueue_script( 'wp-lucky-wheel-jquery-address-javascript', VI_WP_LUCKY_WHEEL_JS . 'jquery.address-1.6.min.js', array( 'jquery' ), VI_WP_LUCKY_WHEEL_VERSION );
				wp_enqueue_script( 'wp-lucky-wheel-admin-javascript', VI_WP_LUCKY_WHEEL_JS . 'admin-javascript.js', array( 'jquery' ), VI_WP_LUCKY_WHEEL_VERSION );
				wp_enqueue_style( 'wp-lucky-wheel-admin-style', VI_WP_LUCKY_WHEEL_CSS . 'admin-style.css', array(), VI_WP_LUCKY_WHEEL_VERSION );
			}
			wp_enqueue_style( 'wp-lucky-wheel-admin-icon-style', VI_WP_LUCKY_WHEEL_CSS . 'admin-icon-style.css' );
		}

		public function get_email() {
			$email       = isset( $_POST["user_email"] ) ? sanitize_email( strtolower( $_POST["user_email"] ) ) : '';
			$name        = ( isset( $_POST["user_name"] ) && $_POST["user_name"] ) ? sanitize_text_field( $_POST["user_name"] ) : 'Sir/Madam';
			$allow       = 'no';
			$email_delay = $this->settings['general']['delay'];
			switch ( $this->settings['general']['delay_unit'] ) {
				case 'm':
					$email_delay *= 60;
					break;
				case 'h':
					$email_delay *= 60 * 60;
					break;
				case 'd':
					$email_delay *= 60 * 60 * 24;
					break;
				default:
			}
			$stop = - 1;
			if ( $this->settings['result']['notification']['lost'] ) {
				$result_notification = $this->settings['result']['notification']['lost'];
			} else {
				$result_notification = esc_html__( 'OOPS! You are not lucky today. Sorry.', 'wp-lucky-wheel' );
			}
			$now   = time();
			$wheel = $this->settings['wheel'];
			$weigh = $wheel['probability'];
			if ( $this->settings['general']['enable'] != 'on' ) {
				$allow = 'Wrong email.';
				$data  = array( 'allow_spin' => $allow );
				wp_send_json( $data );
				die;
			}

			$trash_email = new WP_Query(
				array(
					'post_type'      => 'wplwl_email',
					'posts_per_page' => - 1,
					'title'          => $email,
					'post_status'    => array( // (string | array) - use post status. Retrieves posts by Post Status, default value i'publish'.
						'trash', // - post is in trashbin (available with Version 2.9).
					)
				)
			);
			if ( $trash_email->have_posts() ) {
				$allow = esc_html__( 'Sorry, this email is marked as spam now. Please enter another email to continue.', 'wp-lucky-wheel' );
				wp_reset_postdata();
				$data = array( 'allow_spin' => $allow );
				wp_send_json( $data );
				die;
			}
			$wplwl_emails_args = array(
				'post_type'      => 'wplwl_email',
				'posts_per_page' => - 1,
				'title'          => $email,
				'post_status'    => array( // (string | array) - use post status. Retrieves posts by Post Status, default value i'publish'.
					'publish', // - a published post or page.
					'pending', // - post is pending review.
					'draft',  // - a post in draft status.
					'auto-draft', // - a newly created post, with no content.
					'future', // - a post to publish in the future.
					'private', // - not visible to users who are not logged in.
					'inherit', // - a revision. see get_children.
					'trash', // - post is in trashbin (available with Version 2.9).
				)
			);
			$the_query         = new WP_Query( $wplwl_emails_args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$email_id                  = get_the_ID();
					$post_data                 = (array) get_post();
					$post_data['post_content'] = $name;
					wp_update_post( $post_data );
					$spin_meta = get_post_meta( $email_id, '_wpwlw_meta_data', false );
					$spin_num  = sizeof( $spin_meta );
					if ( $spin_num >= $this->settings['general']['spin_num'] ) {
						$allow = esc_html__( 'This email has reach the maximum spins.', 'wp-lucky-wheel' );
					} elseif ( $spin_num > 0 && ( $now - absint( $spin_meta[ $spin_num - 1 ]['time'] ) ) < $email_delay ) {
						$wait      = $email_delay + absint( $spin_meta[ $spin_num - 1 ]['time'] ) - $now;
						$wait_day  = floor( $wait / 86400 );
						$wait_hour = floor( ( $wait - $wait_day * 86400 ) / 3600 );
						$wait_min  = floor( ( $wait - $wait_day * 86400 - $wait_hour * 3600 ) / 60 );
						$wait_sec  = $wait - $wait_day * 86400 - $wait_hour * 3600 - $wait_min * 60;

						$wait_return = $wait_sec . esc_html__( ' seconds', 'wp-lucky-wheel' );
						if ( $wait_day ) {
							$wait_return = sprintf( esc_html__( '%s days %s hours %s minutes %s seconds', 'wp-lucky-wheel' ), $wait_day, $wait_hour, $wait_min, $wait_sec );
						} elseif ( $wait_hour ) {
							$wait_return = sprintf( esc_html__( '%s hours %s minutes %s seconds', 'wp-lucky-wheel' ), $wait_hour, $wait_min, $wait_sec );
						} elseif ( $wait_min ) {
							$wait_return = sprintf( esc_html__( '%s minutes %s seconds', 'wp-lucky-wheel' ), $wait_min, $wait_sec );
						}
						$allow = esc_html__( 'You have to wait ', 'wp-lucky-wheel' ) . ( $wait_return ) . esc_html__( ' to be able to spin again.', 'wp-lucky-wheel' );
					} else {
						$allow = 'yes';
						for ( $i = 1; $i < sizeof( $weigh ); $i ++ ) {
							$weigh[ $i ] += $weigh[ $i - 1 ];
						}
						for ( $i = 0; $i < sizeof( $weigh ); $i ++ ) {
							if ( $wheel['probability'] == 0 ) {
								$weigh[ $i ] = 0;
							}
						}
						$random = rand( 1, 100 );
						$stop   = 0;
						foreach ( $weigh as $v ) {
							if ( $random <= $v ) {
								break;
							}
							$stop ++;
						}
						$wheel_label = $wheel['custom_label'][ $stop ];
						$code        = $wheel['custom_value'][ $stop ];
						add_post_meta(
							$email_id, '_wpwlw_meta_data', array(
								'time'  => $now,
								'label' => $wheel_label,
								'value' => $code
							)
						);
						if ( $wheel['prize_type'][ $stop ] != 'non' ) {
							if ( $this->settings['result']['notification']['win'] ) {
								$result_notification = $this->settings['result']['notification']['win'];
							} else {
								$result_notification = esc_html__( 'Congrats! You have won a {prize_label}. The code was sent to the email address that you had entered to spin. Thank You!', 'wp-lucky-wheel' );
							}

							$this->send_email( $email, $name, $code, $wheel_label );
							$result_notification = str_replace( '{prize_value}', '<strong>' . $code . '</strong>', $result_notification );
							$result_notification = str_replace( '{prize_label}', '<strong>' . $wheel_label . '</strong>', $result_notification );
							$result_notification = str_replace( '{customer_name}', '<strong>' . ( isset( $_POST['user_name'] ) ? $_POST['user_name'] : '' ) . '</strong>', $result_notification );
							$result_notification = str_replace( '{customer_email}', '<strong>' . $email . '</strong>', $result_notification );
						}
					}
				}
				wp_reset_postdata();
			} else {
				$allow = 'yes';
				//save email
				$email_id = wp_insert_post(
					array(
						'post_title'   => $email,
						'post_name'    => $email,
						'post_content' => $name,
						'post_author'  => 1,
						'post_status'  => 'publish',
						'post_type'    => 'wplwl_email',
					)
				);
				//get stop position
				for ( $i = 1; $i < sizeof( $weigh ); $i ++ ) {
					$weigh[ $i ] += $weigh[ $i - 1 ];
				}
				for ( $i = 0; $i < sizeof( $weigh ); $i ++ ) {
					if ( $wheel['probability'] == 0 ) {
						$weigh[ $i ] = 0;
					}
				}
				$random = rand( 1, 100 );
				$stop   = 0;
				foreach ( $weigh as $v ) {
					if ( $random <= $v ) {
						break;
					}
					$stop ++;
				}
				$wheel_label = $wheel['custom_label'][ $stop ];
				$code        = $wheel['custom_value'][ $stop ];
				add_post_meta(
					$email_id, '_wpwlw_meta_data', array(
						'time'  => $now,
						'label' => $wheel_label,
						'value' => $code
					)
				);
				if ( $wheel['prize_type'][ $stop ] != 'non' ) {
					if ( $this->settings['result']['notification']['win'] ) {
						$result_notification = $this->settings['result']['notification']['win'];
					} else {
						$result_notification = esc_html__( 'Congrats! You have won a {prize_label}. The code was sent to the email address that you had entered to spin. Thank You!', 'wp-lucky-wheel' );
					}

					$this->send_email( $email, $name, $code, $wheel_label );
					$result_notification = str_replace( '{prize_value}', '<strong>' . $code . '</strong>', $result_notification );
					$result_notification = str_replace( '{prize_label}', '<strong>' . $wheel_label . '</strong>', $result_notification );
					$result_notification = str_replace( '{customer_name}', '<strong>' . ( isset( $_POST['user_name'] ) ? $_POST['user_name'] : '' ) . '</strong>', $result_notification );
					$result_notification = str_replace( '{customer_email}', '<strong>' . $email . '</strong>', $result_notification );
				}

			}

			$data = array(
				'allow_spin'          => $allow,
				'stop_position'       => $stop,
				'result_notification' => do_shortcode( $result_notification )
			);
			wp_send_json( $data );
			die;
		}


		public function frontend_enqueue() {
			if ( ! $this->settings || $this->settings['general']['enable'] != 'on' ) {
				return;
			}
			if ( isset( $this->settings['notify']['show_only_front'] ) && $this->settings['notify']['show_only_front'] == 'on' && ! is_front_page() ) {
				return;
			}
			if ( isset( $this->settings['notify']['show_only_blog'] ) && $this->settings['notify']['show_only_blog'] == 'on' && ! is_home() ) {
				return;
			}
			if ( isset( $this->settings['wheel']['custom_label'] ) && is_array( $this->settings['wheel']['custom_label'] ) ) {
				$labels = $this->settings['wheel']['custom_label'];
				foreach ( $labels as $label ) {
					if ( empty( $label ) ) {
						return;
					}
				}
			}
			if ( is_array( $this->settings['wheel']['custom_value'] ) && sizeof( $this->settings['wheel']['custom_value'] ) > 6 ) {
				return;
			}
			$logic_value = isset( $this->settings['notify']['conditional_tags'] ) ? $this->settings['notify']['conditional_tags'] : '';
			if ( $logic_value ) {
				if ( stristr( $logic_value, "return" ) === false ) {
					$logic_value = "return (" . $logic_value . ");";
				}
				if ( ! eval( $logic_value ) ) {
					return;
				}
			}
			if ( isset( $_COOKIE['wplwl_cookie'] ) ) {
				return;
			}
			$detect = new VillaTheme_Mobile_Detect();
			if ( $detect->isMobile() && $this->settings['general']['mobile'] != 'on' ) {
				return;
			}
			if ( $detect->isMobile() && ! $detect->isTablet() ) {
				wp_enqueue_script( 'wp-lucky-wheel-frontend-javascript', VI_WP_LUCKY_WHEEL_JS . 'wp-lucky-wheel-mobile.js', array( 'jquery' ), VI_WP_LUCKY_WHEEL_VERSION );
			} else {
				wp_enqueue_script( 'wp-lucky-wheel-frontend-javascript', VI_WP_LUCKY_WHEEL_JS . 'wp-lucky-wheel.js', array( 'jquery' ), VI_WP_LUCKY_WHEEL_VERSION );
			}
			$font = '';
			if ( isset ( $this->settings['wheel_wrap']['font'] ) && ! empty( $this->settings['wheel_wrap']['font'] ) ) {
				$font = $this->settings['wheel_wrap']['font'];
				wp_enqueue_style( 'wp-lucky-wheel-google-font-' . strtolower( str_replace( '+', '-', $font ) ), '//fonts.googleapis.com/css?family=' . $font . ':300,400,700' );
				$font = str_replace( '+', ' ', $font );
			}
			wp_enqueue_style( 'wp-lucky-wheel-frontend-style', VI_WP_LUCKY_WHEEL_CSS . 'wp-lucky-wheel.css', array(), VI_WP_LUCKY_WHEEL_VERSION );
			wp_enqueue_script( 'wp-lucky-wheel-frontend-javascript', VI_WP_LUCKY_WHEEL_JS . 'wp-lucky-wheel.js', array( 'jquery' ), VI_WP_LUCKY_WHEEL_VERSION );
			$css = '.wplwl_lucky_wheel_content {';
			if ( $this->settings['wheel_wrap']['bg_image'] ) {
				$css .= 'background-image:url("' . wp_get_attachment_url( $this->settings['wheel_wrap']['bg_image'] ) . '");background-repeat: no-repeat;background-size:cover;background-position:center;';
			}
			if ( $this->settings['wheel_wrap']['bg_color'] ) {
				$css .= 'background-color:' . $this->settings['wheel_wrap']['bg_color'] . ';';
			}
			$css .= '}';
			$css .= '.wplwl_wheel_icon{';
			switch ( $this->settings['notify']['position'] ) {
				case 'top-left':
					$css .= 'top:15px;left:0;margin-left: -100%;';
					break;
				case 'top-right':
					$css .= 'top:15px;right:0;margin-right: -100%;';
					break;
				case 'bottom-left':
					$css .= 'bottom:5px;left:5px;margin-left: -100%;';
					break;
				case 'bottom-right':
					$css .= 'bottom:5px;right:5px;margin-right: -100%;';
					break;

				case 'middle-left':
					$css .= 'bottom:45%;left:0;margin-left: -100%;';
					break;
				case 'middle-right':
					$css .= 'bottom:45%;right:0;margin-right: -100%;';
					break;
			}
			$css .= '}';

			if ( $this->settings['wheel_wrap']['pointer_color'] ) {
				$css .= '.wplwl_pointer:before{color:' . $this->settings['wheel_wrap']['pointer_color'] . ';}';
			}
			//wheel wrap design
			$css .= '.wplwl_wheel_content_right .wplwl_user_lucky .wplwl_spin_button{';
			if ( $this->settings['wheel_wrap']['spin_button_color'] ) {
				$css .= 'color:' . $this->settings['wheel_wrap']['spin_button_color'] . ';';
			}

			if ( $this->settings['wheel_wrap']['spin_button_bg_color'] ) {
				$css .= 'background-color:' . $this->settings['wheel_wrap']['spin_button_bg_color'] . ';';
			}
			$css .= '}';
			if ( $font ) {
				$css .= '.wplwl_lucky_wheel_content .wplwl-wheel-content-wrapper .wplwl_wheel_content_right,.wplwl_lucky_wheel_content .wplwl-wheel-content-wrapper .wplwl_wheel_content_right input,.wplwl_lucky_wheel_content .wplwl-wheel-content-wrapper .wplwl_wheel_content_right span,.wplwl_lucky_wheel_content .wplwl-wheel-content-wrapper .wplwl_wheel_content_right a,.wplwl_lucky_wheel_content .wplwl-wheel-content-wrapper .wplwl_wheel_content_right .wplwl-frontend-result{font-family:' . $font . '!important;}';
			}
			wp_add_inline_style( 'wp-lucky-wheel-frontend-style', $css );
			$time_if_close = isset( $this->settings['notify']['time_on_close'] ) ? (int) $this->settings['notify']['time_on_close'] : 60;
			if ( isset( $this->settings['notify']['time_on_close_unit'] ) ) {
				switch ( $this->settings['notify']['time_on_close_unit'] ) {
					case 'm':
						$time_if_close *= 60;
						break;
					case 'h':
						$time_if_close *= 3600;
						break;
					case 'd':
						$time_if_close *= 86400;
						break;
					default:
				}
			}

			wp_localize_script(
				'wp-lucky-wheel-frontend-javascript', '_wplwl_get_email_params', array(
					'ajaxurl'            => admin_url( 'admin-ajax.php' ),
					'pointer_position'   => 'center',
					'wheel_dot_color'    => '#000000',
					'wheel_border_color' => '#ffffff',
					'wheel_center_color' => $this->settings['wheel_wrap']['wheel_center_color'],
					'gdpr'               => isset( $this->settings['wheel_wrap']['gdpr'] ) ? $this->settings['wheel_wrap']['gdpr'] : 'off',

					'position'         => $this->settings['notify']['position'],
					'show_again'       => $this->settings['notify']['show_again'],
					'show_again_unit'  => $this->settings['notify']['show_again_unit'],
					'intent'           => $this->settings['notify']['intent'],
					'hide_popup'       => $this->settings['notify']['hide_popup'],
					'show_wheel'       => wplwl_get_explode( ',', $this->settings['notify']['show_wheel'] ),
					'slice_text_color' => ( isset( $this->settings['wheel']['slice_text_color'] ) && $this->settings['wheel']['slice_text_color'] ) ? $this->settings['wheel']['slice_text_color'] : '#fff',
					'bg_color'         => $this->settings['wheel']['bg_color'],
					'spinning_time'    => $this->settings['wheel']['spinning_time'],
					'custom_label'     => $this->settings['wheel']['custom_label'],
					'prize_type'       => $this->settings['wheel']['prize_type'],

					'auto_close'    => $this->settings['result']['auto_close'],
					'time_if_close' => $time_if_close,
				)
			);
		}


		function load_plugin_textdomain() {
			$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			$locale = apply_filters( 'plugin_locale', $locale, 'wp-lucky-wheel' );
			load_textdomain( 'wp-lucky-wheel', WP_PLUGIN_DIR . "/wp-lucky-wheel/languages/wp-lucky-wheel-$locale.mo" );
			load_plugin_textdomain( 'wp-lucky-wheel', false, basename( dirname( __FILE__ ) ) . "/languages" );
		}


		function add_menu() {
			add_menu_page(
				esc_html__( 'Wordpress Lucky Wheel', 'wp-lucky-wheel' ),
				esc_html__( 'WP Lucky Wheel', 'wp-lucky-wheel' ),
				'manage_options', 'wp-lucky-wheel',
				array(
					$this,
					'settings_page'
				), 'dashicons-wheel', 2
			);
			add_submenu_page( 'wp-lucky-wheel', esc_html__( 'Emails', 'wp-lucky-wheel' ), esc_html__( 'Emails', 'wp-lucky-wheel' ), 'manage_options', 'edit.php?post_type=wplwl_email' );
			add_submenu_page(
				'wp-lucky-wheel', esc_html__( 'Report', 'wp-lucky-wheel' ), esc_html__( 'Report', 'wp-lucky-wheel' ), 'manage_options', 'wplwl-report', array(
					$this,
					'report_callback'
				)
			);
			add_submenu_page(
				'wp-lucky-wheel', esc_html__( 'System Status', 'wp-lucky-wheel' ), esc_html__( 'System Status', 'wp-lucky-wheel' ), 'manage_options', 'wplwl-system-status', array(
					$this,
					'system_status'
				)
			);
		}

		public function export_emails() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( isset( $_POST['submit'] ) && isset( $_POST['wplwl_export_nonce_field'] ) && wp_verify_nonce( $_POST['wplwl_export_nonce_field'], 'wplwl_export_nonce_field_action' ) ) {
				$start    = isset( $_POST['wplwl_export_start'] ) ? sanitize_text_field( $_POST['wplwl_export_start'] ) : '';
				$end      = isset( $_POST['wplwl_export_end'] ) ? sanitize_text_field( $_POST['wplwl_export_end'] ) : '';
				$filename = "wp_lucky_wheel_email_";
				if ( ! $start && ! $end ) {
					$args1    = array(
						'post_type'      => 'wplwl_email',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
					);
					$filename .= date( 'Y-m-d_h-i-s', time() ) . ".csv";
				} elseif ( ! $start ) {
					$args1    = array(
						'post_type'      => 'wplwl_email',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'date_query'     => array(
							array(
								'before'    => $end,
								'inclusive' => true

							)
						),
					);
					$filename .= 'before_' . $end . ".csv";
				} elseif ( ! $end ) {
					$args1    = array(
						'post_type'      => 'wplwl_email',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'date_query'     => array(
							array(
								'after'     => $start,
								'inclusive' => true
							)
						),

					);
					$filename .= 'from' . $start . 'to' . date( 'Y-m-d' ) . ".csv";
				} else {
					if ( strtotime( $start ) > strtotime( $end ) ) {
						wp_die( 'Incorrect input date' );
					}
					$args1    = array(
						'post_type'      => 'wplwl_email',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
						'date_query'     => array(
							array(
								'before'    => $end,
								'after'     => $start,
								'inclusive' => true

							)
						),
					);
					$filename .= 'from' . $start . 'to' . $end . ".csv";
				}
				$the_query        = new WP_Query( $args1 );
				$csv_source_array = array();
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$csv_source_array[] = get_the_title();
					}
					wp_reset_postdata();
				}
				if ( count( $csv_source_array ) ) {
					$data_rows    = array();
					$header_row   = array();
					$header_row[] = 'index';
					$header_row[] = 'email';
					$i            = 1;
					foreach ( $csv_source_array as $result ) {
						$row         = array();
						$row[]       = $i;
						$row[]       = $result;
						$data_rows[] = $row;
						$i ++;
					}
					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Content-type: text/csv' );
					header( 'Content-Description: File Transfer' );
					header( 'Content-Disposition: attachment; filename=' . $filename );
					header( 'Expires: 0' );
					header( 'Pragma: public' );
					$fh = fopen( 'php://output', 'w' );
					fprintf( $fh, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );
					fputcsv( $fh, $header_row );
					foreach ( $data_rows as $data_row ) {
						fputcsv( $fh, $data_row );
					}
					fclose( $fh );
					die;
				}
			}
		}

		public function report_callback() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$total_spin = $email_subscribe = $prizes = 0;
			$args       = array(
				'post_type'      => 'wplwl_email',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
			);
			$the_query  = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				$email_subscribe = $the_query->post_count;
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$id         = get_the_ID();
					$spin_meta  = get_post_meta( $id, '_wpwlw_meta_data', false );
					$total_spin += sizeof( $spin_meta );
					if ( sizeof( $spin_meta ) > 0 ) {
						foreach ( $spin_meta as $item ) {
							if ( ! empty( $item['value'] ) ) {
								$prizes ++;
							}
						}
					}
				}
				wp_reset_postdata();
			}
			?>
            <div class="wrap">
                <form action="" method="post">
					<?php wp_nonce_field( 'wplwl_export_nonce_field_action', 'wplwl_export_nonce_field' ); ?>
                    <h2><?php esc_html_e( 'Lucky Wheel Report', 'wp-lucky-wheel' ) ?></h2>

                    <table cellspacing="0" id="status" class="widefat">
                        <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Total Spins', 'wp-lucky-wheel' ) ?></th>
                            <th><?php esc_html_e( 'Emails Subcribed', 'wp-lucky-wheel' ) ?></th>
                            <th><?php esc_html_e( 'Won prize', 'wp-lucky-wheel' ) ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $total_spin; ?></td>
                            <td><?php echo $email_subscribe; ?></td>
                            <td><?php echo $prizes; ?></td>
                        </tr>
                        </tbody>

                    </table>
                    <label for="wplwl_export_start"><?php esc_html_e( 'From', 'wp-lucky-wheel' ); ?></label><input
                            type="date" name="wplwl_export_start" id="wplwl_export_start" class="wplwl_export_date">
                    <label for="wplwl_export_end"><?php esc_html_e( 'To', 'wp-lucky-wheel' ); ?></label><input
                            type="date" name="wplwl_export_end" id="wplwl_export_end" class="wplwl_export_date">

                    <input id="submit"
                           type="submit"
                           class="button-primary"
                           name="submit"
                           value="<?php esc_html_e( 'Export Emails', 'wp-lucky-wheel' ); ?>"/>
                </form>
            </div>
			<?php
		}

		function system_status() {
			?>
            <div class="wrap">
                <h2><?php esc_html_e( 'System Status', 'wp-lucky-wheel' ) ?></h2>
                <table cellspacing="0" id="status" class="widefat">
                    <tbody>
                    <tr>
                        <td data-export-label="file_get_contents"><?php esc_html_e( 'file_get_contents', 'wp-lucky-wheel' ) ?></td>
                        <td>
							<?php
							if ( function_exists( 'file_get_contents' ) ) {
								echo '<span class="wplwl-status-ok">&#10004;</span> ';
							} else {
								echo '<span class="wplwl-status-error">&#10005; </span>';
							}
							?>
                        </td>
                    </tr>
                    <tr>
                        <td data-export-label="<?php esc_html_e( 'Allow URL Open', 'wp-lucky-wheel' ) ?>"><?php esc_html_e( 'Allow URL Open', 'wp-lucky-wheel' ) ?></td>
                        <td>
							<?php
							if ( ini_get( 'allow_url_fopen' ) == 'On' ) {
								echo '<span class="wplwl-status-ok">&#10004;</span> ';
							} else {
								echo '<span class="wplwl-status-error">&#10005;</span>';
							}
							?>
                    </tr>
                    </tbody>
                </table>
            </div>
			<?php
		}

		public static function auto_color() {
			$palette     = '{
  "red": {
    "100": "#ffcdd2",
    "900": "#b71c1c",
    "300": "#e57373",
    "600": "#e53935"
  },
  "purple": {
    "100": "#e1bee7",
    "900": "#4a148c",
    "300": "#ba68c8",
    "600": "#8e24aa"
  },
  "deeppurple": {
    "100": "#d1c4e9",
    "900": "#311b92",
     "300": "#9575cd",
    "600": "#5e35b1"
  },
  "indigo": {
    "100": "#c5cae9",
    "900": "#1a237e",
    "300": "#7986cb",
    "600": "#3949ab"
  },
  "blue": {
    "100": "#bbdefb",
     "300": "#64b5f6",
    "600": "#1e88e5",
    "900": "#0d47a1"
  },
  "teal": {
    "100": "#b2dfdb",
    "900": "#004d40",
    "300": "#4db6ac",
    "600": "#00897b"
  },
  "green": {
    "100": "#c8e6c9",
    "900": "#1b5e20",
    "300": "#81c784",
    "600": "#43a047"
  },
  "lime": {
    "100": "#f0f4c3",
    "900": "#827717",
     "300": "#dce775",
    "600": "#c0ca33"
  },
  "yellow": {
    "100": "#fff9c4",
    "900": "#f57f17",
    "300": "#fff176",
    "600": "#fdd835"
  },
  "orange": {
    "100": "#ffe0b2",
    "900": "#e65100",
    "300": "#ffb74d",
    "600": "#fb8c00"
  },
  "brown": {
    "100": "#d7ccc8",
    "900": "#3e2723",
     "300": "#a1887f",
    "600": "#6d4c41"
  },
  "bluegrey": {
    "100": "#cfd8dc",
    "900": "#263238",
    "300": "#90a4ae",
    "600": "#546e7a"
  }
}';
			$palette     = json_decode( $palette );
			$color_array = array();
			foreach ( $palette as $colors ) {
				$color_row = array();
				foreach ( $colors as $color ) {
					$color_row[] = $color;
				}
				$color_array[] = $color_row;
			}
			$color_array[] = array(
				'#e6194b',
				'#3cb44b',
				'#ffe119',
				'#0082c8',
				'#f58231',
				'#911eb4',
				'#46f0f0',
				'#f032e6',
				'#d2f53c',
				'#fabebe',
				'#008080',
				'#e6beff',
				'#aa6e28',
				'#fffac8',
				'#800000',
				'#aaffc3',
				'#808000',
				'#ffd8b1',
				'#000080',
				'#808080',
				'#FFFFFF',
				'#000000'
			);
			echo '<div class="color_palette" style="display: none;">';
			foreach ( $color_array as $colors ) {
				echo '<div>';
				$i = 0;
				foreach ( $colors as $color ) {
					echo '<div class="wplwl_color_palette" data-color_code="' . $color . '" style="width: 20px;height: 20px;float:left;border:1px solid #ffffff;background-color: ' . $color . ';';
					if ( $i == ( sizeof( $colors ) - 1 ) ) {
						echo 'display:block;';
					} else {
						echo 'display:none;';
					}
					echo '"></div>';
					$i ++;
				}
				echo '</div>';
			}

			echo '</div>';
			echo '<div class="auto_color_ok_cancel"><div class="vi-ui buttons"><span class="auto_color_ok positive vi-ui button">' . esc_html__( 'OK', 'wp-lucky-wheel' ) . '</span>';
			echo '<div class="or"></div><span class="auto_color_cancel vi-ui button">' . esc_html__( 'Cancel', 'wp-lucky-wheel' ) . '</span></div></div>';
		}

		public function settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$this->save_settings();
			$this->settings = get_option( '_wplwl_settings', array() );

			?>
            <div class="wrap">
                <h2><?php esc_html_e( 'Wordpress Lucky Wheel Settings', 'wp-lucky-wheel' ); ?></h2>
                <form action="" method="POST" class="vi-ui form">
					<?php wp_nonce_field( 'wplwl_settings_page_save', 'wplwl_nonce_field' ); ?>
                    <div class="vi-ui top attached tabular menu">
                        <div class="item active"
                             data-tab="general"><?php esc_html_e( 'General', 'wp-lucky-wheel' ); ?></div>
                        <div class="item"
                             data-tab="popup"><?php esc_html_e( 'Pop-up', 'wp-lucky-wheel' ); ?></div>
                        <div class="item"
                             data-tab="wheel-wrap"><?php esc_html_e( 'Wheel Design', 'wp-lucky-wheel' ); ?></div>
                        <div class="item"
                             data-tab="wheel"><?php esc_html_e( 'Wheel Settings', 'wp-lucky-wheel' ); ?></div>
                        <div class="item"
                             data-tab="email"><?php esc_html_e( 'Email Settings', 'wp-lucky-wheel' ); ?></div>
                        <div class="item"
                             data-tab="result"><?php esc_html_e( 'Frontend Result', 'wp-lucky-wheel' ); ?></div>
                        <div class="item"
                             data-tab="email_api"><?php esc_html_e( 'Email API', 'wp-lucky-wheel' ); ?></div>
                    </div>
                    <div class="vi-ui bottom attached active tab segment" data-tab="general">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="wplwl_enable"><?php esc_html_e( 'Enable', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="wplwl_enable"
                                               id="wplwl_enable" <?php if ( 'on' === $this->settings['general']['enable'] ) {
											echo 'checked="checked"';
										} ?>>
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wplwl_enable_mobile"><?php esc_html_e( 'Enable mobile', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="wplwl_enable_mobile"
                                               id="wplwl_enable_mobile" <?php if ( 'on' === $this->settings['general']['mobile'] ) {
											echo 'checked="checked"';
										} ?>>
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wplwl_spin_num"><?php esc_html_e( 'Times spinning per email', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td colspan="2">
                                    <input type="number" id="wplwl_spin_num" name="wplwl_spin_num" min="1"
                                           value="<?php echo $this->settings['general']['spin_num']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wplwl_delay"><?php esc_html_e( 'Delay between each spin of an email', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" id="wplwl_delay" name="wplwl_delay"
                                           min="0" value="<?php echo $this->settings['general']['delay']; ?>">
                                </td>
                                <td>
                                    <select name="wplwl_delay_unit">
                                        <option value="s" <?php if ( $this->settings['general']['delay_unit'] == 's' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Seconds', 'wp-lucky-wheel' ); ?></option>
                                        <option value="m" <?php if ( $this->settings['general']['delay_unit'] == 'm' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Minutes', 'wp-lucky-wheel' ); ?></option>
                                        <option value="h" <?php if ( $this->settings['general']['delay_unit'] == 'h' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Hours', 'wp-lucky-wheel' ); ?></option>
                                        <option value="d" <?php if ( $this->settings['general']['delay_unit'] == 'd' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Days', 'wp-lucky-wheel' ); ?></option>
                                    </select>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="popup">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="notify_position"><?php esc_html_e( 'Position', 'wp-lucky-wheel' ); ?>

                                    </label>
                                </th>
                                <td colspan="2">
                                    <select name="notify_position" id="notify_position" class="vi-ui fluid dropdown">
                                        <option value="top-left" <?php if ( $this->settings['notify']['position'] == 'top-left' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Top Left', 'wp-lucky-wheel' ); ?></option>
                                        <option value="top-right" <?php if ( $this->settings['notify']['position'] == 'top-right' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Top Right', 'wp-lucky-wheel' ); ?></option>
                                        <option value="middle-left" <?php if ( $this->settings['notify']['position'] == 'middle-left' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Middle Left', 'wp-lucky-wheel' ); ?></option>
                                        <option value="middle-right" <?php if ( $this->settings['notify']['position'] == 'middle-right' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Middle Right', 'wp-lucky-wheel' ); ?></option>
                                        <option value="bottom-left" <?php if ( $this->settings['notify']['position'] == 'bottom-left' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Bottom Left', 'wp-lucky-wheel' ); ?></option>
                                        <option value="bottom-right" <?php if ( $this->settings['notify']['position'] == 'bottom-right' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Bottom Right', 'wp-lucky-wheel' ); ?></option>
                                    </select>
                                    <p><?php esc_html_e( 'Position of the popup on screen', 'wp-lucky-wheel' ); ?></p>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="notify_intent"><?php esc_html_e( 'Select intent', 'wp-lucky-wheel' ); ?>
                                    </label>
                                </th>
                                <td colspan="2">
                                    <select name="notify_intent" class="vi-ui fluid dropdown">
                                        <option value="popup_icon" <?php if ( isset( $this->settings['notify']['intent'] ) && $this->settings['notify']['intent'] == 'popup_icon' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Popup icon', 'wp-lucky-wheel' ); ?></option>
                                        <option value="show_wheel" <?php if ( isset( $this->settings['notify']['intent'] ) && $this->settings['notify']['intent'] == 'show_wheel' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Automactically show wheel', 'wp-lucky-wheel' ); ?></option>
                                        <option value="on_scroll"
                                                disabled><?php esc_html_e( 'Show wheel after users scroll down a specific value - Premium version only', 'wp-lucky-wheel' ); ?></option>
                                        <option value="on_exit"
                                                disabled><?php esc_html_e( 'Show wheel when users move mouse over the top to close browser - Premium version only', 'wp-lucky-wheel' ); ?></option>
                                        <option value="random"
                                                disabled><?php esc_html_e( 'Random one of these above - Premium version only', 'wp-lucky-wheel' ); ?></option>
                                    </select>
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    <label for="show_wheel"><?php esc_html_e( 'Initial time', 'wp-lucky-wheel' ); ?>
                                    </label>
                                </th>
                                <td colspan="2">
                                    <input type="text" id="show_wheel" name="show_wheel"
                                           value="<?php echo isset( $this->settings['notify']['show_wheel'] ) ? $this->settings['notify']['show_wheel'] : ''; ?>"><?php esc_html_e( 'Enter min,max to set random between min and max (seconds).', 'wp-lucky-wheel' ); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="notify_hide_popup"><?php esc_html_e( 'Hide popup icon', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="notify_hide_popup"
                                               id="notify_hide_popup" <?php if ( isset( $this->settings['notify']['hide_popup'] ) && 'on' === $this->settings['notify']['hide_popup'] ) {
											echo 'checked="checked"';
										} ?>>
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="notify_time_on_close"><?php esc_html_e( 'If customers close and not spin, show popup again after', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" id="notify_time_on_close" name="notify_time_on_close"
                                           min="0"
                                           value="<?php echo isset( $this->settings['notify']['time_on_close'] ) ? $this->settings['notify']['time_on_close'] : '60'; ?>">
                                </td>
                                <td>
                                    <select name="notify_time_on_close_unit" class="vi-ui fluid dropdown">
                                        <option value="m" <?php if ( isset( $this->settings['notify']['time_on_close_unit'] ) && $this->settings['notify']['time_on_close_unit'] == 'm' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Minutes', 'wp-lucky-wheel' ); ?></option>
                                        <option value="h" <?php if ( isset( $this->settings['notify']['time_on_close_unit'] ) && $this->settings['notify']['time_on_close_unit'] == 'h' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Hours', 'wp-lucky-wheel' ); ?></option>
                                        <option value="d" <?php if ( isset( $this->settings['notify']['time_on_close_unit'] ) && $this->settings['notify']['time_on_close_unit'] == 'd' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Days', 'wp-lucky-wheel' ); ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="notify_show_again"><?php esc_html_e( 'When finishing a spin, show popup again after', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input type="number" id="notify_show_again" name="notify_show_again"
                                           min="0" value="<?php echo $this->settings['notify']['show_again']; ?>">
                                </td>
                                <td>
                                    <select name="notify_show_again_unit">
                                        <option value="s" <?php if ( $this->settings['notify']['show_again_unit'] == 's' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Seconds', 'wp-lucky-wheel' ); ?></option>
                                        <option value="m" <?php if ( $this->settings['notify']['show_again_unit'] == 'm' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Minutes', 'wp-lucky-wheel' ); ?></option>
                                        <option value="h" <?php if ( $this->settings['notify']['show_again_unit'] == 'h' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Hours', 'wp-lucky-wheel' ); ?></option>
                                        <option value="d" <?php if ( $this->settings['notify']['show_again_unit'] == 'd' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Days', 'wp-lucky-wheel' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="notify_frontpage_only"><?php esc_html_e( 'Show only on Homepage', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="notify_frontpage_only"
                                               id="notify_frontpage_only" <?php if ( isset( $this->settings['notify']['show_only_front'] ) && 'on' === $this->settings['notify']['show_only_front'] ) {
											echo 'checked="checked"';
										} ?>>
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="notify_blog_only"><?php esc_html_e( 'Show only on Blog page', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td colspan="2">
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="notify_blog_only"
                                               id="notify_blog_only" <?php if ( isset( $this->settings['notify']['show_only_blog'] ) && 'on' === $this->settings['notify']['show_only_blog'] ) {
											echo 'checked="checked"';
										} ?>>
                                        <label></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="notify_conditional_tags"><?php esc_html_e( 'Conditional tags', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td colspan="2">
                                    <input type="text" name="notify_conditional_tags"
                                           placeholder="<?php esc_html_e( 'Ex: !is_page(array(123,41,20))', 'wp-lucky-wheel' ) ?>"
                                           id="notify_conditional_tags"
                                           value="<?php if ( $this->settings['notify']['conditional_tags'] ) {
										       echo htmlentities( $this->settings['notify']['conditional_tags'] );
									       } ?>">
                                    <p class="description"><?php esc_html_e( 'Let you control on which pages WordPress Lucky Wheel icon appears using ', 'wp-lucky-wheel' ) ?>
                                        <a href="http://codex.wordpress.org/Conditional_Tags"><?php esc_html_e( 'WP\'s conditional tags', 'wp-lucky-wheel' ) ?></a>
                                    </p>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="wheel-wrap">
                        <table class="form-table">
                            <tbody>

                            <tr>
                                <th>
                                    <label for="wheel_wrap_description"><?php esc_html_e( 'Wheel description', 'wp-lucky-wheel' ); ?>
                                    </label>
                                </th>
                                <td>
									<?php $desc_option = array( 'editor_height' => 200, 'media_buttons' => true );
									wp_editor( stripslashes( $this->settings['wheel_wrap']['description'] ), 'wheel_wrap_description', $desc_option ); ?>

                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="wheel_wrap_bg_image"><?php esc_html_e( 'Background image', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td id="wplwl-bg-image">
									<?php
									if ( $this->settings['wheel_wrap']['bg_image'] ) {
										?>
                                        <div class="wplwl-image-container">
                                            <img style="border: 1px solid;" class="review-images"
                                                 src="<?php echo wp_get_attachment_thumb_url( $this->settings['wheel_wrap']['bg_image'] ); ?>"/>
                                            <input class="wheel_wrap_bg_image" name="wheel_wrap_bg_image"
                                                   type="hidden"
                                                   value="<?php echo $this->settings['wheel_wrap']['bg_image']; ?>"/>
                                            <span class="wplwl-remove-image negative vi-ui button"><?php esc_html_e( 'Remove', 'wp-lucky-wheel' ); ?></span>
                                        </div>
                                        <div id="wplwl-new-image" style="float: left;">
                                        </div>
                                        <span style="display: none;"
                                              class="positive vi-ui button wplwl-upload-custom-img"><?php esc_html_e( 'Add Image', 'wp-lucky-wheel' ); ?></span>
										<?php

									} else {
										?>
                                        <div id="wplwl-new-image" style="float: left;">
                                        </div>
                                        <span class="positive vi-ui button wplwl-upload-custom-img"><?php esc_html_e( 'Add Image', 'wp-lucky-wheel' ); ?></span>
										<?php
									}
									?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wheel_wrap_bg_color"><?php esc_html_e( 'Background color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="wheel_wrap_bg_color" id="wheel_wrap_bg_color" type="text"
                                           class="color-picker"
                                           value="<?php if ( $this->settings['wheel_wrap']['bg_color'] ) {
										       echo $this->settings['wheel_wrap']['bg_color'];
									       } ?>"
                                           style="background: <?php if ( $this->settings['wheel_wrap']['bg_color'] ) {
										       echo $this->settings['wheel_wrap']['bg_color'];
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wheel_wrap_spin_button"><?php esc_html_e( 'Button spin', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wheel_wrap_spin_button" id="wheel_wrap_spin_button"
                                           value="<?php if ( $this->settings['wheel_wrap']['spin_button'] ) {
										       echo htmlentities( $this->settings['wheel_wrap']['spin_button'] );
									       } ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wheel_wrap_spin_button_color"><?php esc_html_e( 'Button spin color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" class="color-picker" name="wheel_wrap_spin_button_color"
                                           id="wheel_wrap_spin_button_color"
                                           value="<?php if ( $this->settings['wheel_wrap']['spin_button_color'] ) {
										       echo $this->settings['wheel_wrap']['spin_button_color'];
									       } ?>"
                                           style="background-color:<?php if ( $this->settings['wheel_wrap']['spin_button_color'] ) {
										       echo $this->settings['wheel_wrap']['spin_button_color'];
									       } ?>;">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wheel_wrap_spin_button_bg_color"><?php esc_html_e( 'Button spin background color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" class="color-picker" name="wheel_wrap_spin_button_bg_color"
                                           id="wheel_wrap_spin_button_bg_color"
                                           value="<?php if ( $this->settings['wheel_wrap']['spin_button_bg_color'] ) {
										       echo $this->settings['wheel_wrap']['spin_button_bg_color'];
									       } ?>"
                                           style="background-color:<?php if ( $this->settings['wheel_wrap']['spin_button_bg_color'] ) {
										       echo $this->settings['wheel_wrap']['spin_button_bg_color'];
									       } ?>;">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="pointer_position"><?php esc_html_e( 'Pointer position', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <select name="pointer_position" id="pointer_position" class="vi-ui fluid dropdown">
                                        <option value="center" <?php if ( $this->settings['wheel_wrap']['pointer_position'] == 'center' ) {
											echo 'selected';
										} ?>><?php esc_html_e( 'Center', 'wp-lucky-wheel' ); ?></option>
                                        <option value="top"
                                                disabled><?php esc_html_e( 'Top - Premium version only', 'wp-lucky-wheel' ); ?></option>
                                        <option value="right"
                                                disabled><?php esc_html_e( 'Right - Premium version only', 'wp-lucky-wheel' ); ?></option>
                                        <option value="bottom"
                                                disabled><?php esc_html_e( 'Bottom - Premium version only', 'wp-lucky-wheel' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="pointer_color"><?php esc_html_e( 'Wheel pointer color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="pointer_color" id="pointer_color" type="text"
                                           class="color-picker"
                                           value="<?php if ( $this->settings['wheel_wrap']['pointer_color'] ) {
										       echo $this->settings['wheel_wrap']['pointer_color'];
									       } ?>"
                                           style="background-color: <?php if ( $this->settings['wheel_wrap']['pointer_color'] ) {
										       echo $this->settings['wheel_wrap']['pointer_color'];
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wplwl-center-image1"><?php esc_html_e( 'Wheel center background image', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td id="wplwl-bg-image1">
                                    <a class="vi-ui button" target="_blank"
                                       href="http://bit.ly/wordpress-lucky-wheel"><?php esc_html_e( 'Upgrade This Feature', 'wp-lucky-wheel' ) ?></a>
                                </td>
                            </tr>


                            <tr>
                                <th>
                                    <label for="wheel_center_color"><?php esc_html_e( 'Wheel center color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="wheel_center_color" id="wheel_center_color" type="text"
                                           class="color-picker"
                                           value="<?php if ( $this->settings['wheel_wrap']['wheel_center_color'] ) {
										       echo $this->settings['wheel_wrap']['wheel_center_color'];
									       } ?>"
                                           style="background-color: <?php if ( $this->settings['wheel_wrap']['wheel_center_color'] ) {
										       echo $this->settings['wheel_wrap']['wheel_center_color'];
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wheel_border_color"><?php esc_html_e( 'Wheel border color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="http://bit.ly/wordpress-lucky-wheel"><?php esc_html_e( 'Upgrade This Feature', 'wp-lucky-wheel' ) ?></a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wheel_dot_color"><?php esc_html_e( 'Wheel border dot color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="http://bit.ly/wordpress-lucky-wheel"><?php esc_html_e( 'Upgrade This Feature', 'wp-lucky-wheel' ) ?></a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="slice_text_color"><?php esc_html_e( 'Slices text color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="slice_text_color" id="slice_text_color" type="text"
                                           class="color-picker"
                                           value="<?php echo isset( $this->settings['wheel']['slice_text_color'] ) ? $this->settings['wheel']['slice_text_color'] : '#fff'; ?>"
                                           style="background-color: <?php echo isset( $this->settings['wheel']['slice_text_color'] ) ? $this->settings['wheel']['slice_text_color'] : '#fff'; ?>;"/>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="wheel_wrap_close_option"><?php esc_html_e( 'Show text option to not display wheel again', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="wheel_wrap_close_option"
                                               id="wheel_wrap_close_option" <?php if ( isset( $this->settings['wheel_wrap']['close_option'] ) && 'on' === $this->settings['wheel_wrap']['close_option'] ) {
											echo 'checked="checked"';
										} ?>>
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="wplwl-google-font-select"><?php esc_html_e( 'Select font', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>

                                    <input type="text" name="wplwl_google_font_select"
                                           id="wplwl-google-font-select"
                                           value="<?php if ( isset( $this->settings['wheel_wrap']['font'] ) ) {
										       echo $this->settings['wheel_wrap']['font'];
									       } ?>"><span class="wplwl-google-font-select-remove wplwl-cancel"
                                                       style="<?php if ( isset( $this->settings['wheel_wrap']['font'] ) && ! $this->settings['wheel_wrap']['font'] ) {
										                   echo 'display:none';
									                   } ?>"></span>

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="gdpr_policy"><?php esc_html_e( 'GDPR checkbox', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input class="gdpr_policy" type="checkbox" id="gdpr_policy"
                                               name="gdpr_policy"
                                               value="on" <?php if ( isset( $this->settings['wheel_wrap']['gdpr'] ) && 'on' == $this->settings['wheel_wrap']['gdpr'] ) {
											echo 'checked';
										} ?>>
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="gdpr_message"><?php esc_html_e( 'GDPR message', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
									<?php
									$option = array( 'editor_height' => 300, 'media_buttons' => false );
									wp_editor( isset( $this->settings['wheel_wrap']['gdpr_message'] ) ? stripslashes( $this->settings['wheel_wrap']['gdpr_message'] ) : '', 'gdpr_message', $option );
									?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="wheel">
                        <table class="form-table wheel-settings">
                            <tbody class="content">
                            <tr>
                                <th>
                                    <label for="wheel_spinning_time">
										<?php esc_html_e( 'Wheel spinning duration', 'wp-lucky-wheel' ); ?>
                                    </label>
                                </th>
                                <td colspan="4">
                                    <a class="vi-ui button" target="_blank"
                                       href="http://bit.ly/wordpress-lucky-wheel"><?php esc_html_e( 'Upgrade This Feature', 'wp-lucky-wheel' ) ?></a>
                                    <p><?php esc_html_e( 'From 3s to 15s.', 'wp-lucky-wheel' ); ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <table class="form-table wheel-settings" style="margin-top: 0;">
                            <tbody>
                            <tr class="wheel-slices" style="background-color: #000000;">
                                <td width="40"><?php esc_attr_e( 'Index', 'wp-lucky-wheel' ) ?></td>
                                <td><?php esc_attr_e( 'Prize type', 'wp-lucky-wheel' ) ?></td>
                                <td><?php esc_attr_e( 'Label', 'wp-lucky-wheel' ) ?></td>
                                <td><?php esc_attr_e( 'Value', 'wp-lucky-wheel' ) ?></td>
                                <td><?php esc_attr_e( 'Probability(%)', 'wp-lucky-wheel' ) ?></td>
                                <td><?php esc_attr_e( 'Color', 'wp-lucky-wheel' ) ?></td>
                            </tr>
                            </tbody>
                            <tbody class="ui-sortable">
							<?php
							for ( $count = 0; $count < count( $this->settings['wheel']['prize_type'] ); $count ++ ) {
								?>
                                <tr class="wheel_col">
                                    <td class="wheel_col_index" width="40"><?php echo( $count + 1 ); ?></td>
                                    <td class="wheel_col_prize_type"><select name="prize_type[]"
                                                                             class="prize_type">
                                            <option value="non" <?php selected( $this->settings['wheel'] ['prize_type'][ $count ], 'non' ); ?>><?php esc_attr_e( 'Non', 'wp-lucky-wheel' ) ?></option>
                                            <option value="custom" <?php selected( $this->settings['wheel'] ['prize_type'][ $count ], 'custom' ); ?>><?php esc_attr_e( 'Custom', 'wp-lucky-wheel' ) ?></option>
                                        </select>
                                    </td>
                                    <td class="wheel_col_prize_type_value">
                                        <input type="text" name="custom_type_label[]"
                                               class="custom_type_label"
                                               value="<?php echo( isset( $this->settings['wheel'] ['custom_label'][ $count ] ) ? $this->settings['wheel'] ['custom_label'][ $count ] : '' ) ?>"
                                               placeholder="Label"/>
                                    </td>
                                    <td class="wheel_col_prize_type_value">

                                        <input type="text" name="custom_type_value[]" class="custom_type_value"
                                               value="<?php echo isset( $this->settings['wheel'] ['custom_value'][ $count ] ) ? $this->settings['wheel'] ['custom_value'][ $count ] : ''; ?>"
                                               placeholder="Value/Code" <?php if ( isset( $this->settings['wheel'] ['prize_type'][ $count ] ) && $this->settings['wheel'] ['prize_type'][ $count ] == 'non' ) {
											echo 'readonly';
										} ?>/>

                                    </td>
                                    <td class="wheel_col_probability">
                                        <input type="number" name="probability[]"
                                               class="probability probability_<?php echo $count; ?>" min="0"
                                               max="100" placeholder="Probability"
                                               value="<?php echo absint( $this->settings['wheel']['probability'][ $count ] ) ?>"/>
                                    </td>
                                    <td class="remove_field_wrap">
                                        <input type="text" id="color_code" name="bg_color[]" class="color-picker"
                                               value=" <?php echo trim( $this->settings['wheel'] ['bg_color'][ $count ] ); ?>"
                                               style="background: <?php echo trim( $this->settings['wheel'] ['bg_color'][ $count ] ); ?>"/>
                                        <span class="remove_field negative vi-ui button"><?php esc_attr_e( 'Remove', 'wp-lucky-wheel' ); ?></span>
                                        <span class="clone_piece positive vi-ui button"><?php esc_attr_e( 'Clone', 'wp-lucky-wheel' ); ?></span>
                                    </td>
                                </tr>
								<?php
							}
							?>
                            <tbody>
                            <tr>
                                <td class="col_add_new" colspan="3">
                                    <i><?php esc_attr_e( 'Slices positions are sortable by drag and drop.', 'wp-lucky-wheel' ); ?></i>
                                </td>

                                <td class="col_add_new col_total_probability">
                                    <i><?php esc_attr_e( '*The total Probability: ', 'wp-lucky-wheel' ); ?>
                                        <strong class="total_probability" data-total_probability=""> 100 </strong> (
                                        % )</i></td>

                                <td></td>
                                <td class="col_add_new">
									<?php
									self::auto_color();
									?>
                                    <span class="auto_color positive vi-ui button"><?php esc_attr_e( 'Auto Color', 'wp-lucky-wheel' ) ?></span>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="email">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="from_name"><?php esc_html_e( '"From" name', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <input id="from_name" type="text" name="from_name"
                                           value="<?php echo isset( $this->settings['result']['email']['from_name'] ) ? htmlentities( $this->settings['result']['email']['from_name'] ) : ''; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="from_address"><?php esc_html_e( '"From" address', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <input id="from_address" type="text" name="from_address"
                                           value="<?php echo isset( $this->settings['result']['email']['from_address'] ) ? htmlentities( $this->settings['result']['email']['from_address'] ) : ''; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="subject"><?php esc_html_e( 'Email subject', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <input id="subject" type="text" name="subject"
                                           value="<?php echo htmlentities( $this->settings['result']['email']['subject'] ); ?>">
									<?php esc_html_e( 'The subject of emails sending to customers when they win.', 'wp-lucky-wheel' ) ?>

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="heading"><?php esc_html_e( 'Email heading', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <input id="heading" type="text" name="heading"
                                           value="<?php echo htmlentities( $this->settings['result']['email']['heading'] ); ?>">
									<?php esc_html_e( 'The heading of emails sending to customers when they win.', 'wp-lucky-wheel' ) ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="content"><?php esc_html_e( 'Email content', 'wp-lucky-wheel' ) ?></label>
                                    <p><?php esc_html_e( 'The content of email sending to customers to inform them the prize they win.', 'wp-lucky-wheel' ) ?></p>
                                </th>
                                <td><?php $option = array( 'editor_height' => 300, 'media_buttons' => true );
									wp_editor( stripslashes( $this->settings['result']['email']['content'] ), 'content', $option ); ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <ul>
                                        <li>{customer_name}
                                            - <?php esc_html_e( 'Customer\'s name.', 'wp-lucky-wheel' ) ?></li>
                                        <li>{prize_value}
                                            - <?php esc_html_e( 'Value of prize that will be sent to customer.', 'wp-lucky-wheel' ) ?></li>
                                        <li>{prize_label}
                                            - <?php esc_html_e( 'Label of prize that customers win', 'wp-lucky-wheel' ) ?></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="footer_text"><?php esc_html_e( 'Footer text', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="footer_text" id="footer_text" type="text"
                                           value="<?php if ( isset( $this->settings['result']['email']['footer_text'] ) ) {
										       echo $this->settings['result']['email']['footer_text'];
									       } ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="email_base_color"><?php esc_html_e( 'Base color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="email_base_color" id="email_base_color" type="text"
                                           class="color-picker"
                                           value="<?php if ( isset( $this->settings['result']['email']['base_color'] ) ) {
										       echo $this->settings['result']['email']['base_color'];
									       } ?>"
                                           style="background: <?php if ( isset( $this->settings['result']['email']['base_color'] ) ) {
										       echo $this->settings['result']['email']['base_color'];
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="email_background_color"><?php esc_html_e( 'Background color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="email_background_color" id="email_background_color" type="text"
                                           class="color-picker"
                                           value="<?php if ( isset( $this->settings['result']['email']['background_color'] ) ) {
										       echo $this->settings['result']['email']['background_color'];
									       } ?>"
                                           style="background: <?php if ( isset( $this->settings['result']['email']['background_color'] ) ) {
										       echo $this->settings['result']['email']['background_color'];
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="email_body_background_color"><?php esc_html_e( 'Body background color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="email_body_background_color" id="email_body_background_color"
                                           type="text"
                                           class="color-picker"
                                           value="<?php if ( isset( $this->settings['result']['email']['body_background_color'] ) ) {
										       echo $this->settings['result']['email']['body_background_color'];
									       } ?>"
                                           style="background: <?php if ( isset( $this->settings['result']['email']['body_background_color'] ) ) {
										       echo $this->settings['result']['email']['body_background_color'];
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="email_body_text_color"><?php esc_html_e( 'Body text color', 'wp-lucky-wheel' ); ?></label>
                                </th>
                                <td>
                                    <input name="email_body_text_color" id="email_body_text_color" type="text"
                                           class="color-picker"
                                           value="<?php if ( isset( $this->settings['result']['email']['body_text_color'] ) ) {
										       echo $this->settings['result']['email']['body_text_color'];
									       } ?>"
                                           style="background: <?php if ( isset( $this->settings['result']['email']['body_text_color'] ) ) {
										       echo $this->settings['result']['email']['body_text_color'];
									       } ?>;"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="result">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="result-auto_close"><?php esc_html_e( 'Automatically hide wheel after finishing spinning', 'wp-lucky-wheel' ); ?>
                                    </label>
                                </th>
                                <td>
                                    <div class="inline fields">
                                        <input type="number" name="result-auto_close" min="0"
                                               id="result-auto_close"
                                               value="<?php echo isset( $this->settings['result']['auto_close'] ) ? absint( $this->settings['result']['auto_close'] ) : '0'; ?>">
										<?php esc_html_e( 'seconds', 'wp-lucky-wheel' ); ?>
                                    </div>
                                    <p><?php esc_html_e( 'Left 0 to let customer close it manually', 'wp-lucky-wheel' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="result_win"><?php esc_html_e( 'Frontend Message if win', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
									<?php $win_option = array( 'editor_height' => 200, 'media_buttons' => true );
									wp_editor( stripslashes( $this->settings['result']['notification']['win'] ), 'result_win', $win_option ); ?>

                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <ul>
                                        <li>{prize_label}
                                            - <?php esc_html_e( 'Label of prize that customers win', 'wp-lucky-wheel' ) ?></li>
                                        <li>{customer_name}
                                            - <?php esc_html_e( 'Customers\'name if they enter', 'wp-lucky-wheel' ) ?></li>
                                        <li>{customer_email}
                                            - <?php esc_html_e( 'Email that customers enter to spin', 'wp-lucky-wheel' ) ?></li>
                                        <li>{prize_value}
                                            - <?php esc_html_e( 'Value of prize that will be sent to customer.', 'wp-lucky-wheel' ) ?></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="result_lost"><?php esc_html_e( 'Frontend message if lost', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
									<?php $lost_option = array( 'editor_height' => 200, 'media_buttons' => true );
									wp_editor( stripslashes( $this->settings['result']['notification']['lost'] ), 'result_lost', $lost_option ); ?>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="email_api">
                        <table class="form-table">
                            <tbody>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="mailchimp_enable"><?php esc_html_e( 'Enable Mailchimp', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="http://bit.ly/wordpress-lucky-wheel"><?php esc_html_e( 'Upgrade This Feature', 'wp-lucky-wheel' ) ?></a>

                                </td>
                            </tr>

                            <tr>
                                <td></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="wplwl_enable_active_campaign"><?php esc_html_e( 'Active Campaign', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="http://bit.ly/wordpress-lucky-wheel"><?php esc_html_e( 'Upgrade This Feature', 'wp-lucky-wheel' ) ?></a>

                                </td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wplwl_sendgrid_enable"><?php esc_html_e( 'SendGrid', 'wp-lucky-wheel' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="http://bit.ly/wordpress-lucky-wheel"><?php esc_html_e( 'Upgrade This Feature', 'wp-lucky-wheel' ) ?></a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <p><input id="submit" type="submit" class="vi-ui primary button" name="submit"
                              value="<?php esc_html_e( 'Save', 'wp-lucky-wheel' ); ?>">
                    </p>
                </form>
            </div>
			<?php
			do_action( 'villatheme_support_wp-lucky-wheel' );
		}

		public function save_settings() {
			if ( empty( $_POST['wplwl_nonce_field'] ) || ! wp_verify_nonce( $_POST['wplwl_nonce_field'], 'wplwl_settings_page_save' ) ) {
				return;
			}
			$args = array(
				'general'    => array(
					'enable'     => isset( $_POST['wplwl_enable'] ) ? sanitize_text_field( $_POST['wplwl_enable'] ) : 'off',
					'mobile'     => isset( $_POST['wplwl_enable_mobile'] ) ? sanitize_text_field( $_POST['wplwl_enable_mobile'] ) : 'off',
					'spin_num'   => isset( $_POST['wplwl_spin_num'] ) ? sanitize_text_field( $_POST['wplwl_spin_num'] ) : 0,
					'delay'      => isset( $_POST['wplwl_delay'] ) ? sanitize_text_field( $_POST['wplwl_delay'] ) : 0,
					'delay_unit' => isset( $_POST['wplwl_delay_unit'] ) ? sanitize_text_field( $_POST['wplwl_delay_unit'] ) : 's',
				),
				'notify'     => array(
					'position'           => isset( $_POST['notify_position'] ) ? sanitize_text_field( $_POST['notify_position'] ) : '',
					'size'               => isset( $_POST['notify_size'] ) ? sanitize_text_field( $_POST['notify_size'] ) : 0,
					'color'              => isset( $_POST['notify_color'] ) ? sanitize_text_field( $_POST['notify_color'] ) : '',
					'intent'             => isset( $_POST['notify_intent'] ) ? sanitize_text_field( $_POST['notify_intent'] ) : '',
					'show_again'         => isset( $_POST['notify_show_again'] ) ? sanitize_text_field( $_POST['notify_show_again'] ) : 0,
					'hide_popup'         => isset( $_POST['notify_hide_popup'] ) ? sanitize_text_field( $_POST['notify_hide_popup'] ) : 'off',
					'show_wheel'         => isset( $_POST['show_wheel'] ) ? sanitize_text_field( $_POST['show_wheel'] ) : '',
					'scroll_amount'      => isset( $this->settings['notify']['scroll_amount'] ) ? $this->settings['notify']['scroll_amount'] : '70',
					'show_again_unit'    => isset( $_POST['notify_show_again_unit'] ) ? sanitize_text_field( $_POST['notify_show_again_unit'] ) : 0,
					'show_only_front'    => isset( $_POST['notify_frontpage_only'] ) ? sanitize_text_field( $_POST['notify_frontpage_only'] ) : 'off',
					'show_only_blog'     => isset( $_POST['notify_blog_only'] ) ? sanitize_text_field( $_POST['notify_blog_only'] ) : 'off',
					'conditional_tags'   => isset( $_POST['notify_conditional_tags'] ) ? stripslashes( sanitize_text_field( $_POST['notify_conditional_tags'] ) ) : '',
					'time_on_close'      => isset( $_POST['notify_time_on_close'] ) ? stripslashes( sanitize_text_field( $_POST['notify_time_on_close'] ) ) : '',
					'time_on_close_unit' => isset( $_POST['notify_time_on_close_unit'] ) ? stripslashes( sanitize_text_field( $_POST['notify_time_on_close_unit'] ) ) : '',
				),
				'wheel_wrap' => array(
					'description'          => isset( $_POST['wheel_wrap_description'] ) ? wp_kses_post( stripslashes( $_POST['wheel_wrap_description'] ) ) : '',
					'bg_image'             => isset( $_POST['wheel_wrap_bg_image'] ) ? sanitize_text_field( $_POST['wheel_wrap_bg_image'] ) : '',
					'bg_color'             => isset( $_POST['wheel_wrap_bg_color'] ) ? sanitize_text_field( $_POST['wheel_wrap_bg_color'] ) : '',
					'spin_button'          => isset( $_POST['wheel_wrap_spin_button'] ) ? sanitize_text_field( stripslashes( $_POST['wheel_wrap_spin_button'] ) ) : 'Try Your Lucky',
					'spin_button_color'    => isset( $_POST['wheel_wrap_spin_button_color'] ) ? sanitize_text_field( $_POST['wheel_wrap_spin_button_color'] ) : '',
					'spin_button_bg_color' => isset( $_POST['wheel_wrap_spin_button_bg_color'] ) ? sanitize_text_field( $_POST['wheel_wrap_spin_button_bg_color'] ) : '',
					'pointer_position'     => 'center',
					'pointer_color'        => isset( $_POST['pointer_color'] ) ? sanitize_text_field( $_POST['pointer_color'] ) : '',
					'wheel_center_image'   => '',
					'wheel_center_color'   => isset( $_POST['wheel_center_color'] ) ? sanitize_text_field( $_POST['wheel_center_color'] ) : '',
					'wheel_border_color'   => '#ffffff',
					'wheel_dot_color'      => '#000000',
					'close_option'         => isset( $_POST['wheel_wrap_close_option'] ) ? sanitize_text_field( $_POST['wheel_wrap_close_option'] ) : '',
					'font'                 => isset( $_POST['wplwl_google_font_select'] ) ? sanitize_text_field( $_POST['wplwl_google_font_select'] ) : '',
					'gdpr'                 => isset( $_POST['gdpr_policy'] ) ? sanitize_textarea_field( $_POST['gdpr_policy'] ) : "off",
					'gdpr_message'         => isset( $_POST['gdpr_message'] ) ? wp_kses_post( stripslashes( $_POST['gdpr_message'] ) ) : "",
				),
				'wheel'      => array(
					'spinning_time'    => 8,
					'prize_type'       => isset( $_POST['prize_type'] ) ? stripslashes_deep( array_map( 'sanitize_text_field', $_POST['prize_type'] ) ) : array(),
					'custom_value'     => isset( $_POST['custom_type_value'] ) ? array_map( 'wplwl_sanitize_text_field', $_POST['custom_type_value'] ) : array(),
					'custom_label'     => isset( $_POST['custom_type_label'] ) ? array_map( 'wplwl_sanitize_text_field', $_POST['custom_type_label'] ) : array(),
					'probability'      => isset( $_POST['probability'] ) ? array_map( 'sanitize_text_field', $_POST['probability'] ) : array(),
					'bg_color'         => isset( $_POST['bg_color'] ) ? array_map( 'sanitize_text_field', $_POST['bg_color'] ) : array(),
					'slice_text_color' => isset( $_POST['slice_text_color'] ) ? wp_kses_post( stripslashes( $_POST['slice_text_color'] ) ) : "",
				),

				'result' => array(
					'auto_close'   => isset( $_POST['result-auto_close'] ) ? sanitize_text_field( $_POST['result-auto_close'] ) : 'off',
					'email'        => array(
						'from_name'             => isset( $_POST['from_name'] ) ? stripslashes( sanitize_text_field( $_POST['from_name'] ) ) : "",
						'from_address'          => isset( $_POST['from_address'] ) ? stripslashes( sanitize_text_field( $_POST['from_address'] ) ) : "",
						'subject'               => isset( $_POST['subject'] ) ? stripslashes( sanitize_text_field( $_POST['subject'] ) ) : "",
						'heading'               => isset( $_POST['heading'] ) ? stripslashes( sanitize_text_field( $_POST['heading'] ) ) : "",
						'content'               => isset( $_POST['content'] ) ? wp_kses_post( $_POST['content'] ) : "",
						'header_image'          => '',
						'footer_text'           => isset( $_POST['footer_text'] ) ? stripslashes( sanitize_text_field( $_POST['footer_text'] ) ) : "",
						'base_color'            => isset( $_POST['email_base_color'] ) ? sanitize_text_field( $_POST['email_base_color'] ) : '',
						'background_color'      => isset( $_POST['email_background_color'] ) ? sanitize_text_field( $_POST['email_background_color'] ) : '',
						'body_background_color' => isset( $_POST['email_body_background_color'] ) ? sanitize_text_field( $_POST['email_body_background_color'] ) : '',
						'body_text_color'       => isset( $_POST['email_body_text_color'] ) ? sanitize_text_field( $_POST['email_body_text_color'] ) : '',
					),
					'notification' => array(
						'win'  => isset( $_POST['result_win'] ) ? wp_kses_post( $_POST['result_win'] ) : "",
						'lost' => isset( $_POST['result_lost'] ) ? wp_kses_post( $_POST['result_lost'] ) : "",
					)
				),
			);
			if ( isset( $_POST['submit'] ) ) {
				if ( $_POST['probability'] ) {
					if ( count( $_POST['probability'] ) > 6 || count( $_POST['probability'] ) < 3 ) {
						wp_die( 'Free version only includes from 3 to 6 slices. Upgrade to Premium version to add up tp 20 slices.' );
					}
					if ( array_sum( $_POST['probability'] ) != 100 ) {
						wp_die( 'The total probability must be equal to 100%!' );
					}

				}
				if ( isset( $_POST['custom_type_label'] ) && is_array( $_POST['custom_type_label'] ) ) {
					foreach ( $_POST['custom_type_label'] as $key => $val ) {
						if ( $_POST['custom_type_label'][ $key ] === '' ) {
							wp_die( 'Label cannot be empty.' );
						}

						if ( isset( $_POST['custom_type_value'] ) && is_array( $_POST['custom_type_value'] ) ) {
							if ( $_POST['prize_type'][ $key ] == 'custom' && $_POST['custom_type_value'][ $key ] == '' ) {
								wp_die( 'Please enter value for custom type.' );
							}
						}
					}
				}

				update_option( '_wplwl_settings', wp_parse_args( $args, $this->settings ) );
				?>
                <div class="updated">
                    <p><?php esc_html_e( 'Your settings have been saved!', 'wp-lucky-wheel' ) ?></p>
                </div>
				<?php
			}
		}

		public function get_from_address() {
			return sanitize_email( $this->settings['result']['email']['from_address'] );
		}

		public function get_from_name() {
			return wp_specialchars_decode( esc_html( $this->settings['result']['email']['from_name'] ), ENT_QUOTES );
		}

		public function get_content_type() {
			return 'text/html';
		}

		public function send_email( $user_email, $customer_name, $value = '', $label = '' ) {
			$setting = get_option( '_wplwl_settings', array() );
			if ( ! $setting || $setting['general']['enable'] != 'on' ) {
				return;
			}
			if ( sanitize_email( $this->settings['result']['email']['from_address'] ) ) {
				add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
			}
			if ( $this->settings['result']['email']['from_address'] ) {
				add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
			}
			add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
			$header        = 'Content-Type: text/html; charset=utf-8;';
			$email_temp    = $setting['result']['email'];
			$content       = stripslashes( nl2br( $email_temp['content'] ) );
			$content       = str_replace( '{prize_label}', $label, $content );
			$content       = str_replace( '{customer_name}', $customer_name, $content );
			$content       = str_replace( '{prize_value}', $value, $content );
			$subject       = stripslashes( $email_temp['subject'] );
			$email_heading = isset( $email_temp['heading'] ) ? $email_temp['heading'] : '';

			$bg   = isset( $email_temp['background_color'] ) ? $email_temp['background_color'] : '';
			$body = isset( $email_temp['body_background_color'] ) ? $email_temp['body_background_color'] : '';
			$base = isset( $email_temp['base_color'] ) ? $email_temp['base_color'] : '';
			$text = isset( $email_temp['body_text_color'] ) ? $email_temp['body_text_color'] : '';
			ob_start();
			?>
            <!DOCTYPE html>
            <html <?php language_attributes(); ?>>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>"/>
                <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
            </head>
            <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0"
            marginheight=
            "0" offset="0">
            <div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr' ?>"
                 style="background-color: <?php echo esc_attr( $bg ); ?>;
                         margin: 0;
                         padding: 70px 0 70px 0;
                         -webkit-text-size-adjust: none !important;
                         width: 100%;">
                <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                    <tr>
                        <td align="center" valign="top">
                            <div id="template_header_image">
								<?php
								if ( $img = isset( $email_temp['header_image'] ) ? $email_temp['header_image'] : '' ) {
									echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
								}
								?>
                            </div>
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container"
                                   style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important;
                                           background-color: <?php echo esc_attr( $body ); ?>;
                                           border-radius: 3px !important;">
                                <tr>
                                    <td align="center" valign="top">
                                        <!-- Header -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="600"
                                               id="template_header"
                                               style="background-color: <?php echo esc_attr( $base ); ?>;
                                                       border-radius: 3px 3px 0 0 !important;
                                                       border-bottom: 0;
                                                       font-weight: bold;
                                                       line-height: 100%;
                                                       vertical-align: middle;
                                                       font-family: Helvetica, Roboto, Arial, sans-serif;">
                                            <tr>
                                                <td id="header_wrapper" style="padding: 36px 48px;
	display: block;">
                                                    <h1><?php echo $email_heading; ?></h1>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Header -->
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">
                                        <!-- Body -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="600"
                                               id="template_body">
                                            <tr>
                                                <td valign="top" id="body_content"
                                                    style="background-color: <?php echo esc_attr( $body ); ?>;">
                                                    <!-- Content -->
                                                    <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top" style="padding: 48px;">
                                                                <div id="body_content_inner" style="
                                                                        font-family: Helvetica, Roboto, Arial, sans-serif;
                                                                        font-size: 14px;
                                                                        line-height: 150%;
                                                                        text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;">
                                                                    <div class="text"
                                                                         style="color: <?php echo esc_attr( $text ); ?>;
                                                                                 font-family: Helvetica, Roboto, Arial, sans-serif;">
																		<?php
																		echo $content;
																		?>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!-- End Content -->
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Body -->
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">
                                        <!-- Footer -->
                                        <table border="0" cellpadding="10" cellspacing="0" width="600"
                                               id="template_footer">
                                            <tr>
                                                <td valign="top">
                                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td colspan="2" valign="middle" id="credit" style="border:0;
                                                                font-family: Arial;
                                                                font-size:12px;
                                                                line-height:125%;
                                                                text-align:center;
                                                                padding: 0 48px 48px 48px;">
																<?php echo wpautop( wp_kses_post( wptexturize( isset( $email_temp['footer_text'] ) ? $email_temp['footer_text'] : '' ) ) ); ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Footer -->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            </body>
            </html>

			<?php
			$content = ob_get_clean();

			wp_mail( $user_email, $subject, $content, $header );

			remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
			remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
			remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
		}

		function draw_wheel() {

			if ( $this->settings['general']['enable'] != 'on' ) {
				return;
			}
			if ( isset( $this->settings['notify']['show_only_front'] ) && $this->settings['notify']['show_only_front'] == 'on' && ! is_front_page() ) {
				return;
			}
			if ( isset( $this->settings['notify']['show_only_blog'] ) && $this->settings['notify']['show_only_blog'] == 'on' && ! is_home() ) {
				return;
			}
			if ( isset( $this->settings['wheel']['custom_label'] ) && is_array( $this->settings['wheel']['custom_label'] ) ) {
				$labels = $this->settings['wheel']['custom_label'];
				foreach ( $labels as $label ) {
					if ( empty( $label ) ) {
						return;
					}
				}
			}
			$logic_value = isset( $this->settings['notify']['conditional_tags'] ) ? $this->settings['notify']['conditional_tags'] : '';
			if ( $logic_value ) {
				if ( stristr( $logic_value, "return" ) === false ) {
					$logic_value = "return (" . $logic_value . ");";
				}
				if ( ! eval( $logic_value ) ) {
					return;
				}
			}
			if ( $this->settings['wheel']['custom_value'] && sizeof( $this->settings['wheel']['custom_value'] ) > 6 ) {
				return;
			}
			if ( isset( $_COOKIE['wplwl_cookie'] ) ) {
				return;
			}
			$detect = new VillaTheme_Mobile_Detect();

			if ( $detect->isMobile() && ! $detect->isTablet() && $this->settings['general']['mobile'] != 'on' ) {
				return;
			}
			?>

			<?php
			?>
            <div class="wplwl-overlay"></div>

			<?php
			if ( $detect->isMobile() && ! $detect->isTablet() ) {
				?>
                <div class="wplwl_lucky_wheel_content wplwl_lucky_wheel_content_mobile
                <?php
				if ( $this->settings['wheel_wrap']['pointer_position'] == 'top' ) {
					echo 'wplwl_margin_position wplwl_spin_top';
				} elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'right' ) {
					echo 'wplwl_margin_position';
				} elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'bottom' ) {
					echo 'wplwl_margin_position wplwl_spin_bottom';
				} ?>">
                    <div class="wplwl-wheel-content-wrapper">

                        <div class="wplwl_wheel_content_right">

                            <div class="wplwl_wheel_description">
								<?php echo stripslashes( $this->settings['wheel_wrap']['description'] ); ?>
                            </div>
                            <div class="wplwl_user_lucky">
                                <label>
                                    <input type="text" class="wplwl_field_input wplwl_field_name"
                                           name="wplwl_player_name"
                                           placeholder="<?php esc_html_e( "Please enter your name", 'wp-lucky-wheel' ); ?>"
                                           id="wplwl_player_name">
                                </label>
                                <label>
                                    <span id="wplwl_error_mail"></span>
                                    <input type="mail" class="wplwl_field_input wplwl_field_email"
                                           name="wplwl_player_mail"
                                           placeholder="<?php esc_html_e( "Please enter your email", 'wp-lucky-wheel' ); ?>"
                                           id="wplwl_player_mail">
                                </label>
                                <span class="wplwl_chek_mail wplwl_spin_button button-primary" id="wplwl_chek_mail">
							<?php if ( $this->settings['wheel_wrap']['spin_button'] ) {
								echo $this->settings['wheel_wrap']['spin_button'];
							} else {
								esc_html_e( "Try Your Lucky", 'wp-lucky-wheel' );
							} ?>
                            </span>
								<?php
								if ( isset( $this->settings['wheel_wrap']['gdpr'] ) && 'on' == $this->settings['wheel_wrap']['gdpr'] ) {
									?>
                                    <div class="wplwl-gdpr-checkbox-wrap">
                                        <input type="checkbox">
                                        <span><?php echo isset( $this->settings['wheel_wrap']['gdpr_message'] ) && $this->settings['wheel_wrap']['gdpr_message'] ? $this->settings['wheel_wrap']['gdpr_message'] : esc_html__( "I agree with the term and condition", 'wp-lucky-wheel' ) ?></span>
                                    </div>
									<?php
								}
								if ( isset( $this->settings['wheel_wrap']['close_option'] ) && 'on' === $this->settings['wheel_wrap']['close_option'] ) {
									?>
                                    <div class="wplwl-show-again-option">
                                        <div class="wplwl-reminder-later"><a class="wplwl-reminder-later-a"
                                                                             href="#"><?php esc_html_e( "Remind later", 'wp-lucky-wheel' ); ?></a>
                                        </div>
                                        <div class="wplwl-never-again"><a
                                                    href="#"><?php esc_html_e( "Never", 'wp-lucky-wheel' ); ?></a>
                                        </div>
                                        <div class="wplwl-close"><a
                                                    href="#"><?php esc_html_e( "No thanks", 'wp-lucky-wheel' ); ?></a>
                                        </div>
                                    </div>
									<?php
								}

								?>
                            </div>
                            <div class="wplwl_wheel_content_left">
                                <div class="wplwl-frontend-result"></div>

                                <div class="wplwl_wheel_spin">
                                    <canvas id="wplwl_canvas">
                                    </canvas>
                                    <canvas id="wplwl_canvas1" class="<?php
									if ( $this->settings['wheel_wrap']['pointer_position'] == 'top' ) {
										echo 'canvas_spin_top';
									} elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'bottom' ) {
										echo 'canvas_spin_bottom';
									} ?>">
                                    </canvas>
                                    <canvas id="wplwl_canvas2">
                                    </canvas>
                                    <div class="wp_wheel_spin_container">
                                        <div class="wplwl_pointer_before"></div>
                                        <div class="wplwl_pointer_content">
                                        <span class="wplwl-location wplwl_pointer <?php
                                        if ( $this->settings['wheel_wrap']['pointer_position'] == 'top' ) {
	                                        echo 'wplwl_pointer_spin_top';
                                        } elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'bottom' ) {
	                                        echo 'wplwl_pointer_spin_bottom';
                                        } ?>"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
					if ( ! isset( $this->settings['wheel_wrap']['close_option'] ) || 'on' !== $this->settings['wheel_wrap']['close_option'] ) {
						?>
                        <div class="wplwl-close-wheel"><span class="wplwl-cancel"></span></div>
						<?php
					}
					?>
                    <div class="hide-after-spin"><span class="wplwl-cancel"></span></div>
                </div>
				<?php
			} else {
				?>
                <div class="wplwl_lucky_wheel_content <?php
				if ( $detect->isTablet() ) {
					echo 'wplwl_lucky_wheel_content_tablet ';
				}
				if ( $this->settings['wheel_wrap']['pointer_position'] == 'top' ) {
					echo 'wplwl_margin_position wplwl_spin_top';
				} elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'right' ) {
					echo 'wplwl_margin_position';
				} elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'bottom' ) {
					echo 'wplwl_margin_position wplwl_spin_bottom';
				} ?>">
                    <div class="wplwl-wheel-content-wrapper">
                        <div class="wplwl_wheel_content_left">
                            <div class="wplwl_wheel_spin">
                                <canvas id="wplwl_canvas">
                                </canvas>
                                <canvas id="wplwl_canvas1" class="<?php
								if ( $this->settings['wheel_wrap']['pointer_position'] == 'top' ) {
									echo 'canvas_spin_top';
								} elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'bottom' ) {
									echo 'canvas_spin_bottom';
								} ?>">
                                </canvas>
                                <canvas id="wplwl_canvas2">
                                </canvas>
                                <div class="wp_wheel_spin_container">
                                    <div class="wplwl_pointer_before"></div>
                                    <div class="wplwl_pointer_content">
                                    <span class="wplwl-location wplwl_pointer <?php
                                    if ( $this->settings['wheel_wrap']['pointer_position'] == 'top' ) {
	                                    echo 'wplwl_pointer_spin_top';
                                    } elseif ( $this->settings['wheel_wrap']['pointer_position'] == 'bottom' ) {
	                                    echo 'wplwl_pointer_spin_bottom';
                                    } ?>"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wplwl_wheel_content_right">

                            <div class="wplwl_wheel_description">
								<?php echo stripslashes( $this->settings['wheel_wrap']['description'] ); ?>
                            </div>
                            <div class="wplwl_user_lucky">
                                <label>
                                    <input type="text" class="wplwl_field_input wplwl_field_name"
                                           name="wplwl_player_name"
                                           placeholder="<?php esc_html_e( "Please enter your name", 'wp-lucky-wheel' ); ?>"
                                           id="wplwl_player_name">
                                </label>
                                <label>
                                    <span id="wplwl_error_mail"></span>
                                    <input type="mail" class="wplwl_field_input wplwl_field_email"
                                           name="wplwl_player_mail"
                                           placeholder="<?php esc_html_e( "Please enter your email", 'wp-lucky-wheel' ); ?>"
                                           id="wplwl_player_mail">
                                </label>
                                <span class="wplwl_chek_mail wplwl_spin_button button-primary" id="wplwl_chek_mail">
							<?php if ( $this->settings['wheel_wrap']['spin_button'] ) {
								echo $this->settings['wheel_wrap']['spin_button'];
							} else {
								esc_html_e( "Try Your Lucky", 'wp-lucky-wheel' );
							} ?>
                            </span>
								<?php
								if ( isset( $this->settings['wheel_wrap']['gdpr'] ) && 'on' == $this->settings['wheel_wrap']['gdpr'] ) {
									?>
                                    <div class="wplwl-gdpr-checkbox-wrap">
                                        <input type="checkbox">
                                        <span><?php echo isset( $this->settings['wheel_wrap']['gdpr_message'] ) && $this->settings['wheel_wrap']['gdpr_message'] ? $this->settings['wheel_wrap']['gdpr_message'] : esc_html__( "I agree with the term and condition", 'wp-lucky-wheel' ) ?></span>
                                    </div>
									<?php
								}
								if ( isset( $this->settings['wheel_wrap']['close_option'] ) && 'on' === $this->settings['wheel_wrap']['close_option'] ) {
									?>
                                    <div class="wplwl-show-again-option">
                                        <div class="wplwl-reminder-later"><a class="wplwl-reminder-later-a"
                                                                             href="#"><?php esc_html_e( "Remind later", 'wp-lucky-wheel' ); ?></a>
                                        </div>
                                        <div class="wplwl-never-again"><a
                                                    href="#"><?php esc_html_e( "Never", 'wp-lucky-wheel' ); ?></a>
                                        </div>
                                        <div class="wplwl-close"><a
                                                    href="#"><?php esc_html_e( "No thanks", 'wp-lucky-wheel' ); ?></a>
                                        </div>
                                    </div>
									<?php
								}

								?>
                            </div>


                        </div>

                    </div>
					<?php
					if ( ! isset( $this->settings['wheel_wrap']['close_option'] ) || 'on' !== $this->settings['wheel_wrap']['close_option'] ) {
						?>
                        <div class="wplwl-close-wheel"><span class="wplwl-cancel"></span></div>
						<?php
					}
					?>
                    <div class="hide-after-spin"><span class="wplwl-cancel"></span></div>
                </div>
				<?php
			}
			?>
            <canvas id="wplwl_popup_canvas" class="wplwl_wheel_icon wp-lucky-wheel-popup-icon"
                    style="border-radius: 50%;" width="64"
                    height="64"></canvas>
			<?php

		}
	}
}
new WP_Lucky_Wheel();
