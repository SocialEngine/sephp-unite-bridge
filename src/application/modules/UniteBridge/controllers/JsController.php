<?php

class UniteBridge_JsController extends Core_Controller_Action_Standard {
    public function indexAction () {
        $item = $this->getRequest()->getUserParam('item');
        $item = str_replace('.js', '', $item);
        $actionItem = Engine_Api::_()->getItem('activity_action', $item);
        $parent = $actionItem->getParent();
        $response = [
            'id' => $item,
            'action' => $actionItem->getCleanData(),
            'title' => $actionItem->getTitle(),
            'body' => $actionItem->getDescription(),
            'href' => $parent->getHref(),
            'parent' => [
                'title' => $parent->getTitle(),
                'href' => $parent->getHref(),
                'body' => $parent->getDescription()
            ]
        ];
        header('Content-Type: application/javascript');
        echo '_legacyResponse(\'' . $item . '\', ' . json_encode($response) . ');';
        exit;
    }
}
