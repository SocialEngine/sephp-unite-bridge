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
        'object_id' => 'object_id',
        'object_type' => 'object_type',
        'subject_type' => 'subject_type',
        'subject_id' => 'subject_id',
        'subject' => 'subject',
        'privacy' => 'privacy',
        'object_privacy' => 'object_privacy'
    );

    protected $join = array();

    protected function queryCount ($query) {
        $this->query($query);
    }

    protected function query ($query) {
        $query->where('type = ?', $this->actionType);

        if (!empty($this->join['table'])) {
            $query->joinLeft(
                $this->join['table'],
                'engine4_activity_actions.object_type = \'' . $this->join['objectType'] . '\' AND engine4_blog_blogs.blog_id = engine4_activity_actions.object_id',
                array(
                    'subject' => 'title',
                    'body' => 'body',
                    'object_privacy' => 'view_privacy'
                )
            );
        }
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
