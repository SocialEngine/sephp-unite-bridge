<?php

class UniteBridge_Plugin_Photo {
    private $unite;

    private $isEnabled = false;

    public function __construct () {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $this->unite = $settings->unite;
        if ($this->unite['url']) {
            $this->isEnabled = true;
        }
    }

    public function onItemPhoto ($event) {
        if (!$this->isEnabled) {
            return null;
        }
        $payload = $event->getPayload();
        $item = $payload['item'];
        if ($item instanceof User_Model_User) {
            $src = $this->unite['url'] . '/';
            $src .= implode('/', [
                'storage',
                $this->unite['siteId'],
                strtotime($item['modified_date']),
                '@SE/SEPHPBridge/user',
                $payload['type'],
                $item['user_id'] . '.png'
            ]);
            $event->setResponse('
                <img src="' . $src . '" />
            ');
        }
    }
}
