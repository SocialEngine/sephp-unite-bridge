<?php
class UniteBridge_Migrate_Status extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_core_status';

    protected $tableIdKey = 'status_id';

    protected function init () {
        $this->replacements = array_merge($this->replacements, [
            'resource_id' => 'user_id'
        ]);
    }
}
