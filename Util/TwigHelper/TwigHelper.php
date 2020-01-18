<?php

use Twig_Environment;
use Twig_Extension;
use Twig_Filter_Method;
use Twig_Loader_String;
use Twig_SimpleFunction;

/**
 * Description of CustomTwigHelper
 *
 * @author Satjan
 */
class CustomTwigHelper extends Twig_Extension {

    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('popover', array($this, 'setPopover')),
            new Twig_SimpleFunction('array_unset', array($this, 'arrayUnset')),
            new Twig_SimpleFunction('array_unset_by_val', array($this, 'arrayUnsetByValue')),
            new Twig_SimpleFunction('drawAssetsByType', array($this, 'drawAssetsByType')),
            new Twig_SimpleFunction('footerGetContents', array($this, 'footerGetContents')),
            new Twig_SimpleFunction('findDateDiff', array($this, 'findDateDiff')),
            new Twig_SimpleFunction('getMnDayName', array($this, 'getMnDayName')),
        );
    }

    public function getFilters() {
        return array(
            'compile' => new Twig_Filter_Method($this, 'compile',
                    array(
                'needs_environment' => true,
                'needs_context'     => true,
                'is_safe'           => array('compile' => true)
                    )),
        );
    }

    public function getMnDayName($dayName) {
        $dayNames = array(
            'Monday'    => 'Даваа',
            'Tuesday'   => 'Мягмар',
            'Wednesday' => 'Лхагва',
            'Thursday'  => 'Пүрэв',
            'Friday'    => 'Баасан',
            'Saturday'  => 'Бямба',
            'Sunday'    => 'Ням',
        );
        return $dayNames[$dayName];
    }

    public function findDateDiff($datetime, $full = false) {
        $now  = new DateTime;
        $ago  = $datetime;
        $diff = $now->diff($ago);

        if ($diff->y > 0 || $diff->m > 0) {
            return $ago->format('Y-m-d H:m:s');
        } else {
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'жил',
                'm' => 'сар',
                'w' => 'долоо хоног',
                'd' => 'өдөр',
                'h' => 'цаг',
                'i' => 'минут',
                's' => 'секунд',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) {
                $string = array_slice($string, 0, 1);
                return $string ? implode(' ', $string) : 'яг одоо';
            } else {
                return $string ? implode(' ', $string) . '-н өмнө' : 'яг одоо';
            }
        }
    }

    public function drawAssetsByType($assets, $type) {
        $assetResult = '';
        if (!is_null($assets)) {
            if ($type == 'css') {
                foreach ($assets as $asset) {
                    $assetResult .= "<link href='/" . $asset . "' rel='stylesheet' type='text/css'/>";
                }
            } else if ($type == 'js') {
                foreach ($assets as $asset) {
                    $assetResult .= "<script src='/" . $asset . "'></script>";
                }
            }
        }

        return $assetResult;
    }

    public function setPopover($text, $length = 20, $placement = 'top') {
        if (strlen($text) > $length) {
            return '<span class="popovers" data-content="' . $text .
                    '" data-placement="' . $placement . '" data-trigger="hover">'
                    . mb_substr($text, 0, $length, "utf-8") . '...</span>';
        } else {
            return $text;
        }
    }

    /**
     * Delete a key of an array
     *
     * @param array  $array Source array
     * @param string $key   The key to remove
     *
     * @return array
     */
    public function arrayUnset($array, $key) {
        unset($array[$key]);

        return $array;
    }

    /**
     * Delete a key of an array by avlue
     *
     * @param array  $array Source array
     * @param string $value   The key to remove
     *
     * @return array
     */
    public function arrayUnsetByValue($array, $value) {
        if (($key = array_search($value, $array)) !== false) {
            unset($array[$key]);
        }

        return $array;
    }

    public function compileString(Twig_Environment $environment, $context, $string) {
        $environment->setLoader(new Twig_Loader_String());
        return $environment->render($string, $context);
    }

    public function getName() {
        return 'custom twig helper';
    }

}
