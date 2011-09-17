<?php
/*
Plugin Name: Simple CRM MailChimp Addon
Plugin URI: http://wordpress.org/extend/plugins/simple-crm-mailchimp/
Description: Add MailChimp Synchronization to Simple CRM
Author: Stas Sușcov
Version: 0.2
Author URI: http://stas.nerd.ro/
*/

define( 'SCRM_MC_ROOT', dirname( __FILE__ ) );
define( 'SCRM_MC_WEB_ROOT', WP_PLUGIN_URL . '/' . basename( SCRM_MC_ROOT ) );

if ( !class_exists( 'MCAPI' ) )
    require_once SCRM_MC_ROOT . '/includes/MCAPI.class.php';
require_once SCRM_MC_ROOT . '/includes/crm_sync.class.php';

/**
 * i18n
 */
function scrm_mc_textdomain() {
    load_plugin_textdomain( 'scrm_mc', false, basename( SCRM_MC_ROOT ) . '/languages' );
}
add_action( 'init', 'scrm_mc_textdomain' );

SCRM_SYNC::init();

?>
