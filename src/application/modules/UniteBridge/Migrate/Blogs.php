<?php
class UniteBridge_Migrate_Blogs extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_blog_blogs';

    protected $tableIdKey = 'blog_id';

    protected $commentKey = 'blog';

    protected $likeKey = 'blog';

    protected $hasPhoto = true;
}
