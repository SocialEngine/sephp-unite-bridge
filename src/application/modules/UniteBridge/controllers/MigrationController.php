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
        try {
            $obj = $ref->newInstance(array(
                'page' => $this->getRequest()->getParam('page', 1),
                'limit' => $this->getRequest()->getParam('limit', 100)
            ));
            UniteBridge_Controller_Response::json(
                call_user_func(array($obj, 'run'))
            );
        } catch (Exception $e) {
            UniteBridge_Controller_Response::error($e);
        }
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

    public function blogsAction () {
        return $this->migrate('UniteBridge_Migrate_Blogs');
    }

    public function blogsCategoriesAction () {
        return $this->migrate('UniteBridge_Migrate_BlogsCategories');
    }

    public function albumsCategoriesAction () {
        return $this->migrate('UniteBridge_Migrate_AlbumsCategories');
    }

    public function albumsAction () {
        return $this->migrate('UniteBridge_Migrate_Albums');
    }
}
