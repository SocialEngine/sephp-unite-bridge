<?php
class UniteBridge_Migrate_Forums extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_forum_topics';

    protected $tableIdKey = 'topic_id';

    protected function record ($record) {
        $posts = [];
        $rows = $this->db->select()
            ->from('engine4_forum_posts')
            ->where('topic_id = ?', $record['id'])
            ->query()
            ->fetchAll();
        $current = null;
        foreach ($rows as $key => $row) {
            if ($key === 0) {
                $current = $row;
                continue;
            }
            $posts[] = [
                'id' => $row['post_id'],
                'poster_id' => $row['user_id'],
                'body' => $row['body'],
                'creation_date' => $row['creation_date'],
                'params' => []
            ];
        }
        $record['comments'] = $posts;
        $record['body'] = $current['body'];
        return $record;
    }
}
