<?php

class UniteBridge_MigrationController extends UniteBridge_Controller_Base
{
    private $migrations = array(
        'UniteBridge_Migrate_Users'
    );

    private function migrate ($name) {
        $ref = new ReflectionClass($name);
        $obj = $ref->newInstance(array(
            'page' => $this->getRequest()->getParam('page'),
            'limit' => $this->getRequest()->getParam('limit')
        ));
        return $this->sendJson(
            call_user_func(array($obj, 'run'))
        );
    }

    public function usersAction () {
        return $this->migrate('UniteBridge_Migrate_Users');
    }
}
