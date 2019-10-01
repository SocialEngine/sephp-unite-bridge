<?php

class UniteBridge_ApiController extends UniteBridge_Controller_Base
{
    public function loginAction () {
        try {
            $auth = $this->get();
            $userTable = Engine_Api::_()->getDbtable('users', 'user');
            $userSelect = $userTable->select()
                ->where('user_id = ?', $auth->user_id);
            $user = $userTable->fetchRow($userSelect);
            $isValidPassword = Engine_Api::_()->user()->checkCredential($user->getIdentity(), $auth->password);
            UniteBridge_Controller_Response::json(array(
                'isValidPassword' => $isValidPassword
            ));
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function usersAction () {
        return $this->apiResource('UniteBridge_ApiResource_User');
    }

    public function groupsAction () {
        return $this->apiResource('UniteBridge_ApiResource_Groups');
    }
}
