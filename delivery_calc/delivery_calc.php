<?php
/**
 * @package Delivery_Calc
 * @version 0.0.1
 */
/*
Plugin Name: Delivery Calc
Plugin URI: https://github.com/PashaWNN/delivery_calculator_wordpress/
Description: Calculates. :)
Author: Pavel Shishmarev
Version: 0.0.1
Author URI: http://pashawnn.github.io/
*/


class DeliveryCalc {


    public function __construct() {
    
        add_action('wp_ajax_calculate_delivery', array($this, 'calculate_delivery_callback'));
        add_action('wp_ajax_nopriv_calculate_delivery', array($this, 'calculate_delivery_callback'));
        add_action('woocommerce_after_single_product_summary', array($this, 'calc_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
    }
    
    function enqueue_assets() {
    
        wp_register_script('delcalc_jq', plugins_url('js/lib/jquery-1.11.1.min.js', __FILE__), array(), null, false);
        wp_enqueue_script('delcalc_jq');
        wp_register_script('delcalc_kladr', plugins_url('js/jquery.kladr.min.js', __FILE__), array(), null, false);
        wp_enqueue_script('delcalc_kladr');
        wp_register_script('delcalc_main', plugins_url('js/main.js', __FILE__), array(), null, true);
        wp_enqueue_script('delcalc_main');
        
        wp_register_style('delcalc_css', plugins_url('css/simple.css', __FILE__), array(), '20190120', 'all');
        wp_enqueue_style('delcalc_css');
        wp_register_style('delcalc_kladr_css', plugins_url('css/jquery.kladr.min.css', __FILE__), array(), '20190120', 'all');
        wp_enqueue_style('delcalc_kladr_css');
        
    }

    function calc_form() {
        $weight = wc_get_product()->get_weight();
        if ($weight) {
            include_once (plugin_dir_path( __FILE__ ) . '/tmpl/calc.php');
        }
    }
    
    function calculate_delivery_callback() {
        $DELLIN_APP_KEY = "DF8DC7DC-DB1A-4C4A-A537-D8A51C77D770";
        $volume = 1.0;
        $weight = floatval($_POST['weight']);
        $derival = $_POST['derival'] == 'true' ? true : false;
        $arrival = $_POST['arrival'] == 'true' ? true : false;
        $from_kladr = $this->normalize_kladr($_POST['from_kladr']);
        $to_kladr = $this->normalize_kladr($_POST['to_kladr']);
        $payload = array(
            'appkey' => $DELLIN_APP_KEY,
            'arrivalPoint' => $to_kladr,
            'derivalPoint' => $from_kladr,
            'arrivalDoor' => $arrival,
            'derivalDoor' => $derival,
            'sizedWeight' => $weight,
            'sizedVolume' => $volume
        );
        
        $url = 'https://api.dellin.ru/v1/public/calculator.json';
        $data = wp_remote_post($url, array(
            'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'      => json_encode($payload),
            'method'    => 'POST',
            'timeout'   => 15,
        ));
        if (!(is_wp_error($data))) {
            $response = json_decode($data['body'], true);
            $t_price = $response['price'];
            $d_price = $response['derival']['price'];
            $a_price = $response['arrival']['price'];
            $i_price = $response['intercity']['price'];
            $payload = json_encode(array(
                'total' => $t_price,
                'derival' => $d_price,
                'arrival' => $a_price,
                'intercity' => $i_price
            ));
            echo $payload;
        } else { echo $data->get_error_message(); }
        wp_die();
    }
    
    function normalize_kladr($kladr) {
        $result = ltrim($kladr, '0');
        $result = str_pad($result , 25, '0');
        return $result;
    }
    
}

$masterpage_obj = new DeliveryCalc ();

?>
