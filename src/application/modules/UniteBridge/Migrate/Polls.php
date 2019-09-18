<?php
class UniteBridge_Migrate_Polls extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_poll_polls';

    protected $tableIdKey = 'poll_id';

    protected $commentKey = 'poll';

    protected $likeKey = 'poll';

    protected function record ($record) {
        $record['answers'] = $this->db->select()
            ->from('engine4_poll_options')
            ->where('poll_id = ?', $record['id'])
            ->query()
            ->fetchAll();

        $record['votes'] = $this->db->select()
            ->from('engine4_poll_votes')
            ->where('poll_id = ?', $record['id'])
            ->query()
            ->fetchAll();

        return $record;
    }
}
