<?php
/**
 * Plugin Name: Encryption for Ninja Forms
 * Description: Encrypts selected fields on form submission
 * Author: Sightfactory
 * Author URI: https://www.sightfactory.com
 * Plugin URI: https://www.sightfactory.com
 * Version: 1.0.0
 */
if ( !defined( 'ABSPATH' ) ) die( '-1' );

add_filter( 'ninja_forms_submit_data', 'vwpenf_ninja_forms_submit_data' );

function vwpenf_ninja_forms_submit_data( $form_data ) {
	
	foreach( $form_data[ 'fields' ] as $key => $field ) {// Field settigns, including the field key and value.
		if(stristr($field['key'],'encrypted')) { 
			
			$key_size = 32; // 256 bits
			//$encryption_key = openssl_random_pseudo_bytes($key_size, $strong);
			$encryption_key = "PAQPVbXyGnPn9QhuD4ekfVXh4GGztbuQ";
			$iv = "XJpYdLhMcf4QHQ6j";

			$iv_size = 16; // 128 bits
			//$iv = openssl_random_pseudo_bytes($iv_size, $strong);  
			$enc_val = openssl_encrypt(
			pkcs7_pad($form_data['fields'][$key]['value'], 16), // padded data
			'AES-256-CBC',        // cipher and mode
			$encryption_key,      // secret key
			0,                    // options (not used)
			$iv                   // initialisation vector
			);  
			$form_data['fields'][$key]['value'] = $enc_val; // Update the submitted field value.
		}
		
  }
  
  //$form_settings = $form_data[ 'settings' ]; // Form settings.
  
  //$extra_data = $form_data[ 'extra' ]; // Extra data included with the submission.
  
  return $form_data;
}

    // define the nf_subs_csv_field_value callback 
    function filter_nf_subs_csv_field_value( $user_value, $field_id ) { 
	
	
	try {
		$encryption_key = "PAQPVbXyGnPn9QhuD4ekfVXh4GGztbuQ";
		$iv = "XJpYdLhMcf4QHQ6j";

        // make filter magic happen here... 
				//if($field_id == 37 ) {

	$dec_val = pkcs7_unpad(openssl_decrypt(
    $user_value,
    'AES-256-CBC',
    $encryption_key,
    0,
    $iv
	));
        return $dec_val; 
	}
	
	catch(Exception $e) {
		return $user_value;
	}
		
		
    }
             
    // add the filter 
    add_filter( 'ninja_forms_subs_export_pre_value', 'filter_nf_subs_csv_field_value', 10, 2 ); 

function pkcs7_pad($data, $size)
{
    $length = $size - strlen($data) % $size;
    return $data . str_repeat(chr($length), $length);
}   

function pkcs7_unpad($data)
{	
    return substr($data, 0, -ord($data[strlen($data) - 1]));
}




    // define the nf_subs_table_qv callback 
    function filter_nf_subs_table_qv( $qv, $form_id ) { 
        // make filter magic happen here... 
        print_r($qv);
		exit;
    }; 
             
    // add the filter 
    add_filter( 'nf_subs_table_qv', 'filter_nf_subs_table_qv', 10, 2 ); 
	
	
	
	
	
	
	if ( in_array( 'ninja-forms/ninja-forms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// Create turnstile field for Ninja Forms
	add_filter( 'ninja_forms_register_fields', function( $fields ) {
		$fields['vwpnfencryption'] = new VWPNFEncryption;
		return $fields;
	} );

    

	class VWPNFEncryption extends NF_Abstracts_Input {			
		protected $_name = 'vwpnfencryption';
		protected $_nicename = 'Encrypted';
		protected $_section = 'misc';
		protected $_icon = 'eye';
		protected $_type = 'textbox';
		protected $_templates = array( 'textbox', 'input' );
	    protected $_settings_all_fields = array(
        'key', 'label', 'label_pos', 'required', 'default', 'placeholder', 'classes', 'input_limit_set' , 'manual_key', 'disable_input', 'admin_label', 'help', 'description'
		);
		public function __construct() {
			parent::__construct();
			$this->_nicename = __( 'Encrypted', 'ninja-forms' );
			$this->_settings[ 'label' ][ 'width' ] = 'full';
		}
		public function get_parent_type()
		{
			return parent::get_type();
		}
	}

	// Set value for vwpencrypted field
	add_filter( 'ninja_forms_render_default_value', 'vwpenf_default_value_vwpnfencryption' , 10 , 3);
	function vwpenf_default_value_vwpnfencryption( $default_value, $field_type, $field_settings ) {
		
		if ( 'vwpnfencryption' == $field_type && in_array( 'vwpnfencryption', $field_settings ) ) {
			$default_value = '';
		}
		return esc_html($default_value);
	}
	
	
	}

?>
