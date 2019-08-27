<?php
class UniteBridge_Migrate_Actions extends UniteBridge_Migrate_Base {
    protected $actionType = null;

    protected $table = 'engine4_activity_actions';

    protected $map = array(
        'action_id' => 'id',
        'type' => 'type',
        'body' => 'body',
        'params' => 'params',
        'date' => 'date',
        'object_id' => 'user_id'
    );

    protected function queryCount ($query) {
        $this->query($query);
    }

    protected function query ($query) {
        $query->where('type = ?', $this->actionType);
    }

    protected function records ($records) {
        $response = [];
        foreach ($records as $record) {
            $record['comments'] = $this->getComments($record['id']);
            $record['reactions'] = $this->getLikes($record['id']);

            $response[] = $record;
        }
        return $response;
    }
}
