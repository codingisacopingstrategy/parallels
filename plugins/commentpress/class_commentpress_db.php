<?php /*
===============================================================
Class CommentPressDatabase Version 1.0
===============================================================
AUTHOR			: Christian Wach <needle@haystack.co.uk>
LAST MODIFIED	: 04/05/2009
---------------------------------------------------------------
NOTES
=====

This class is a wrapper for the majority of database operations.

---------------------------------------------------------------
*/






/*
===============================================================
Class Name
===============================================================
*/

class CommentPressDatabase {






	/*
	===============================================================
	Properties
	===============================================================
	*/
	
	// parent object reference
	var $parent_obj;
	
	// commentpress version
	var $version = '3.2';
	
	// options
	var $cp_options = array();
	
	// paragraph-level comments
	var $para_comments_enabled = 1;
	
	// TOC content
	var $toc_content = 'page';
	
	// TOC chapters are pages by default
	var $toc_chapter_is_page = 1;
	
	// TOC shows subpages by default
	var $show_subpages = 1;
	
	// show page titles by default
	var $title_visibility = 'show';
	
	// default editor (tinyMCE)
	var $comment_editor = 1;
	
	// promote reading (1) or commenting (0)
	var $promote_reading = 0;
	
	// allow sidebar to be minimised
	var $minimise_sidebar = 1;

	// default excerpt length
	var $excerpt_length = 55;
	
	// default header background colour (hex, same as in layout.css)
	var $header_bg_colour = '819565';
	
	// default scroll speed (ms)
	var $js_scroll_speed = '800';
	
	// default minimum page width (px)
	var $min_page_width = '447';
		






	/** 
	 * @description: initialises this object
	 * @param object $parent_obj a reference to the parent object
	 * @return object
	 * @todo: 
	 *
	 */
	function CommentPressDatabase( $parent_obj ) {
	
		// store reference to parent
		$this->parent_obj = $parent_obj;

		// init
		$this->_init();

		// --<
		return $this;

	}






	/** 
	 * @description: set up all items associated with this object
	 * @param integer $blog_id the ID of the blog - default null
	 * @todo: 
	 *
	 */
	function initialise( $blog_id = null ) {
	
		// update db schema
		$this->schema_update();
		
		// test that we aren't reactivating
		if ( !$this->option_wp_get( 'cp_version' ) ) {
		
			// add options with default values
			$this->options_create();
			
			// if we're force-activating in multisite (or sitewide)
			if ( CP_PLUGIN_CONTEXT == 'mu_forced' OR CP_PLUGIN_CONTEXT == 'mu_sitewide' ) {
			
				// create special pages
				$this->create_special_pages();
				
			}
			
			// enable comment threading (should this be an option?)
			//$this->_store_wordpress_option( 'thread_comments', '1' );
	
			// finally, turn comment paging option off
			$this->_cancel_comment_paging();
	
		}
		
	}







	/** 
	 * @description: upgrade Commentpress plugin from 3.1 to higher
	 * @return boolean $result
	 * @todo: 
	 *
	 */
	function upgrade() {
		
		// database object
		global $wpdb;
		
		// init return
		$result = false;



		// if we have a commentpress install (or we're forcing)
		if ( $this->check_upgrade() ) {
		


			// are we missing the cp_options option?
			if ( !$this->option_wp_exists( 'cp_options' ) ) {
			
				// upgrade to the single array
				$this->options_upgrade();
			
			}
			


			// get variables
			extract( $_POST );
			


			// are we missing the cp_comment_editor option?
			if ( !$this->option_exists( 'cp_comment_editor' ) ) {
			
				// get choice
				$_choice = $wpdb->escape( $cp_comment_editor );
			
				// add chosen cp_comment_editor option
				$this->option_set( 'cp_comment_editor', $_editor );
				
			}
			


			// are we missing the cp_promote_reading option?
			if ( !$this->option_exists( 'cp_promote_reading' ) ) {
			
				// get choice
				$_choice = $wpdb->escape( $cp_promote_reading );
			
				// add chosen cp_promote_reading option
				$this->option_set( 'cp_promote_reading', $_choice );
				
			}
			


			// are we missing the cp_title_visibility option?
			if ( !$this->option_exists( 'cp_title_visibility' ) ) {
			
				// get choice
				$_choice = $wpdb->escape( $cp_title_visibility );
			
				// add chosen cp_title_visibility option
				$this->option_set( 'cp_title_visibility', $_choice );
				
			}
			


			// are we missing the cp_header_bg_colour option?
			if ( !$this->option_exists( 'cp_header_bg_colour' ) ) {
			
				// get choice
				$_choice = $wpdb->escape( $cp_header_bg_colour );
			
				// strip our rgb #
				if ( stristr( $_choice, '#' ) ) {
					$_choice = substr( $_choice, 1 );
				}
				
				// reset to default if blank
				if ( $_choice == '' ) {
					$_choice = $this->header_bg_colour;
				}
				
				// add chosen cp_header_bg_colour option
				$this->option_set( 'cp_header_bg_colour', $_choice );
				
			}
			


			// are we missing the cp_js_scroll_speed option?
			if ( !$this->option_exists( 'cp_js_scroll_speed' ) ) {
			
				// get choice
				$_choice = $wpdb->escape( $cp_js_scroll_speed );
			
				// add chosen cp_js_scroll_speed option
				$this->option_set( 'cp_js_scroll_speed', $_choice );
				
			}
			


			// are we missing the cp_min_page_width option?
			if ( !$this->option_exists( 'cp_min_page_width' ) ) {
			
				// get choice
				$_choice = $wpdb->escape( $cp_min_page_width );
			
				// add chosen cp_min_page_width option
				$this->option_set( 'cp_min_page_width', $_choice );
				
			}
			


			// do we still have the legacy cp_allow_users_to_minimize option?
			if ( $this->option_exists( 'cp_allow_users_to_minimize' ) ) {
			
				// delete old cp_allow_users_to_minimize option
				$this->option_delete( 'cp_allow_users_to_minimize' );
				
			}
			


			// do we have special pages?
			if ( $this->option_exists( 'cp_special_pages' ) ) {
			
				// if we don't have the toc page...
				if ( !$this->option_exists( 'cp_toc_page' ) ) {
				
					// get special pages array
					$special_pages = $this->option_get( 'cp_special_pages' );
				
					// create TOC page -> a convenience, let's us define a logo as attachment
					$special_pages[] = $this->_create_toc_page();
		
					// store the array of page IDs that were created
					$this->option_set( 'cp_special_pages', $special_pages );
					
				}
	
			}
			


			// save new Commentpress options
			$this->options_save();
			
			// store new Commentpress version
			$this->option_wp_set( 'cp_version', CP_VERSION );
			
		}
		
		

		// --<
		return $result;
	}
	
	
	
	
	


