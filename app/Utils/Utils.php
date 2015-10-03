<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author jonascr86
 */
namespace Delivery\Utils;

class Utils {

        
    public static function _get($key) {
        if (isset($_GET[$key])) {
            return filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
        }

        return null;
    }
    
    static function displayErrorMessage($msg) {
        echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
    }

    static function displaySuccessMessage($msg) {
        echo '<div class="alert alert-success" role="alert">' . $msg . '</div>';
    }

    static function displayNotices() {
        $successMsg = Utils::_get('successMsg');
        $errorMsg = Utils::_get('errorMsg');
        if ($errorMsg)
            Utils::displayErrorMessage($errorMsg);
        else if ($successMsg)
            Utils::displaySuccessMessage($successMsg);
    }

}
