<?php
class UniteBridge_Migrate_Feed extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_activity_actions';

    protected $tableIdKey = 'action_id';

    protected function record ($record) {
        $record['user_id'] = $record['subject_id'];
        $record['comments'] = $this->db->select()
            ->from('engine4_activity_comments')
            ->where('resource_id = ?', $record['id'])
            ->query()
            ->fetchAll();

        $record['reactions'] = $this->db->select()
            ->from('engine4_activity_likes')
            ->where('resource_id = ?', $record['id'])
            ->query()
            ->fetchAll();

        return $record;
    }
}