	/** 
	 * @description: if needed, destroys all items associated with this object
	 * @todo: 
	 *
	 */
	function destroy() {
	
		// reset comment threading
		//$this->_reset_wordpress_option( 'thread_comments' );

		// reset comment paging option
		$this->_reset_comment_paging();

		// remove special pages
		//$this->delete_special_pages();
		
		// delete options
		$this->options_delete();
		
	}







	/** 
	 * @description: uninstalls database modifications
	 * @todo: 
	 *
	 */
	function uninstall() {
	
		// restore database schema
		// NOTE: we will lose all our submitted comment text signatures
		$this->schema_restore();
		
	}







//#################################################################







	/*
	===============================================================
	PUBLIC METHODS
	===============================================================
	*/
	




	/** 
	 * @description: update Wordpress database schema
	 * @return boolean $result
	 * @todo: 
	 *
	 */
	function schema_update() {
		
		// database object
		global $wpdb;
		


		// include Wordpress upgrade script
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		// add the column, if not already there
		$result = maybe_add_column(
		
			$wpdb->comments, 
			'comment_text_signature', 
			"ALTER TABLE `$wpdb->comments` ADD `comment_text_signature` VARCHAR(255) NULL;"
			
		);
		


		// --<
		return $result;
	}
	
	
	
	
	


	/** 
	 * @description: restore Wordpress database schema
	 * @return boolean $result
	 * @todo: 
	 *
	 */
	function schema_restore() {
		
		// database object
		global $wpdb;
		


		// include Wordpress install helper script
		require_once( ABSPATH . 'wp-admin/install-helper.php' );
		
		// add the column, if not already there
		$result = maybe_drop_column(
		
			$wpdb->comments, 
			'comment_text_signature', 
			"ALTER TABLE `$wpdb->comments` DROP `comment_text_signature`;"
			
		);
		


		// --<
		return $result;
	}
	
	
	
	
	


	/** 
	 * @description: do we have the comment_text_signature field?
	 * @return boolean $result
	 * @todo: 
	 *
	 */
	function db_is_modified() {
		
		// database object
		global $wpdb;
		
		// init
		$result = false;
		
		
		
		// define query
		$query = "DESCRIBE $wpdb->comments";
		
		// get columns
		$cols = $wpdb->get_results( $query );
		
		// loop
		foreach( $cols AS $col ) {
		
			// is it comment_text_signature?
			if ( $col->Field == 'comment_text_signature' ) {
				
				// we got it
				$result = true;
				break;
			
			}
		
		}
		


		// --<
		return $result;
	}
	
	
	
	
	


	/** 
	 * @description: check for plugin upgrade
	 * @return boolean $result
	 * @todo: 
	 *
	 */
	function check_upgrade() {
	
		// init
		$result = false;
		
		// get installed version
		$_version = $this->option_wp_get( 'cp_version' );
		
		// if we have a commentpress install and it's lower than this one
		if ( ( $_version !== false AND ( (float)$_version < (float)CP_VERSION ) ) ) {
		
			// override
			$result = true;

		}
		


		// --<
		return $result;
	}
	
	
	
	
	


	/** 
	 * @description: create all basic Commentpress options
	 * @todo: store plugin options in a single array
	 *
	 */
	function options_create() {
	
		// init options array --> TO DO
		$this->cp_options = array(
		
			'cp_para_comments_enabled' => $this->para_comments_enabled,
			'cp_show_posts_or_pages_in_toc' => $this->toc_content,
			'cp_toc_chapter_is_page' => $this->toc_chapter_is_page,
			'cp_show_subpages' => $this->show_subpages,
			'cp_title_visibility' => $this->title_visibility,
			'cp_header_bg_colour' => $this->header_bg_colour,
			'cp_js_scroll_speed' => $this->js_scroll_speed,
			'cp_min_page_width' => $this->min_page_width,
			'cp_comment_editor' => $this->comment_editor,
			'cp_promote_reading' => $this->promote_reading,
			'cp_minimise_sidebar' => $this->minimise_sidebar,
			'cp_excerpt_length' => $this->excerpt_length
		
		);
		
		// Paragraph-level comments enabled by default
		add_option( 'cp_options', $this->cp_options );
		
		// store Commentpress version
		add_option( 'cp_version', CP_VERSION );
		
	}
	
	
	
	
	


	/** 
	 * @description: delete all basic Commentpress options
	 * @todo: 
	 *
	 */
	function options_delete() {
		
		// delete Commentpress version
		delete_option( 'cp_version' );
		
		// delete Commentpress options
		delete_option( 'cp_options' );
		
	}
	
	
	
	
	


