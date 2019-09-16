<?php
class UniteBridge_Migrate_Albums extends UniteBridge_Migrate_Actions {
    protected $actionType = 'album_photo_new';

    protected $join = array(
        'table' => 'engine4_album_albums',
        'objectType' => 'album'
    );

    protected $category = array(
        'table' => 'engine4_album_categories'
    );

    protected $columns = [
        'id' => 'album_id',
        'body' => 'description'
    ];

    protected $mapCustom = [
        'photos'
    ];

    protected $resourceType = 'album';

    protected function record ($record) {
        $record['photos'] = [];
        $photos = $image = $this->db->select()
            ->from('engine4_album_photos')
            ->where('album_id = ?', $record['object_id'])
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
