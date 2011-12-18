<?php

/**
 * Returns Facebook ID
 * @global WP_Gianism $gianism
 * @param int $user_id
 * @return string
 */
function get_facebook_id($user_id){
	global $gianism;
	return get_user_meta($user_id, '_wpg_facebook_id', true);
}

/**
 * Returns if user is connected with particular web service.
 * @global int $user_ID
 * @param string $service
 * @return boolean 
 */
function is_user_connected_with($service){
	global $user_ID;
	switch($service){
		case 'facebook':
			return (boolean) get_facebook_id($user_ID);
			break;
		default:
			return false;
			break;
	}
}

/**
 * Get user object by credencial
 * @global wpdb $wpdb
 * @param string $service
 * @param mixed $credential
 * @return Object 
 */
function get_user_by_service($service, $credential){
	global $wpdb;
	switch($service){
		case 'facebook':
			$user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'wpg_facebook_id' AND meta_value = %s", $credential));
			if($user_id){
				return new WP_User($user_id);
			}else{
				return null;
			}
			break;
		default:
			return null;
			break;
	}
}

/**
 * Returns if current user liked or not.
 * 
 * You can this function only on Facebook fan page tab.
 * 
 * @global WP_Gianism $gianism
 * @return boolean
 */
function is_user_like_me(){
	global $gianism;
	return $gianism->fb->is_user_like_me_on_fangate();
}

/**
 * Returns if current user has wordpress account.
 * 
 * You can this function only on Facebook fan page tab.
 * 
 * @global WP_Gianism $gianism
 * @return boolean
 */
function is_user_registered_with($service){
	global $gianism, $wpdb;
	switch($service){
		case 'facebook':
			$user_id = get_user_id_on_fangate();
			$sql = <<<EOS
				SELECT user_id FROM {$wpdb->usermeta}
				WHERE meta_key = 'wpg_facebook_id' AND meta_value = %s
EOS;
			return $wpdb->get_var($wpdb->prepare($sql, $signed['user_id']));
			break;
		default:
			return false;
			break;
	}
}

/**
 * Returns facebook id on fan gate.
 * @global WP_Gianism $gianism
 * @return stiring|boolean
 */
function get_user_id_on_fangate(){
	global $gianism;
	return $gianism->fb->is_registered_user_on_fangate();
}