	/** 
	 * @description: save the settings set by the administrator
	 * @return boolean success or failure
	 * @todo: do more error checking?
	 *
	 */
	function options_update() {
	
		// database object
		global $wpdb;
		
		
	
		// init result
		$result = false;
		


	 	// was the form submitted?
		if( $_POST['cp_submit'] ) {
			


			// check that we trust the source of the data
			check_admin_referer( 'cp_admin_action', 'cp_nonce' );
		


			// get variables
			extract( $_POST );
			
			
			
			// did we ask to install Commentpress?
			if ( $cp_install == '1' ) {
			
				// add database modifications
				$this->schema_update();
				
				// --<
				return true;
			
			}
			
			
			
			// did we ask to uninstall Commentpress?
			if ( $cp_uninstall == '1' ) {
			
				// remove database modifications
				$this->uninstall();
				
				// --<
				return true;
			
			}
			
			
			
			// did we ask to upgrade Commentpress?
			if ( $cp_upgrade == '1' ) {
			
				// do upgrade
				$this->upgrade();
				
				// --<
				return true;
			
			}
			
			
			
			// did we ask to reset?
			if ( $cp_reset == '1' ) {
			
				// Is it one of our themes?
				if ( $this->parent_obj->is_allowed_theme() ) {

					// reset theme options
					$this->options_reset_theme();
			
				}
				
				// --<
				return true;
			
			}


			
			// did we ask to auto-create special pages?
			if ( $cp_create_pages == '1' ) {
			
				// remove any existing special pages
				$this->delete_special_pages();
				
				// create special pages
				$this->create_special_pages();
				
			}
			
			
			
			// did we ask to delete special pages?
			if ( $cp_delete_pages == '1' ) {
			
				// remove special pages
				$this->delete_special_pages();

			}
			
			
			
			// Is it one of our themes?
			if ( $this->parent_obj->is_allowed_theme() ) {
		
				// Commentpress Theme params 

				// comments enabled
				$cp_para_comments_enabled = $wpdb->escape( $cp_para_comments_enabled );
				$this->option_set( 'cp_para_comments_enabled', ( $cp_para_comments_enabled ? 1 : 0 ) );
				
				// TOC content
				$cp_show_posts_or_pages_in_toc = $wpdb->escape( $cp_show_posts_or_pages_in_toc );
				$this->option_set( 'cp_show_posts_or_pages_in_toc', $cp_show_posts_or_pages_in_toc );
				
				// if we have pages in TOC and a value for the next param...
				if ( $cp_show_posts_or_pages_in_toc == 'page' AND isset( $cp_toc_chapter_is_page ) ) {
					
					$cp_toc_chapter_is_page = $wpdb->escape( $cp_toc_chapter_is_page );
					$this->option_set( 'cp_toc_chapter_is_page', $cp_toc_chapter_is_page );
					
					// if chapters are not pages and we have a value for the next param...
					if ( $cp_toc_chapter_is_page == '0' ) {
						
						$cp_show_subpages = $wpdb->escape( $cp_show_subpages );
						$this->option_set( 'cp_show_subpages', ( $cp_show_subpages ? 1 : 0 ) );

					} else {
					
						// always set to show subpages
						$this->option_set( 'cp_show_subpages', 1 );
					
					}

				}

				$this->option_set( 'cp_excerpt_length', $cp_excerpt_length );
				$cp_excerpt_length = $wpdb->escape( $cp_excerpt_length );
				
				// (I have disabled being able to CHANGE these for now)
				//$cp_welcome_page = $wpdb->escape( $cp_welcome_page );
				//$cp_blog_page = $wpdb->escape( $cp_blog_page );
				//$cp_general_comments_page = $wpdb->escape( $cp_general_comments_page );
				//$cp_all_comments_page = $wpdb->escape( $cp_all_comments_page );
				//$cp_comments_by_page = $wpdb->escape( $cp_comments_by_page );
				//$this->option_set( 'cp_welcome_page', $cp_welcome_page );
				//$this->option_set( 'cp_blog_page', $cp_blog_page );
				//$this->option_set( 'cp_general_comments_page', $cp_general_comments_page );
				//$this->option_set( 'cp_all_comments_page', $cp_all_comments_page );
				//$this->option_set( 'cp_comments_by_page', $cp_comments_by_page );

			}
	
	

			// common params
			
			// cooment editor
			$cp_comment_editor = $wpdb->escape( $cp_comment_editor );
			$this->option_set( 'cp_comment_editor', ( $cp_comment_editor ? 1 : 0 ) );
			
			// behaviour
			$cp_promote_reading = $wpdb->escape( $cp_promote_reading );
			$this->option_set( 'cp_promote_reading', ( $cp_promote_reading ? 1 : 0 ) );
			
			// title visibility
			$cp_title_visibility = $wpdb->escape( $cp_title_visibility );
			$this->option_set( 'cp_title_visibility', $cp_title_visibility );
			
			// header background colour
			
			// strip our rgb #
			if ( stristr( $cp_header_bg_colour, '#' ) ) {
				$cp_header_bg_colour = substr( $cp_header_bg_colour, 1 );
			}
			
			// reset to default if blank
			if ( $cp_header_bg_colour == '' ) {
				$cp_header_bg_colour = $this->header_bg_colour;
			}
			
			// save it
			$cp_header_bg_colour = $wpdb->escape( $cp_header_bg_colour );
			$this->option_set( 'cp_header_bg_colour', $cp_header_bg_colour );
			
			// save scroll speed
			$cp_js_scroll_speed = $wpdb->escape( $cp_js_scroll_speed );
			$this->option_set( 'cp_js_scroll_speed', $cp_js_scroll_speed );
			
			// save min page width
			$cp_min_page_width = $wpdb->escape( $cp_min_page_width );
			$this->option_set( 'cp_min_page_width', $cp_min_page_width );
			
			// minimise sidebar
			$cp_minimise_sidebar = $wpdb->escape( $cp_minimise_sidebar );
			$this->option_set( 'cp_minimise_sidebar', ( $cp_minimise_sidebar ? 1 : 0 ) );

			// save
			$this->options_save();
			
			

			// set flag
			$result = true;
	
		}
		
		
		
		// --<
		return $result;
		
	}
	
	
	
	
	
	
	
	/** 
	 * @description: upgrade Commentpress options to array
	 * @todo: 
	 *
	 */
	function options_save() {
		
		// set option
		return $this->option_wp_set( 'cp_options', $this->cp_options );
		
	}
	
	
	
	
	


	/** 
	 * @description: reset Commentpress theme options
	 * @todo: 
	 *
	 */
	function options_reset_theme() {
		
		// Paragraph-level commenting on by default
		$this->option_set( 'cp_para_comments_enabled', $this->para_comments_enabled );

		// TOC: show posts by default
		$this->option_set( 'cp_show_posts_or_pages_in_toc', $this->toc_content );

		// TOC: are chapters pages
		$this->option_set( 'cp_toc_chapter_is_page', $this->toc_chapter_is_page );

		// TOC: if pages are shown, show subpages by default
		$this->option_set( 'cp_show_subpages', $this->show_subpages );

		// comment editor
		$this->option_set( 'cp_comment_editor', $this->comment_editor );

		// promote reading or commenting
		$this->option_set( 'cp_promote_reading', $this->promote_reading );

		// show or hide titles
		$this->option_set( 'cp_title_visibility', $this->title_visibility );

		// header background colour
		$this->option_set( 'cp_header_bg_colour', $this->header_bg_colour );

		// js scroll speed
		$this->option_set( 'cp_js_scroll_speed', $this->js_scroll_speed );

		// minimum page width
		$this->option_set( 'cp_min_page_width', $this->min_page_width );

		// Blog: excerpt length
		$this->option_set( 'cp_excerpt_length', $this->excerpt_length );
		
		// store it
		$this->options_save();
		
	}
	
	
	
	
	


	/** 
	 * @description: upgrade Commentpress options to array (only for pre 3.2 upgrades)
	 * @todo: 
	 *
	 */
	function options_upgrade() {
	
		// populate options array with current values
		$this->cp_options = array(
			
			// theme settings we want to keep
			'cp_para_comments_enabled' => $this->option_wp_get( 'cp_para_comments_enabled'),
			'cp_show_posts_or_pages_in_toc' => $this->option_wp_get( 'cp_show_posts_or_pages_in_toc' ),
			'cp_toc_chapter_is_page' => $this->option_wp_get( 'cp_toc_chapter_is_page'),
			'cp_show_subpages' => $this->option_wp_get( 'cp_show_subpages'),
			'cp_minimise_sidebar' => $this->option_wp_get( 'cp_allow_users_to_minimize'),
			'cp_excerpt_length' => $this->option_wp_get( 'cp_excerpt_length'),
			
			// migrate special pages
			'cp_special_pages' => $this->option_wp_get( 'cp_special_pages'),
			'cp_welcome_page' => $this->option_wp_get( 'cp_welcome_page'),
			'cp_general_comments_page' => $this->option_wp_get( 'cp_general_comments_page'),
			'cp_all_comments_page' => $this->option_wp_get( 'cp_all_comments_page'),
			'cp_comments_by_page' => $this->option_wp_get( 'cp_comments_by_page'),
			'cp_blog_page' => $this->option_wp_get( 'cp_blog_page')
		
		);
		
		// save options array
		$this->options_save();
		
		// delete all old options
		$this->options_delete_legacy();
		
	}
	
	
	
	
	


