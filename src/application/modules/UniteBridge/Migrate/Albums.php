<?php
class UniteBridge_Migrate_Albums extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_album_albums';

    protected $tableIdKey = 'album_id';

    protected $commentKey = 'album';

    protected $likeKey = 'album';

    protected function query ($query) {
        $query->where('type IS NULL');
    }

    protected function record ($record) {
        $record['photos'] = [];
        $photos = $image = $this->db->select()
            ->from('engine4_album_photos')
            ->where('album_id = ?', $record['id'])
            ->query()
            ->fetchAll();
        foreach ($photos as $photo) {
            $image = $this->db->select()
                ->from('engine4_storage_files')
                ->where('file_id = ?', $photo['file_id'])
                ->query()
                ->fetch();
            $record['photos'][] = 'sephp:/' . $image['storage_path'];
        }
        return $record;
    }
}
