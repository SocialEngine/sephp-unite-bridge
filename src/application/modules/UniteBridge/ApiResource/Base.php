<?php

class UniteBridge_ApiResource_Base {
    /**
     * @var UniteBridge_Controller_Base
     */
    protected $controller;

    protected $db;

    public function __construct ($controller) {
        $this->controller = $controller;
        $this->db = Engine_Db_Table::getDefaultAdapter();
        $this->init();
    }

    protected function init () {}

    protected function transaction ($cb) {
        $this->db->beginTransaction();
        try {
            $response = call_user_func($cb);
            $this->db->commit();
            return $response;
        } catch( Exception $e ) {
            $this->db->rollBack();
            $this->controller->error($e->getMessage());
        }
    }
}
