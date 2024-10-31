<?php
if ( ! class_exists( 'GFForms' ) ) {
	die();
}
GFForms::include_addon_framework();
class Superaddons_Grepeater_Field_Addon extends GFAddOn{
	protected $_version = "1.0";
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'gravityforms-repeater';
	protected $_path = 'gravityforms-repeater/index.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Repeater Fields Add-On';
	protected $_short_title = 'Gravity Repeater';
	private static $_instance = null;
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function init_admin() {
        parent::init_admin();
        // add tasks or filters here that you want to perform only in admin
    }
    public function init_frontend() {
        parent::init_frontend();
        // add tasks or filters here that you want to perform only in the front end
    }
	function type( $position, $form_id ) {   
		$check_pro = get_option( '_redmuber_item_1540');    
	    if ( $position == 1550 ) {
	        ?>
	      <li class="field_field_repeater_title_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Title', 'gravityforms-repeater'); ?>
			</label>
			<input type="text" id="repeater_title" placeholder="Person" value="" onchange="SetFieldProperty('repeater_title', this.value);">
			<?php esc_html_e("An optional title before each row of the repeater",'gravityforms-repeater') ?>
		</li>
		<?php if($check_pro == "ok") {?>
	     <li class="field_field_repeater_initial_rows_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Initial Rows', 'gravityforms-repeater'); ?>
			</label>
			<input type="text" id="repeater_initial_rows" placeholder="0" value="" onchange="SetFieldProperty('repeater_initial_rows', this.value);">
			<?php esc_html_e("The number of rows at start, if empty no rows will be created",'gravityforms-repeater') ?>
		</li>
		<li class="field_field_repeater_initial_rows_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Map field with Initial Rows', 'gravityforms-repeater'); ?>
			</label>
			<input type="text" id="repeater_initial_rows_map" placeholder="" value="" onchange="SetFieldProperty('repeater_initial_rows_map', this.value);">
			<?php esc_html_e("Map field with Initial Rows (ID field)",'gravityforms-repeater') ?>
		</li>
		<li class="field_field_repeater_max_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Limit', 'gravityforms-repeater'); ?>
			</label>
			<input type="text" id="repeater_max" placeholder="9999" value="" onchange="SetFieldProperty('repeater_max', this.value);">
			<?php esc_html_e("Max number of rows applicable by the user, leave empty for no limit",'gravityforms-repeater') ?>
		</li>
		<?php }else{ ?>
			<li class="field_field_repeater_initial_rows_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Initial Rows', 'gravityforms-repeater'); ?>
			</label>
			<div class="pro_disable">
				<input type="text" id="repeater_initial_rows" placeholder="Upgrade to pro version" value="" readonly="true">
			</div>
			<?php esc_html_e("The number of rows at start, if empty no rows will be created",'gravityforms-repeater') ?>
		</li>
		<li class="field_field_repeater_initial_rows_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Map field with Initial Rows', 'gravityforms-repeater'); ?>
			</label>
			<div class="pro_disable">
				<input type="text" id="repeater_initial_rows_map" placeholder="Upgrade to pro version" value="" readonly="true" >
			</div>
			<?php esc_html_e("Map field with Initial Rows (ID field)",'gravityforms-repeater') ?>
		</li>
		<li class="field_field_repeater_max_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Limit', 'gravityforms-repeater'); ?>
			</label>
			<input type="text" id="repeater_max" placeholder="5" value="" onchange="SetFieldProperty('repeater_max', this.value);">
			<?php esc_html_e("Max number of rows applicable by the user, leave empty for no limit (Free version limit = 5 )",'gravityforms-repeater') ?>
		</li>
		<?php } ?>
		<li class="field_field_repeater_end_text_setting field_setting">
			<label class="section_label">
				<?php esc_html_e('Text button', 'gravityforms-repeater'); ?>
			</label>
			<input type="text" id="field_repeater_end_text" placeholder="Add more" value="" onchange="SetFieldProperty('field_repeater_end_text', this.value);">
			<?php esc_html_e("add button text",'gravityforms-repeater') ?>
		</li>
	        <?php
	    }
	}
	function editor_script(){
    ?>
	    <script type='text/javascript'>
	    	( function( $ ) { 
	    	"use strict";     
			  jQuery(document).ready(function($) {
			        jQuery(document).on('gform_load_field_settings', function(event, field, form){  
			            jQuery('#repeater_title').val( field.repeater_title);
			            jQuery('#repeater_initial_rows').val( field.repeater_initial_rows);
			            jQuery('#repeater_max').val( field.repeater_max);
			            jQuery('#repeater_initial_rows_map').val( field.repeater_initial_rows_map);
			            jQuery('#field_repeater_end_text').val( field.field_repeater_end_text);
			        });
			  });
			} )( jQuery );       
	    </script>
	    <?php
	}
    public function pre_init() {
		parent::pre_init();
		add_filter( "gform_input_mask_script",array($this,"gform_input_mask_script"),10,4 );
		add_action( 'gform_field_standard_settings', array($this,'type'), 10, 2 );
		add_action( 'gform_editor_js', array($this,'editor_script') );
		include SUPERADDONS_GF_REPEATER_PLUGIN_PATH."fields/repeater_field.php";
		include SUPERADDONS_GF_REPEATER_PLUGIN_PATH."fields/repeater_start_field.php";
		add_filter( 'gform_pre_validation', array( "Superaddons_GFRepeater_Field", 'remove_validation' ) );
		add_action( 'gform_enqueue_scripts', array($this,"add_data"),10,2 );
	}
	function gform_input_mask_script($script_str,$form_id,$field_id,$mask){
		$input_mask = get_option( "yeeaddons_gf_input_mask",array());
		$input_mask["input_".$form_id."_".$field_id] = esc_js( $mask );
		update_option( "yeeaddons_gf_input_mask",$input_mask );
		return $script_str;
	}
	function add_data($form, $is_ajax ){
		$input_mask = get_option( "yeeaddons_gf_input_mask",array());
		wp_enqueue_script( 'gf_repeater', SUPERADDONS_GF_REPEATER_PLUGIN_URL."libs/wp_repeater.js", array('jquery',"gform_masked_input"),time());
		wp_localize_script("gf_repeater","yeeaddons_gf_repeater_data",array("input_mask"=>($input_mask)));
	}
	public function styles() {
		$styles = array(
			array(
				'handle'  => 'repeater_icon',
				'src'     => SUPERADDONS_GF_REPEATER_PLUGIN_URL."libs/css/repeatericons.css",
				'version' => time(),
				'enqueue' => array(
					array( 'field_types' => array( 'repeater_end' ) )
				)
			),
			array(
				'handle'  => 'gf_repeater',
				'src'     => SUPERADDONS_GF_REPEATER_PLUGIN_URL."libs/wp_repeater.css",
				'version' => time(),
				'enqueue' => array(
					array( 'field_types' => array( 'repeater_end' ) )
				)
			),
		);
		return array_merge( parent::styles(), $styles );
	}
}