<?php
class UniteBridge_Migrate_ForumsCategories extends UniteBridge_Migrate_Categories {
    protected $table = 'engine4_forum_categories';

    protected $map = [
        'category_id' => 'category_id',
        'title' => 'category_name'
    ];

    protected $mapCustom = [
        'children'
    ];

    protected function records ($records) {
        $response = [];
        $map = [
            'forum_id' => 'id',
            'title' => 'category_name'
        ];
        foreach ($records as $record) {
            $rows = $this->db->select()
                ->from('engine4_forum_forums')
                ->where('category_id = ?', $record['category_id'])
                ->query()
                ->fetchAll();
            $children = [];
            foreach ($rows as $row) {
                $items = [];
                foreach ($map as $key => $replacement) {
                    $items[$replacement] = $row[$key];
                }
                $children[] = $items;
            }
            $record['children'] = $children;

            $response[] = $record;
        }
        return $response;
    }
}
