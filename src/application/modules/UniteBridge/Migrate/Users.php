<?php
class UniteBridge_Migrate_Users extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_users';

    protected $tableIdKey = 'user_id';

    protected $hasPhoto = true;

    protected function query ($query) {
        $query
            ->joinLeft('engine4_authorization_levels', 'engine4_users.level_id = engine4_authorization_levels.level_id', array(
                'groups' => 'type'
            ));
    }

    protected function record ($record)
    {
        $record['picture'] = [];
        if ($record['photo_id']) {
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

        return [
            'id' => $record['id'],
            'email' => $record['email'],
            'username' => $record['username'],
            'name' => $record['displayname'],
            'password' => $record['password'],
            'password_salt' => $record['salt'],
            'groups' => $record['groups'],
            'picture' => $record['picture']
        ];
    }
}
