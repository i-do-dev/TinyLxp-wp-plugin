<?php

/**
 * Class TL_Admin_Menu
 */

class TL_Admin_Menu 
{
    /**
	 * Array of submenu items.
	 *
	 * @var array
	 */
	protected $menu_items = array();

    /**
     * @var null
     */
   protected static $_instance = null;


    public function __construct() {
		//add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
    * Get Instance
    */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
	 * Register for menu for admin
	 */
	public function admin_menu() {
		// add_menu_page(
		// 	__( 'Learning Management System', 'tinylms' ),
		// 	'Tiny LMS',
		// 	'manage_options',
		// 	'tiny_lms',
		// 	'',
		// 	'dashicons-welcome-learn-more',
		// 	'3.14'
		// );
    }
}
