<?php

class UniteBridge_Controller_Base extends Core_Controller_Action_Standard
{
    private $params = array();

    public function init () {
        header('Content-Type: application/json');
        $bypass = ($_SERVER['HTTP_HOST'] == 'localhost:8080');
        if (!$bypass) {
            if (empty($_SERVER['HTTP_SE_UNITE_TOKEN'])) {
                $this->error('Missing auth token.');
            }
            $settings = Engine_Api::_()->getApi('settings', 'core');
            if ($settings->unite['token'] !== $_SERVER['HTTP_SE_UNITE_TOKEN']) {
                $this->error('Token miss-match');
            }
        }

        $params = file_get_contents('php://input');
        if (!empty($params)) {
            $this->params = json_decode($params);
        }
    }

    protected function apiResource ($class) {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if (!class_exists($class)) {
            $this->error('Not a valid API endpoint.');
        }
        try {
            $ref = new ReflectionClass($class);
            $object = $ref->newInstance($this);
            if (!$ref->hasMethod($method)) {
                $this->error('Not a valid API endpoint.');
            }
            $params = [
                $this->params
            ];
            if ($method != 'post') {
                $params = [
                    $this->getRequest()->getParam('item'),
                    $this->params
                ];
            }
            $response = call_user_func_array(array($object, $method), $params);
            if ($response instanceof Core_Model_Item_Abstract) {
                $data = $response->toArray();
                $response = array();
                foreach ($data as $key => $value) {
                    $response[$key] = utf8_encode($value);
                }
            }
            UniteBridge_Controller_Response::json($response);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    protected function get ($key = null, $default = null) {
        if ($key === null) {
            return $this->params;
        }
        return isset($this->params[$key]) ? $this->params[$key] : $default;
    }

    public function error ($message) {
        UniteBridge_Controller_Response::json(array(
            'error' => $message
        ));
    }
}
