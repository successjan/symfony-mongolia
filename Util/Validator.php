<?php

use DateTime;

/**
 * Description of Validator
 *
 * @author Satjan
 */
class Validator {

    public static function isEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function isWebsite($website) {
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
            return false;
        }
        return true;
    }

    public static function validMin($str, $min) {
        $len = mb_strlen($str);
        if ($len < $min) {
            return false;
        }
        return true;
    }

    public static function validMax($str, $max) {
        $len = mb_strlen($str);
        if ($len > $max) {
            return false;
        }
        return true;
    }

    public static function validDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public static function isPhone($phone) {
        if (!preg_match("/^(\+\d{3}(\-){0,1}){0,1}(\d{8})$/", $phone)) {
            return false;
        }
        return true;
    }

    public static function isRegisterNumber($value) {
        if (!preg_match("/^[А-ЯӨҮЁ]{2}(\d){8}$/u", mb_strtoupper($value))) {
            return false;
        }
        return true;
    }

}
