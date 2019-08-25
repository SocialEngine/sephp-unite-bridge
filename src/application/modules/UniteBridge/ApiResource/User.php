<?php

class UniteBridge_ApiResource_User extends UniteBridge_ApiResource_Base {
    /**
     * @var User_Model_DbTable_Users
     */
    private $table;

    private $map = array(
        'email' => 'email',
        'username' => 'username',
        'name' => 'displayname'
    );

    public function __construct ($controller) {
        parent::__construct($controller);

        $this->table = Engine_Api::_()->getDbtable('users', 'user');
    }

    /**
     * @param $id
     * @return Zend_Db_Table_Row_Abstract|null
     */
    public function get ($id) {
        $user = $this->table->select()
            ->where('user_id = ?', $id);
        return $this->table->fetchRow($user);
    }

    public function put ($id, $data) {
        return $this->transaction(function () use ($id, $data) {
            $user = $this->get($id);
            foreach ($this->map as $key => $name) {
                if (isset($data->{$key})) {
                    $user->{$name} = $data->{$key};
                }
            }
            $user->save();
            return $user;
        });
    }
}