	/** 
	 * @description: delete all legacy Commentpress options
	 * @todo: 
	 *
	 */
	function options_delete_legacy() {

		// delete paragraph-level commenting option
		delete_option( 'cp_para_comments_enabled' );
		
		// delete TOC options
		delete_option( 'cp_show_posts_or_pages_in_toc' );
		delete_option( 'cp_show_subpages' );
		delete_option( 'cp_toc_chapter_is_page' );
		
		// delete comment editor
		delete_option( 'cp_comment_editor' );
		
		// promote reading or commenting
		delete_option( 'cp_promote_reading' );
		
		// show or hide titles
		delete_option( 'cp_title_visibility' );
		
		// header bg colour
		delete_option( 'cp_header_bg_colour' );
		
		// header bg colour
		delete_option( 'cp_js_scroll_speed' );
		
		// header bg colour
		delete_option( 'cp_min_page_width' );
		
		// delete skin
		delete_option( 'cp_default_skin' );
		
		// window appearance options
		delete_option( 'cp_default_left_position' );
		delete_option( 'cp_default_top_position' );
		delete_option( 'cp_default_width' );
		delete_option( 'cp_default_height' );

		// window behaviour options		
		delete_option( 'cp_allow_users_to_iconize' );
		delete_option( 'cp_allow_users_to_minimize' );
		delete_option( 'cp_allow_users_to_resize' );
		delete_option( 'cp_allow_users_to_drag' );
		delete_option( 'cp_allow_users_to_save_position' );

		// blog options
		delete_option( 'cp_excerpt_length' );
		
		// special pages options
		delete_option( 'cp_special_pages' );
		delete_option( 'cp_welcome_page' );
		delete_option( 'cp_general_comments_page' );
		delete_option( 'cp_all_comments_page' );
		delete_option( 'cp_comments_by_page' );
		delete_option( 'cp_blog_page' );

	}
	
	
	
	
	


