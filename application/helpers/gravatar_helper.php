<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Gravatar Helper
 */
function get_gravatar( $email, $s = 40, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {

    $url = '//www.gravatar.com/avatar/';

    $url .= md5( strtolower( trim( $email ) ) );

    $url .= "?s=$s&d=$d&r=$r";

    if ( !$url ) {
      $url = base_url()."files/media/no-pic.png";
    }

    return $url;

}
function get_user_pic($pic = FALSE, $email = FALSE){
    if($pic != 'no-pic.png'){
                                    return base_url()."files/media/".$pic;
                                    }else{
                                    return get_gravatar($email);
                                    }
                                
}