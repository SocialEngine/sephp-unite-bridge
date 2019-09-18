<?php
class UniteBridge_Migrate_Videos extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_video_videos';

    protected $tableIdKey = 'video_id';

    protected $commentKey = 'video';

    protected $likeKey = 'video';

    protected function query ($query) {
        $query->where('parent_type IS NULL');
    }

    protected $hasPhoto = true;
}
