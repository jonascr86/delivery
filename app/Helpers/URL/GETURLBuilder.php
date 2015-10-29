<?php
namespace Delivery\Helpers\URL;


class GETURLBuilder extends URLBuilder {

    public function doAction($action, Array $params = array()) {
        $actionKey = $this->router->getGetVar();

        $urlaction = ROOT_URL . "?{$actionKey}={$action}";

        if (!empty($params)) {
            $params_key_value = array();

            array_walk($params, function($value, $key) use(&$params_key_value) {
                $params_key_value[] = $key . '=' . urlencode($value);
            });

            $urlaction .= '&' . implode('&', $params_key_value);
        }

        return $urlaction;
    }

}
