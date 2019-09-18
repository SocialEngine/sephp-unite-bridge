<?php
class UniteBridge_Migrate_Videos extends UniteBridge_Migrate_Actions {
    protected $actionType = 'video_new';

    protected $join = array(
        'table' => 'engine4_video_videos',
        'objectType' => 'video'
    );

    protected $category = array(
        'table' => 'engine4_video_categories'
    );

    protected $columns = [
        'id' => 'video_id',
        'body' => 'description'
    ];

    protected $mapCustom = [
        'code',
        'photo',
        'photo_id'
    ];

    protected $merge = [
        'code' => 'code',
        'photo_id' => 'photo_id'
    ];

    protected $resourceType = 'video';

    protected function record ($record) {
        $record['photo'] = '';
        if ($record['photo_id']) {
            $image = $this->db->select()
                ->from('engine4_storage_files')
                ->where('file_id = ?', $record['photo_id'])
                ->query()
                ->fetch();
            $record['photo'] = 'sephp:/' . $image['storage_path'];
        }
        return $record;
    }
}
