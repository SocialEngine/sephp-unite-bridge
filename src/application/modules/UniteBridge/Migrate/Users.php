<?php
class UniteBridge_Migrate_Users extends UniteBridge_Migrate_Base {
    protected $table = 'engine4_users';

    protected $map = array(
        'user_id' => 'id',
        'email' => 'email',
        'username' => 'username',
        'displayname' => 'name',
        'photo_storage_path' => 'photo',
        'password' => 'password',
        'salt' => 'password_salt',
        'groups' => 'groups'
    );

    protected function joinLeft ($query) {
        return $query
            ->joinLeft('engine4_album_photos', 'engine4_album_photos.photo_id = engine4_users.photo_id', array(
                'photo_file_id' => 'file_id'
            ))
            ->joinLeft('engine4_storage_files', 'engine4_storage_files.file_id = engine4_album_photos.file_id', array(
                'photo_storage_path' => 'storage_path'
            ))
            ->joinLeft('engine4_authorization_levels', 'engine4_users.level_id = engine4_authorization_levels.level_id', array(
                'groups' => 'type'
            ));
    }
}