	/** 
	 * @description: return a value for a specified option
	 * @todo: 
	 */
	function option_exists( $option_name = '' ) {
	
		// test for null
		if ( $option_name == '' ) {
		
			// oops
			die( 'You must supply an option to option_exists()' );
		
		}
	
		// get option with unlikey default
		return array_key_exists( $option_name, $this->cp_options );
		
	}
	
	
	
	
	
	
	/** 
	 * @description: return a value for a specified option
	 * @todo: 
	 */
	function option_get( $option_name = '', $default = false ) {
	
		// test for null
		if ( $option_name == '' ) {
		
			// oops
			die( 'You must supply an option to option_get()' );
		
		}
	
		// get option
		return ( array_key_exists( $option_name, $this->cp_options ) ) ? $this->cp_options[ $option_name ] : $default;
		
	}
	
	
	
	
	
	
	/** 
	 * @description: sets a value for a specified option
	 * @todo: 
	 */
	function option_set( $option_name = '', $value = '' ) {
	
		// test for null
		if ( $option_name == '' ) {
		
			// oops
			die( 'You must supply an option to option_set()' );
		
		}
	
		// test for other than string
		if ( !is_string( $option_name ) ) {
		
			// oops
			die( 'You must supply the option as a string to option_set()' );
		
		}
	
		// set option
		$this->cp_options[ $option_name ] = $value;
		
	}
	
	
	
	
	
	
	/** 
	 * @description: deletes a specified option
	 * @todo: 
	 */
	function option_delete( $option_name = '' ) {
	
		// test for null
		if ( $option_name == '' ) {
		
			// oops
			die( 'You must supply an option to option_delete()' );
		
		}
	
		// unset option
		unset( $this->cp_options[ $option_name ] );
		
	}
	
	
	
	
	
	
	/** 
	 * @description: return a value for a specified option
	 * @todo: 
	 */
	function option_wp_exists( $option_name = '' ) {
	
		// test for null
		if ( $option_name == '' ) {
		
			// oops
			die( 'You must supply an option to option_wp_exists()' );
		
		}
	
		// get option with unlikey default
		if ( $this->option_wp_get( $option_name, 'fenfgehgejgrkj' ) == 'fenfgehgejgrkj' ) {
		
			// no
			return false;
		
		} else {
		
			// yes
			return true;
		
		}
		
	}
	
	
	
	
	
	
	/** 
	 * @description: return a value for a specified option
	 * @todo: 
	 */
	function option_wp_get( $option_name = '', $default = false ) {
	
		// test for null
		if ( $option_name == '' ) {
		
			// oops
			die( 'You must supply an option to option_wp_get()' );
		
		}
	
		// get option
		return get_option( $option_name, $default );
		
	}
	
	
	
	
	
	
	/** 
	 * @description: sets a value for a specified option
	 * @todo: 
	 */
	function option_wp_set( $option_name = '', $value = '' ) {
	
		// test for null
		if ( $option_name == '' ) {
		
			// oops
			die( 'You must supply an option to option_wp_set()' );
		
		}
	
		// set option
		return update_option( $option_name, $value );
		
	}
	
	
	
	
	
	
	/** 
	 * @description: get default header bg colour
	 * @todo: 
	 */
	function option_get_header_bg() {
	
		// test for option
		if ( $this->option_exists( 'cp_header_bg_colour' ) ) {
		
			// --<
			return $this->option_get( 'cp_header_bg_colour' );
			
		} else {
		
			// --<
			return $this->header_bg_colour;
			
		}
	
	}
	
	
	
	
	
	
	/** 
	 * @description: when a page is saved, this also saves the CP options
	 * @param object $post_obj the post object
	 * @return boolean $result
	 * @todo: 
	 *
	 */
	function save_page_meta( $post_obj ) {
		
		//print_r( 'data: '.$_data ); die();
		//print_r( '$post_obj->post_type: '.$post_obj->post_type ); die();
		//print_r( '$post_obj->ID: '.$post_obj->ID ); die();
		
		// if no post, kick out
		if ( !$post_obj ) { return; }
		
		// if not page, kick out
		if ( $post_obj->post_type != 'page' ) { return; }
		
		
		
		// authenticate
		if ( !wp_verify_nonce( $_POST['cp_nonce'], 'cp_page_settings' ) ) { return; }
		
		// is this an auto save routine?
		if ( defined('DOING_AUTOSAVE') AND DOING_AUTOSAVE ) { return; }
		
		// Check permissions
		if ( !current_user_can( 'edit_page', $post_obj->ID ) ) { return; }
		

		
		// OK, we're authenticated
		
		
		
		// check for revision
		if ( $post_obj->post_type == 'revision' ) {
		
			// get parent
			if ( $post_obj->post_parent != 0 ) {
				$post = get_post( $post_obj->post_parent );
			} else {
				$post = $post_obj;
			}
	
		} else {
			$post = $post_obj;
		}
		


		// database object and post
		global $wpdb;
		


		// --------------------------------------------------------------
		// Show or Hide Page Title
		// --------------------------------------------------------------
		
		// find and save the data
		$_data = ( isset( $_POST['cp_title_visibility'] ) ) ? $_POST['cp_title_visibility'] : 'show';

		//print_r( '$_data: '.$_data ); die();
		//print_r( $post ); die();

		// set key
		$key = '_cp_title_visibility';
		
		//if the custom field already has a value...
		if ( get_post_meta( $post->ID, $key, true ) != '' ) {
		
			// update the data
			update_post_meta( $post->ID, $key, $wpdb->escape( $_data ) );
			
		} else {
		
			// add the data
			add_post_meta( $post->ID, $key, $wpdb->escape( $_data ) );
			
		}



		// --------------------------------------------------------------
		// Page Numbering - only first top level page is allowed to send this
		// --------------------------------------------------------------
		
		// was the value sent?
		if ( isset( $_POST['cp_number_format'] ) ) {
		
			// set meta key
			$key = '_cp_number_format';
			
			if ( 
				
				// do we need to check this, since only the first top level page
				// can now send this data? doesn't hurt to validate, I guess.
				$post->post_parent == '0' AND 
				!$this->is_special_page() AND 
				$post->ID == $this->parent_obj->nav->get_first_page() 
				
			) { // -->
	
				// get the data
				$_data = $_POST['cp_number_format'];
		
				//print_r( $post->ID ); die();
				
				//if the custom field already has a value...
				if ( get_post_meta( $post->ID, $key, true ) != '' ) {
				
					// update the data
					update_post_meta( $post->ID, $key, $wpdb->escape( $_data ) );
					
				} else {
				
					// add the data
					add_post_meta( $post->ID, $key, $wpdb->escape( $_data ) );
					
				}

			}
			
			// delete this meta value from all other pages, because we may have altered
			// the relationship between pages, thus causing the page numbering to fail
			
			// get all pages including chapters
			$all_pages = $this->parent_obj->nav->get_book_pages( 'structural' );
			
			// if we have any pages...
			if ( count( $all_pages ) > 0 ) {
			
				// loop
				foreach( $all_pages AS $_page ) {
				
					// exclude first top level page
					if ( $post->ID != $_page->ID ) {
				
						// delete the meta value
						delete_post_meta( $_page->ID, $key );
					
					}
				
				}
			
			}
			
		}
		
		
		
		// --------------------------------------------------------------
		// Page Layout for Title Page -> to allow for Book Cover image
		// --------------------------------------------------------------
		
		// is this the title page?
		if ( $post->ID == $this->option_get( 'cp_welcome_page' ) ) {
		
			// find and save the data
			$_data = ( isset( $_POST['cp_page_layout'] ) ) ? $_POST['cp_page_layout'] : 'text';
	
			// set key
			$key = '_cp_page_layout';
			
			//if the custom field already has a value...
			if ( get_post_meta( $post->ID, $key, true ) != '' ) {
			
				// update the data
				update_post_meta( $post->ID, $key, $wpdb->escape( $_data ) );
				
			} else {
			
				// add the data
				add_post_meta( $post->ID, $key, $wpdb->escape( $_data ) );
				
			}
			
		}

	}
	
	
	
	
	


	/** 
	 * @description: get javascript for the plugin, context dependent
	 * @return string $script
	 * @todo: 
	 *
	 */
	function get_javascript_vars() {
	
		// init return
		$vars = array();
		
		
	
		// add comments open
		global $post;
		$vars['cp_comments_open'] = ( $post->comment_status == 'open' ) ? 'y' : 'n';
		
		// add comments-on-paragraphs flag
		$vars['cp_para_comments_enabled'] = $this->option_get('cp_para_comments_enabled');
		
		// add rich text editor
		$vars['cp_tinymce'] = 1;
		
		// check option
		if ( 
		
			$this->option_exists( 'cp_comment_editor' ) AND
			$this->option_get( 'cp_comment_editor' ) != '1'
			
		) {
		
			// replace with Javascript for moving our comment form without TinyMCE
			//$comment_form_handler = 'cp_js_form_plain.js';
			
			// don't add rich text editor
			$vars['cp_tinymce'] = 0;
			
		}
		
		// add mobile var
		$vars['cp_is_mobile'] = 0;

		// is it a mobile?
		if ( $this->is_mobile_touch OR $this->is_mobile ) {
			
			// not mobile
			$vars['cp_is_mobile'] = 1;
			
		}
		


		// Is it one of our themes?
		if ( $this->parent_obj->is_allowed_theme() ) {
		
			// add rich text editor behaviour
			$vars['cp_promote_reading'] = 1;
			
			// check option
			if ( 
			
				$this->option_exists( 'cp_promote_reading' ) AND
				$this->option_get( 'cp_promote_reading' ) != '1'
				
			) {
			
				// promote commenting
				$vars['cp_promote_reading'] = 0;
				
			}
			
			// add special page var
			$vars['cp_special_page'] = ( $this->is_special_page() ) ? '1' : '0';
	
			// get path
			$url_info = parse_url( get_option('siteurl') );
			
			// add path for cookies
			$vars['cp_cookie_path'] = trailingslashit( $url_info['path'] );
			
			// add page
			global $page;
			$vars['cp_multipage_page'] = ( !empty( $page ) ) ? $page : 0;
			
			// add path to template directory
			$vars['cp_template_dir'] = get_bloginfo('template_url');
			
			// add path to plugin directory
			$vars['cp_plugin_dir'] = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
			
			// are chapters pages?
			$vars['cp_toc_chapter_is_page'] = $this->option_get( 'cp_toc_chapter_is_page' );
			
			// are subpages shown?
			$vars['cp_show_subpages'] = $this->option_get( 'cp_show_subpages' );
		
			// set default sidebar
			$vars['cp_default_sidebar'] = $this->parent_obj->get_default_sidebar();
			
			// set scroll speed
			$vars['cp_js_scroll_speed'] = $this->option_get( 'cp_js_scroll_speed' );;
			
			// set min page width
			$vars['cp_min_page_width'] = $this->option_get( 'cp_min_page_width' );;
			
			// set signup flag
			$vars['cp_is_signup_page'] = '0';
			
			// test for signup
			if ( $this->parent_obj->is_signup_page() ) {
			
				// set flag
				$vars['cp_is_signup_page'] = '1';
				
			}
			
		}
		
		
		
		// --<
		return $vars;
			
	}
	
	
	
	
	
	

