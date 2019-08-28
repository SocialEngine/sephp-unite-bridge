<?php

class UniteBridge_ItemController extends UniteBridge_Controller_Base
{
    public function usersAction () {
        $this->apiResource('UniteBridge_ApiResource_User');
    }

    public function testsAction () {
        UniteBridge_Controller_Response::json([
            'hello' => 'world'
        ]);
    }
}
