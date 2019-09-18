<?php
class UniteBridge_Migrate_Actions extends UniteBridge_Migrate_Base {
    protected $actionType = null;

    protected $table = 'engine4_activity_actions';

    protected $resourceType = 'blog';

    protected $columns = [
        'id' => 'blog_id',
        'body' => 'body'
    ];

    protected $merge = [];

    protected $map = array(
        'action_id' => 'id',
        'type' => 'type',
        'body' => 'body',
        'params' => 'params',
        'date' => 'date',
        'object_id' => 'object_id',
        'object_type' => 'object_type',
        'subject_type' => 'subject_type',
        'subject_id' => 'subject_id',
        'subject' => 'subject',
        'privacy' => 'privacy',
        'object_privacy' => 'object_privacy',
        'category_id' => 'category_id'
    );

    protected $category = array();

    protected $join = array();

    protected function queryCount ($query) {
        $this->query($query);
    }

    protected function query ($query) {
        $query->where($this->table . '.type = ?', $this->actionType);

        if (!empty($this->join['table'])) {
            $query->joinLeft(
                $this->join['table'],
                'engine4_activity_actions.object_type = \'' . $this->join['objectType'] . '\' AND ' . $this->join['table'] . '.' . $this->columns['id'] . ' = engine4_activity_actions.object_id',
                array_merge(array(
                    'subject' => 'title',
                    'body' => $this->columns['body']
                ), $this->merge)
            );

            if (!empty($this->category['table'])) {
                $query->joinLeft(
                    $this->category['table'],
                    $this->join['table'] . '.category_id = ' . $this->category['table'] . '.category_id',
                    array(
                        'category_id' => 'category_id'
                    )
                );
            }
        }
    }

    protected function records ($records) {
        $response = [];
        foreach ($records as $record) {
            $record['comments'] = $this->getComments($record['object_id'], $this->resourceType);
            $record['reactions'] = $this->getLikes($record['object_id'], $this->resourceType);

            $response[] = $this->record($record);
        }
        return $response;
    }

    protected function record ($record) {
        return $record;
    }
}
