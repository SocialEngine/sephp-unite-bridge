<?php
class UniteBridge_Migrate_Blogs extends UniteBridge_Migrate_Actions {
    protected $actionType = 'blog_new';

    protected $join = array(
        'table' => 'engine4_blog_blogs',
        'objectType' => 'blog'
    );
}