	/** 
	 * @description: create all "special" pages
	 * @todo: 
	 *
	 */
	function create_special_pages() {
	
		// NOTE: one of the Commentpress themes MUST be active, or WordPress will
		// fail to set the page templates for the pages that require them.
		// Also, a user must be logged in for these pages to be associated with them.
	
		// Is it one of our themes?
		if ( $this->parent_obj->is_allowed_theme() ) {

			// get special pages array, if it's there
			$special_pages = $this->option_get( 'cp_special_pages' );
		


			// create welcome/title page
			$special_pages[] = $this->_create_title_page();
			
			// create general comments page
			$special_pages[] = $this->_create_general_comments_page();

			// create all comments page
			$special_pages[] = $this->_create_all_comments_page();
			
			// create comments by author page
			$special_pages[] = $this->_create_comments_by_author_page();

			// create blog page
			$special_pages[] = $this->_create_blog_page();
			
			// create TOC page -> a convenience, let's us define a logo as attachment
			$special_pages[] = $this->_create_toc_page();



			// store the array of page IDs that were created
			$this->option_set( 'cp_special_pages', $special_pages );
			
			// save changes
			$this->options_save();
	
		}
	
	}
	
	
	
	
	


	/** 
	 * @description: create a particular "special" page
	 * @todo: 
	 *
	 */
	function create_special_page( $_page ) {
	
		// init
		$new_id = false;
		
		
	
		// Is it one of our themes?
		if ( $this->parent_obj->is_allowed_theme() ) {
		
			// get special pages array, if it's there
			$special_pages = $this->option_get( 'cp_special_pages' );
		


			// switch by page
			switch( $_page ) {
			
				case 'title':
				
					// create welcome/title page
					$new_id = $this->_create_title_page();
					break;
			
				case 'general comments':
				
					// create general comments page
					$new_id = $this->_create_general_comments_page();
					break;
			
				case 'all comments':
				
					// create all comments page
					$new_id = $this->_create_all_comments_page();
					break;
			
				case 'comments by author':
				
					// create comments by author page
					$new_id = $this->_create_comments_by_author_page();
					break;
			
				case 'blog':
				
					// create blog page
					$new_id = $this->_create_blog_page();
					break;
			
				case 'toc':
				
					// create TOC page
					$new_id = $this->_create_toc_page();
					break;
			
			}
			
			
			
			// add to special pages
			$special_pages[] = $new_id;

			// reset option
			$this->option_set( 'cp_special_pages', $special_pages );
			
			// save changes
			$this->options_save();
	
		}
		
		
		
		// --<
		return $new_id;
	
	}
	
	
	
	
	


	/** 
	 * @description: delete "special" pages
	 * @return boolean $success
	 * @todo: 
	 *
	 */
	function delete_special_pages() {
	
		// init success flag
		$success = true;
		


		// Is it one of our themes?
		if ( $this->parent_obj->is_allowed_theme() ) {
		
			// only delete special pages if we have one of the Commentpress themes active
			// because other themes may have a totally different way of presenting the
			// content of the blog
	
			// retrieve data on special pages
			$special_pages = $this->option_get( 'cp_special_pages' );
			
			// if we have created any...
			if ( is_array( $special_pages ) AND count( $special_pages ) > 0 ) {
			
				// loop through them
				foreach( $special_pages AS $special_page ) {
				
					// try and delete each page...
					if ( !wp_delete_post( $special_page ) ) {
					
						// oops, set success flag to false
						$success = false;
					
					}
				
				}
			
				// delete the corresponding options
				$this->option_delete( 'cp_special_pages' );
				$this->option_delete( 'cp_welcome_page' );
				$this->option_delete( 'cp_blog_page' );
				$this->option_delete( 'cp_general_comments_page' );
				$this->option_delete( 'cp_all_comments_page' );
				$this->option_delete( 'cp_comments_by_page' );
				$this->option_delete( 'cp_toc_page' );
				
				// save changes
				$this->options_save();
				
				// reset Wordpress internal page references
				$this->_reset_wordpress_option( 'show_on_front' );
				$this->_reset_wordpress_option( 'page_on_front' );
				$this->_reset_wordpress_option( 'page_for_posts' );
		
			}
		
		}



		// --<
		return $success;

	}
	
	
	
	
	


	/** 
	 * @description: delete a particular "special" page
	 * @return boolean $success
	 * @todo: 
	 *
	 */
	function delete_special_page( $_page ) {
	
		// init success flag
		$success = true;
		


		// Is it one of our themes?
		if ( $this->parent_obj->is_allowed_theme() ) {
		
			// only delete a special page if we have one of the Commentpress themes active
			// because other themes may have a totally different way of presenting the
			// content of the blog
			


			// get id of special page
			switch( $_page ) {
			
				case 'title':
				
					// set flag
					$flag = 'cp_welcome_page';

					// reset Wordpress internal page references
					$this->_reset_wordpress_option( 'show_on_front' );
					$this->_reset_wordpress_option( 'page_on_front' );
		
					break;
			
				case 'general comments':
				
					// set flag
					$flag = 'cp_general_comments_page';
					break;
			
				case 'all comments':
				
					// set flag
					$flag = 'cp_all_comments_page';
					break;
			
				case 'comments by author':
				
					// set flag
					$flag = 'cp_comments_by_page';
					break;
			
				case 'blog':
				
					// set flag
					$flag = 'cp_blog_page';

					// reset Wordpress internal page reference
					$this->_reset_wordpress_option( 'page_for_posts' );
				
					break;
			
				case 'toc':
				
					// set flag
					$flag = 'cp_toc_page';
					break;
			
			}
			


			// get welcome/title page
			$page_id = $this->option_get( $flag );
			
			// kick out if it doesn't exist
			if ( !$page_id ) { return true; }



			// delete option
			$this->option_delete( $flag );



			// try and delete the page...
			if ( !wp_delete_post( $page_id ) ) {
			
				// oops, set success flag to false
				$success = false;
			
			}
		


			// retrieve data on special pages
			$special_pages = $this->option_get( 'cp_special_pages' );
			
			// remove page id
			$special_pages = array_diff( $special_pages, array( $page_id ) );

			// reset option
			$this->option_set( 'cp_special_pages', $special_pages );
			
			// save changes
			$this->options_save();
			
		}



		// --<
		return $success;

	}
	
	
	
	
	


