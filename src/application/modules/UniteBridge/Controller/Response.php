<?php

class UniteBridge_Controller_Response {
    static public function json ($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
