<?php
class UniteBridge_Migrate_Messages extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_messages_conversations';

    protected $tableIdKey = 'conversation_id';

    protected function record ($record) {
        $record['messages'] = $this->db->select()
            ->from('engine4_messages_messages')
            ->where('conversation_id = ?', $record['id'])
            ->query()
            ->fetchAll();

        $record['users'] = $this->db->select()
            ->from('engine4_messages_recipients')
            ->where('conversation_id = ?', $record['id'])
            ->query()
            ->fetchAll();
        return $record;
    }
}