	/** 
	 * @description: test if a page is a "special" page
	 * @return boolean $is_special_page
	 * @todo: 
	 *
	 */
	function is_special_page() {
	
		// init flag
		$is_special_page = false;
		


		// access post object
		global $post;
		
		// do we have one?
		if ( !is_object( $post ) ) {
		
			// --<
			return $is_special_page;
			
		}
	


		// get special pages
		$special_pages = $this->option_get('cp_special_pages');
	
		// do we have a special page array?
		if ( is_array( $special_pages ) AND count( $special_pages ) > 0 ) {
		
			// is the current page one?
			if ( in_array( $post->ID, $special_pages ) ) {
			
				// it is...
				$is_special_page = true;
			
			}
		
		}



		// --<
		return $is_special_page;

	}
	
	
	
	
	


	/** 
	 * @description: check if a post allows comments to be posted
	 * @return boolean $allowed
	 * @todo: 
	 *
	 */
	function comments_enabled() {
	
		// init return
		$allowed = false;
		


		// access post object
		global $post;
		
		// do we have one?
		if ( !is_object( $post ) ) {
		
			// --<
			return $allowed;
			
		}
	


		// are comments enabled on this post?
		if ( $post->comment_status == 'open' ) {
		
			// set return
			$allowed = true;
			
		}



		// --<
		return $allowed;
	}
	
	
	
	
	


	/** 
	 * @description: get Wordpress approved comments
	 * @param integer $post_id the ID of the post
	 * @return array $comments
	 * @todo: 
	 *
	 */
	function get_approved_comments( $post_ID ) {
		
		// for Wordpress, we use the API
		$comments = get_approved_comments( $post_ID );



		// --<
		return $comments;
	}
	
	
	
	
	


	/** 
	 * @description: get all Wordpress comments
	 * @param integer $post_id the ID of the post
	 * @return array $comments
	 * @todo: 
	 *
	 */
	function get_all_comments( $post_ID ) {
	
		// access post
		global $post;
		
		// get all by default
		$pings = '';
		
		// check what we're allowing
		if ( $post->ping_status != 'open' ) {
		
			$pings = '&type=comment';

		}
		
		// for Wordpress, we use the API
		$comments = get_comments( 'post_id='.$post_ID.'&order=ASC'.$pings );
		
		// --<
		return $comments;
	}
	
	
	
	
	


	/** 
	 * @description: get all comments for a post
	 * @param integer $post_id the ID of the post
	 * @return array $comments
	 * @todo: 
	 *
	 */
	function get_comments( $post_ID ) {
	
		// database object
		global $wpdb;
		
		
		
		// get comments from db
		$comments = $wpdb->get_results(
		
			$wpdb->prepare(
			
				"SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d", 
				$post_ID
				
			)
			
		);
		

		
		// --<
		return $comments;	
	
	}
	






	/** 
	 * @description: when a comment is saved, this also saves the text signature
	 * @param integer $comment_id the ID of the comment
	 * @return boolean $result
	 * @todo: 
	 *
	 */
	function save_comment_signature( $comment_ID ) {
		
		// database object
		global $wpdb;
		
		
		// get text signature
		$text_signature = ( isset( $_POST['text_signature'] ) ) ? $_POST['text_signature'] : '';
		
		// did we get one?
		if ( $text_signature != '' ) {
		
			// escape it
			$text_signature = $wpdb->escape( $text_signature );
			
			// construct query
			$query = $wpdb->prepare(
					
				"UPDATE $wpdb->comments SET comment_text_signature = %s WHERE comment_ID = %d", 
				$text_signature, 
				$comment_ID
			
			);
	
			//var_dump( $query );
	
	
	
			// store comment signature
			$result = $wpdb->query( $query );
		
		} else {
		
			// set result to true... why not, eh?
			$result = true;
		
		}
		
		
		
		// --<
		return $result;
		
	}
	
	
	
	
	


	/** 
	 * @description: retrieves text signature by comment ID
	 * @param integer $comment_id the ID of the comment
	 * @return string $comment_text_signature
	 * @todo: 
	 *
	 */
	function get_text_signature_by_comment_id( $comment_ID ) {
	
		// database object
		global $wpdb;


		
		// query for signature
		$comment_text_signature = $wpdb->get_var( 
		
			$wpdb->prepare(
			
				"SELECT comment_text_signature FROM $wpdb->comments WHERE comment_ID = %s", 
				$comment_ID
				
			) 
			
		);
		
		
		
		// --<
		return $comment_text_signature;
		
	}
	
	
	
	
	
	

	/** 
	 * @description: store text sigs in a global - because some versions of PHP do not save properties!
	 * @param: array $sigs array of text signatures
	 * @todo: 
	 *
	 */
	function set_text_sigs( $sigs ) {
	
		global $ffffff_sigs;
	
		// store them
		$ffffff_sigs = $sigs;
		
		//var_dump( $ffffff_sigs );
		
	}
	
	
	
	
	




	/** 
	 * @description: retrieve text sigs
	 * @return array $text_signatures
	 * @todo: 
	 *
	 */
	function get_text_sigs() {
	
		global $ffffff_sigs;
	
		//var_dump( $ffffff_sigs );

		// get them
		return $ffffff_sigs;
		
	}
	
	
	
	
	



//#################################################################







	/*
	===============================================================
	PRIVATE METHODS
	===============================================================
	*/
	
	
	



	/*
	---------------------------------------------------------------
	Object Initialisation
	---------------------------------------------------------------
	*/
	
	/** 
	 * @description: object initialisation
	 * @todo:
	 *
	 */
	function _init() {
		
		// load options array
		$this->cp_options = $this->option_wp_get( 'cp_options', $this->cp_options );
		
		// if we don't have one
		if ( count( $this->cp_options ) == 0 ) {
		
			// if not in backend
			if ( !is_admin() ) {
		
				// init upgrade
				//die( 'Commentpress upgrade required.' );
				
			}
		
		}
		
	}







	/** 
	 * @description: create "title" page
	 * @todo: 
	 *
	 */
	function _create_title_page() {
	
		// define welcome/title page
		$title = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_parent' => 0,
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'to_ping' => '', // quick fix for Windows
			'pinged' => '', // quick fix for Windows
			'post_content_filtered' => '', // quick fix for Windows
			'post_excerpt' => '', // quick fix for Windows
			'menu_order' => 0
		);
		
