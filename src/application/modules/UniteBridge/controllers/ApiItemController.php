<?php

class UniteBridge_ApiItemController extends UniteBridge_Controller_Base
{
    public function usersAction () {
        return $this->apiResource('UniteBridge_ApiResource_User');
    }
}
