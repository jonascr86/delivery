<?php

namespace Delivery\Utils;

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

    static function validaCPF($cpf) { 
        $cpf = str_pad(@ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
        $cpf = self::removeFormatacao($cpf);
        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }


    static function calculaCNPJ($CampoNumero) {
        $RecebeCNPJ = ${"CampoNumero"};
        $RecebeCNPJ = self::removeFormatacao($RecebeCNPJ);
        
        $s = "";
        for ($x = 1; $x <= strlen($RecebeCNPJ); $x = $x + 1) {
            $ch = substr($RecebeCNPJ, $x - 1, 1);
            if (ord($ch) >= 48 && ord($ch) <= 57) {
                $s = $s . $ch;
            }
        }

        $RecebeCNPJ = $s;
        if (strlen($RecebeCNPJ) != 14) {
            return false;
        } else
        if ($RecebeCNPJ == "00000000000000") {
            $then;
            return false;
        } else {
            $Numero[1] = intval(substr($RecebeCNPJ, 1 - 1, 1));
            $Numero[2] = intval(substr($RecebeCNPJ, 2 - 1, 1));
            $Numero[3] = intval(substr($RecebeCNPJ, 3 - 1, 1));
            $Numero[4] = intval(substr($RecebeCNPJ, 4 - 1, 1));
            $Numero[5] = intval(substr($RecebeCNPJ, 5 - 1, 1));
            $Numero[6] = intval(substr($RecebeCNPJ, 6 - 1, 1));
            $Numero[7] = intval(substr($RecebeCNPJ, 7 - 1, 1));
            $Numero[8] = intval(substr($RecebeCNPJ, 8 - 1, 1));
            $Numero[9] = intval(substr($RecebeCNPJ, 9 - 1, 1));
            $Numero[10] = intval(substr($RecebeCNPJ, 10 - 1, 1));
            $Numero[11] = intval(substr($RecebeCNPJ, 11 - 1, 1));
            $Numero[12] = intval(substr($RecebeCNPJ, 12 - 1, 1));
            $Numero[13] = intval(substr($RecebeCNPJ, 13 - 1, 1));
            $Numero[14] = intval(substr($RecebeCNPJ, 14 - 1, 1));

            $soma = $Numero[1] * 5 + $Numero[2] * 4 + $Numero[3] * 3 + $Numero[4] * 2 + $Numero[5] * 9 + $Numero[6] * 8 + $Numero[7] * 7 +
                    $Numero[8] * 6 + $Numero[9] * 5 + $Numero[10] * 4 + $Numero[11] * 3 + $Numero[12] * 2;

            $soma = $soma - (11 * (intval($soma / 11)));

            if ($soma == 0 || $soma == 1) {
                $resultado1 = 0;
            } else {
                $resultado1 = 11 - $soma;
            }
            if ($resultado1 == $Numero[13]) {
                $soma = $Numero[1] * 6 + $Numero[2] * 5 + $Numero[3] * 4 + $Numero[4] * 3 + $Numero[5] * 2 + $Numero[6] * 9 +
                        $Numero[7] * 8 + $Numero[8] * 7 + $Numero[9] * 6 + $Numero[10] * 5 + $Numero[11] * 4 + $Numero[12] * 3 + $Numero[13] * 2;
                $soma = $soma - (11 * (intval($soma / 11)));
                if ($soma == 0 || $soma == 1) {
                    $resultado2 = 0;
                } else {
                    $resultado2 = 11 - $soma;
                }
                if ($resultado2 == $Numero[14]) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    static function removeFormatacao($subject){
        $busca = array('(', ')', '-', '.');
        $replace = "";
        return str_replace($busca, $replace, $subject);
    }
}