		// add post-specific stuff
		$title['post_title'] = 'Title Page';
		$title['post_content'] = 'This is your title page. Edit it to suit your needs. It has been automatically set as your homepage but if you want another page as your homepage, set it in <em>Wordpress</em> &#8594; <em>Settings</em> &#8594; <em>Reading</em>.';
		$title['page_template'] = 'welcome.php';

		// Insert the post into the database
		$title_id = wp_insert_post( $title );
		
		// store the option
		$this->option_set( 'cp_welcome_page', $title_id );
		
		// set Wordpress internal page references
		$this->_store_wordpress_option( 'show_on_front', 'page' );
		$this->_store_wordpress_option( 'page_on_front', $title_id );

		// --<
		return $title_id;

	}
	
	
	
	
	


	/** 
	 * @description: create "general comments" page
	 * @todo: 
	 *
	 */
	function _create_general_comments_page() {
	
		// define general comments page
		$general_comments = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_parent' => 0,
			'comment_status' => 'open',
			'ping_status' => 'open',
			'to_ping' => '', // quick fix for Windows
			'pinged' => '', // quick fix for Windows
			'post_content_filtered' => '', // quick fix for Windows
			'post_excerpt' => '', // quick fix for Windows
			'menu_order' => 0
		);

		// add post-specific stuff
		$general_comments['post_title'] = 'General Comments';
		$general_comments['post_content'] = 'Do not delete this page. Page content is generated with a custom template.';
		$general_comments['page_template'] = 'comments-general.php';

		// Insert the post into the database
		$general_comments_id = wp_insert_post( $general_comments );
		
		// store the option
		$this->option_set( 'cp_general_comments_page', $general_comments_id );

		// --<
		return $general_comments_id;

	}
	
	
	
	
	


	/** 
	 * @description: create "all comments" page
	 * @todo: 
	 *
	 */
	function _create_all_comments_page() {
	
		// define all comments page
		$all_comments = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_parent' => 0,
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'to_ping' => '', // quick fix for Windows
			'pinged' => '', // quick fix for Windows
			'post_content_filtered' => '', // quick fix for Windows
			'post_excerpt' => '', // quick fix for Windows
			'menu_order' => 0
		);

		// add post-specific stuff
		$all_comments['post_title'] = 'All Comments';
		$all_comments['post_content'] = 'Do not delete this page. Page content is generated with a custom template.';
		$all_comments['page_template'] = 'comments-all.php';

		// Insert the post into the database
		$all_comments_id = wp_insert_post( $all_comments );
		
		// store the option
		$this->option_set( 'cp_all_comments_page', $all_comments_id );

		// --<
		return $all_comments_id;

	}
	
	
	
	
	


	/** 
	 * @description: create "comments by author" page
	 * @todo: 
	 *
	 */
	function _create_comments_by_author_page() {
	
		// define comments by author page
		$group = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_parent' => 0,
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'to_ping' => '', // quick fix for Windows
			'pinged' => '', // quick fix for Windows
			'post_content_filtered' => '', // quick fix for Windows
			'post_excerpt' => '', // quick fix for Windows
			'menu_order' => 0
		);
		
		// add post-specific stuff
		$group['post_title'] = 'Comments by Commenter';
		$group['post_content'] = 'Do not delete this page. Page content is generated with a custom template.';
		$group['page_template'] = 'comments-by.php';

		// Insert the post into the database
		$group_id = wp_insert_post( $group );
		
		// store the option
		$this->option_set( 'cp_comments_by_page', $group_id );

		// --<
		return $group_id;
	
	}
	
	
	
	
	


	/** 
	 * @description: create "blog" page
	 * @todo: 
	 *
	 */
	function _create_blog_page() {

		// define blog page
		$blog = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_parent' => 0,
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'to_ping' => '', // quick fix for Windows
			'pinged' => '', // quick fix for Windows
			'post_content_filtered' => '', // quick fix for Windows
			'post_excerpt' => '', // quick fix for Windows
			'menu_order' => 0
		);
		
		// add post-specific stuff
		$blog['post_title'] = 'Blog';
		$blog['post_content'] = 'Do not delete this page. Page content is generated with a custom template.';
		$blog['page_template'] = 'blog.php';

		// Insert the post into the database
		$blog_id = wp_insert_post( $blog );
		
		// store the option
		$this->option_set( 'cp_blog_page', $blog_id );

		// set Wordpress internal page reference
		$this->_store_wordpress_option( 'page_for_posts', $blog_id );

		// --<
		return $blog_id;

	}
	
	
	
	
	


	/** 
	 * @description: create "table of contents" page
	 * @todo: 
	 *
	 */
	function _create_toc_page() {
	
		// define TOC page
		$toc = array(
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_parent' => 0,
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'to_ping' => '', // quick fix for Windows
			'pinged' => '', // quick fix for Windows
			'post_content_filtered' => '', // quick fix for Windows
			'post_excerpt' => '', // quick fix for Windows
			'menu_order' => 0
		);
		
		// add post-specific stuff
		$toc['post_title'] = 'Table of Contents';
		$toc['post_content'] = 'Do not delete this page. Page content is generated with a custom template.';
		$toc['page_template'] = 'toc.php';

		// Insert the post into the database
		$toc_id = wp_insert_post( $toc );
		
		// store the option
		$this->option_set( 'cp_toc_page', $toc_id );

		// --<
		return $toc_id;
	
	}
	
	
	
	
	


	/** 
	 * @description: cancels comment paging because CP will not work with comment paging
	 * @todo: 
	 */
	function _cancel_comment_paging() {
	
		// store option
		$this->_store_wordpress_option( 'page_comments', '' );
	
	}
	
	
	
	
	
	
	/** 
	 * @description: resets comment paging option when plugin is deactivated
	 * @todo: 
	 */
	function _reset_comment_paging() {
	
		// reset option
		$this->_reset_wordpress_option( 'page_comments' );
	
	}
	
	
	
	
	
	
	/** 
	 * @description: store Wordpress option
	 * @param string $name the name of the option
	 * @param mixed $value the value of the option
	 * @todo: 
	 */
	function _store_wordpress_option( $name, $value ) {
	
		// set backup option
		add_option( 'cp_'.$name, $this->option_wp_get( $name ) );

		// set the Wordpress option
		$this->option_wp_set( $name, $value );
	
	}
	
	
	
	
	
	
	/** 
	 * @description: reset Wordpress option
	 * @param string $name the name of the option
	 * @todo: 
	 */
	function _reset_wordpress_option( $name ) {
	
		// set the Wordpress option
		$this->option_wp_set( $name, $this->option_wp_get( 'cp_'.$name ) );
	
		// remove backup option
		delete_option( 'cp_'.$name );

	}
	
	
	
	
	
	
//#################################################################







} // class ends






?>