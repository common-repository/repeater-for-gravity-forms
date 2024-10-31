<?php
/**
 * Plugin Name: Repeater for Gravity Forms
 * Description: The add-on that allows specified groups of fields to be repeated by the user.
 * Plugin URI: https://add-ons.org/plugin/gravity-forms-repeater-fields/
 * Version: 2.1.6
 * Author: add-ons.org
 * Text Domain: repeater-for-gravityforms
 * Domain Path: /languages
 * Author URI: https://add-ons.org/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
define( 'SUPERADDONS_GF_REPEATER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SUPERADDONS_GF_REPEATER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
add_action( 'gform_loaded', array( 'Superaddons_Grepeater_Field_AddOn_Init', 'load' ), 5 );
class Superaddons_Grepeater_Field_AddOn_Init {
    public static function load() {
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
        include SUPERADDONS_GF_REPEATER_PLUGIN_PATH."add_on.php";
        GFAddOn::register( 'Superaddons_Grepeater_Field_Addon' );
        include SUPERADDONS_GF_REPEATER_PLUGIN_PATH."superaddons/check_purchase_code.php";
        new Superaddons_Check_Purchase_Code( 
            array(
                "plugin" => "repeater-for-gravity-forms/repeater-for-gravity-forms.php",
                "id"=>"1540",
                "pro"=>"https://add-ons.org/plugin/gravity-forms-repeater-fields/",
                "plugin_name"=> "Repeater Field For Gravity Forms",
                "document"=>"https://add-ons.org/document-gravity-forms-repeater-fields/"
            )
        );
    }
}
if(!class_exists('Superaddons_List_Addons')) {  
    include SUPERADDONS_GF_REPEATER_PLUGIN_PATH."add-ons.php"; 
}