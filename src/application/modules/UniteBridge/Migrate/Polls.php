<?php
class UniteBridge_Migrate_Polls extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_poll_polls';

    protected function records ($records) {
        $response = [];
        foreach ($records as $record) {
            $comments = [];
            $rows = $this->db->select()
                ->from('engine4_core_comments')
                ->where('resource_id = ?', $record['poll_id'])
                ->where('resource_type = ?', 'poll')
                ->query()
                ->fetchAll();
            foreach ($rows as $key => $row) {
                $comments[] = [
                    'id' => $row['comment_id'],
                    'poster_id' => $row['poster_id'],
                    'body' => $row['body'],
                    'creation_date' => $row['creation_date'],
                    'params' => []
                ];
            }
            $response[] = [
                'id' => $record['poll_id'],
                'subject_id' => $record['user_id'],
                'type' => 'polls',
                'params' => [],
                'creation_date' => $record['creation_date'],
                'subject' => $record['title'],
                'body' => $record['description'],
                'comments' => $comments,
                'answers' => $this->db->select()
                    ->from('engine4_poll_options')
                    ->where('poll_id = ?', $record['poll_id'])
                    ->query()
                    ->fetchAll(),
                'votes' => $this->db->select()
                    ->from('engine4_poll_votes')
                    ->where('poll_id = ?', $record['poll_id'])
                    ->query()
                    ->fetchAll()
            ];
        }
        return $response;
    }
}
