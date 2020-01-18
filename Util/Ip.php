<?php 

/**
 * Ip
 *
 * @author Satjan
 */
class Ip {
   
    public static function getClientIp() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}