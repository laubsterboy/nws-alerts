<?php
/**
* NWS_Alert Client
*
* @since 1.0.0
*/

class NWS_Alert_Client {

    var $nonce_string = 'nws_alert_nonce';
    var $nonce;

    /*
    * set_ajaxurl
    *
    * Is called when the NWS_Alert plugin is activated and creates necessary database tables and populates them with data
    *
    * @return void
    * @access public
    */
    public static function set_ajaxurl() {
        ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
    }




    /*
    * refresh
    *
    * Is called from the front-end to update the alert data.
    *
    * @return void
    * @access public
    */
    public static function refresh() {
        //check_ajax_referer($this->nonce_string, 'security');
        write_log('test');
        $s_zip = sanitize_text_field($_POST['zip']);
		$s_display = sanitize_text_field($_POST['display']);
		$s_scope = sanitize_text_field($_POST['scope']);
        if (empty($s_zip) || empty($s_display) || empty($s_scope)) die();

        $nws_alert_data = new NWS_Alert($s_zip, null, null, null, $s_scope);

        header('Content-Type: application/json');
        if ($s_display == NWS_ALERT_DISPLAY_BASIC) {
            echo json_encode(array('html' => $nws_alert_data->get_output_html(false)));
        } else {
            echo json_encode(array('html' => $nws_alert_data->get_output_html(true)));
        }

        die();
    }




    /*
    * scripts_styles
    *
    * Enqueues necessary JavaScript and Stylesheet files
    *
    * @return void
    * @access public
    */
    public static function scripts_styles() {
        // Stylesheets
        wp_enqueue_style('nws-alert-css', NWS_ALERT_URL . '/css/nws-alert.css');

        /* JavaScript */
        wp_enqueue_script('nws-alert-js', NWS_ALERT_URL . '/js/nws-alert.js', array('jquery'), null, true);
        wp_enqueue_script('google-map-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=weather&sensor=false', false, null, false);
    }
}