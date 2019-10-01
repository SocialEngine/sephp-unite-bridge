<?php

class UniteBridge_ApiResource_Groups extends UniteBridge_ApiResource_Base {
    /**
     * @var Group_Model_DbTable_Groups
     */
    private $table;

    protected function init () {
        $this->table = Engine_Api::_()->getDbtable('groups', 'group');
    }

    public function get ($id = null, $query = []) {
        $page = !empty($query['page']) ? $query['page'] : 1;
        $limit = !empty($query['limit']) ? $query['limit'] : 20;
        $table = $this->table
            ->select()
            ->limitPage($page, $limit);

        if ($id) {
            $table->where('group_id = ?', $id);
        }

        return call_user_func([$table->query(), $id ? 'fetch' : 'fetchAll']);
    }
}
