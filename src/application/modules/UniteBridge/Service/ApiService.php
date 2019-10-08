<?php

class UniteBridge_Service_ApiService {
    public static function call($method, $endpoint, $params = []) {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $url = $settings->unite['url'] . '/api/@SE/SEPHPBridge' . $endpoint;
        $apiKey = $settings->unite['apiKey'];
        $request = new Zend_Http_Client($url);
        $request->setHeaders(array(
            'se-client' => 'acp',
            'se-api-key' => $apiKey,
            'se-viewer-token' => $settings->unite['viewerToken']
        ));
        $request->setParameterPost($params);
        $request->request($method);
    }
}
