<?php
/*
Plugin Name: Account Manager
Plugin URI: http://signpostmarv.name/account-manager/
Description: Adds support for the Mozilla Labs "Account Manager" to WordPress
Version: 0.1
Author: SignpostMarv Martin
Author URI: http://signpostmarv.name/
*/

class MozLabs_Account_Manager
{
	function amcd_url(){
		return site_url('.amcd');
	}

	function action_init(){
		header('X-Account-Management: ' . $this->amcd_url());
		$this->_header_am_status();
		if(site_url($_SERVER['REQUEST_URI']) == $this->amcd_url()){
			$doc = json_encode(apply_filters('mozlabs_amcd',array('methods'=>array())));
			$ETag = sha1($doc);
			if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $ETag){
				header('x',true,304);
				exit;
			}
			header('Content-Type: application/json');
			header('Content-Length: ' . strlen($doc));
			header('ETag: ' . $ETag);
			die($doc);
		}else if(site_url($_SERVER['REQUEST_URI']) == site_url('.amcd-logout')){
			wp_logout();
			wp_safe_redirect('wp-login.php?loggedout=true');
		}
	}

	function _header_am_status($username=''){
		$logged_in = false;
		if($username == ''){
			if(wp_get_current_user()->ID != 0){
				$logged_in = true;
				$username = wp_get_current_user()->display_name;
			}
		}
		if($logged_in == true){
			header(sprintf('X-Account-Management-Status: active; name="%1$s"',sanitize_user($username)));
		}else{
			header('X-Account-Management-Status: none');
		}
	}

	function action_wp_login($username){
		$this->_header_am_status($username);
	}

	function action_wp_logout(){
		header('X-Account-Management-Status: none');
	}

	function filter_mozlabs_amcd($json_array){
		$json_array['methods']['username-password-form'] = array(
			'connect' => array(
				'method' => 'POST',
				'path' => site_url('wp-login.php', 'login_post'),
				'params' => array(
					'username' => 'log',
					'password' => 'pwd',
				),
			),
			'disconnect' => array(
				'method'    => 'POST',
				'path'      => site_url('.amcd-logout'),
			),
		);
		return $json_array;
	}
}
$MozLabs_Account_Manager = new MozLabs_Account_Manager;
add_filter('mozlabs_amcd',array($MozLabs_Account_Manager,'filter_mozlabs_amcd'));
add_action('init',array($MozLabs_Account_Manager,'action_init'));
add_action('wp_login',array($MozLabs_Account_Manager,'action_wp_login'));
add_action('wp_logout',array($MozLabs_Account_Manager,'action_wp_logout'));
?>