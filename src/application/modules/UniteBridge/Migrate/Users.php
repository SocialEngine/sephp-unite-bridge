<?php
class UniteBridge_Migrate_Users extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_users';

    protected $map = array(
        'user_id' => 'id',
        'email' => 'email',
        'username' => 'username',
        'displayname' => 'name',
        'photo_storage_path' => 'picture',
        'password' => 'password',
        'salt' => 'password_salt',
        'groups' => 'groups',
        'photo_id' => 'photo_id'
    );

    protected function query ($query) {
        $query
            ->joinLeft('engine4_authorization_levels', 'engine4_users.level_id = engine4_authorization_levels.level_id', array(
                'groups' => 'type'
            ));
    }

    protected function records ($records) {
        $response = [];
        foreach ($records as $record) {
            if ($record['photo_id']) {
                $record['picture'] = [];
                $image = $this->db->select()
                    ->from('engine4_storage_files')
                    ->where('file_id = ?', $record['photo_id'])
                    ->query()
                    ->fetch();
                $images = $this->db->select()
                    ->from('engine4_storage_files')
                    ->where('parent_file_id = ?', $record['photo_id'])
                    ->query()
                    ->fetchAll();
                $record['picture'][] = [
                    'type' => 'default',
                    'path' => $image['storage_path']
                ];
                foreach ($images as $image) {
                    $record['picture'][] = [
                        'type' => $image['type'],
                        'path' => $image['storage_path']
                    ];
                }
            }
            $response[] = $record;
        }
        return $response;
    }
}
