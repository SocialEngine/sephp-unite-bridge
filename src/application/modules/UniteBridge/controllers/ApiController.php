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
            $this->sendJson(array(
                'isValidPassword' => $isValidPassword
            ));
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
