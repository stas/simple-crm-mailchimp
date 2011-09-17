<?php
/**
 * Class extends user profile fields
 */
class SCRM_SYNC {
    /**
     * Static constructor
     */
    function init() {
        add_action( 'scrm_options_screen_updated', array( __CLASS__, 'screen_update' ) );
        add_action( 'scrm_options_screen_updated', array( __CLASS__, 'screen_sync' ) );
        add_action( 'scrm_options_screen', array( __CLASS__, 'screen' ) );
    }
    
    /**
     * Options screen
     */
    function screen() {
        $api_key = get_option( 'scrm_mc_api_key' );
        $mc_api = new MCAPI( $api_key );
        
        $vars['path'] = SCRM_MC_ROOT . '/includes/templates/';
        $vars['api_key'] = get_option( 'scrm_mc_api_key', '' );
        $vars['list_id'] = get_option( 'scrm_mc_list_id', '' );
        $vars['counted_users'] = count( get_users() );
        $vars['lists'] = null;
        if( $vars['api_key'] )
            $vars['lists'] = $mc_api->lists();
        template_render( 'mailchimp_options', $vars );
    }
    
    /**
     * Options screen
     */
    function screen_update() {
        if( isset( $_POST['scrm_mc_nonce'] ) && wp_verify_nonce( $_POST['scrm_mc_nonce'], 'scrm_mc' ) ) {
            if( isset( $_POST['scrm_mc'] ) && !empty( $_POST['scrm_mc'] ) ) {
                $api_key = sanitize_text_field( $_POST['scrm_mc']['api_key'] );
                $list_id = sanitize_text_field( $_POST['scrm_mc']['list_id'] );
                update_option( 'scrm_mc_api_key', $api_key );
                if( !empty( $api_key ) )
                    update_option( 'scrm_mc_list_id', $list_id );
                else
                    update_option( 'scrm_mc_list_id', '' );
            }
        }
    }
    
    /**
     * Catch the screen sync query
     */
    function screen_sync() {
        $flash = __( 'Synced with MailChimp.', 'scrm_mc' );
        if( isset( $_POST['scrm_mc_nonce'] ) && wp_verify_nonce( $_POST['scrm_mc_nonce'], 'scrm_mc_sync' ) )
            if( self::sync( intval( $_POST['scrm_mc_start'] ), intval( $_POST['scrm_mc_end'] ) ) )
                add_filter( 'scrm_screen_flash', create_function( '', "return '$flash';" ) );
    }
    
    /**
     * Fire up the sync process
     */
    function sync( $start = 0, $end = null ) {
        $api_key = get_option( 'scrm_mc_api_key' );
        $list_id = get_option( 'scrm_mc_list_id' );
        
        if( !$api_key || !$list_id )
            return;
        
        $mc_api = new MCAPI( $api_key );
        
        $users = get_users();
        $crm_fields = SCRM::get_fields();
        
        if( $end == null )
            $end = count( $users );
        
        $profiles = array();
        $counter = 0;
        foreach ( $users as $u ) {
            if( ( $counter >= $start ) && ( $counter <= $end ) ) {
                $profile = get_userdata( $u->ID );
                foreach( $crm_fields as $f )
                    $profile->$f['name'] = get_user_meta( $u->ID, $f['name'], true );
                $profiles[] = $profile;
            }
            $counter++;
        }
        
        if( !empty( $profiles ) )
            foreach( $profiles as $p ) {
                $merge_vars = array(
                    'FNAME' => $p->user_firstname,
                    'LNAME' => $p->user_lastname,
                    'MMERGE3' => $p->user_url,
                );
                
                // Start with MERGE4
                $fpos = 4;
                foreach( $crm_fields as $f ) {
                    $merge_vars['MMERGE' . $fpos] = $p->$f['name'];
                    $fpos++;
                }
                
                $mc_api->listSubscribe(
                    $list_id,
                    $p->user_email,
                    $merge_vars,
                    'html',
                    false,
                    true,
                    true,
                    false
                );
            }
        
        return true;
    }
}
?>
