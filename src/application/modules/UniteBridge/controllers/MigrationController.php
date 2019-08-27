<?php

class UniteBridge_MigrationController extends UniteBridge_Controller_Base
{
    private function migrate ($name) {
        $ref = null;
        try {
            $ref = new ReflectionClass($name);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
        $obj = $ref->newInstance(array(
            'page' => $this->getRequest()->getParam('page'),
            'limit' => $this->getRequest()->getParam('limit', 100)
        ));
        return $this->sendJson(
            call_user_func(array($obj, 'run'))
        );
    }

    public function connectionsAction () {
        return $this->migrate('UniteBridge_Migrate_Connections');
    }

    public function usersAction () {
        return $this->migrate('UniteBridge_Migrate_Users');
    }

    public function statusAction () {
        return $this->migrate('UniteBridge_Migrate_Status');
    }
}
