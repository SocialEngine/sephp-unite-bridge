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
    /*
    protected function records ($records) {
        $response = [];
        foreach ($records as $record) {
            $posts = [];
            $rows = $this->db->select()
                ->from('engine4_forum_posts')
                ->where('topic_id = ?', $record['topic_id'])
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
            $response[] = [
                'id' => $record['topic_id'],
                'category_id' => $record['forum_id'],
                'subject_id' => $record['user_id'],
                'type' => 'forum_topic',
                'params' => [],
                'creation_date' => $record['creation_date'],
                'subject' => $record['title'],
                'body' => $current['body'],
                'comments' => $posts
            ];
        }
        return $response;
    }
    */
}
