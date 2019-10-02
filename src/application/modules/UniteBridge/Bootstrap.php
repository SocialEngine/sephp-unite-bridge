<?php

class UniteBridge_Bootstrap extends Engine_Application_Bootstrap_Abstract {
    private $unite;

    public function __construct ($application) {
        parent::__construct($application);

        $settings = Engine_Api::_()->getApi('settings', 'core');
        $this->unite = $settings->unite;

        if (!$this->unite['url']) {
            return null;
        }

        $this->initRedirects();
    }

    private function initRedirects () {
        if ($_SERVER['HTTP_HOST'] === 'localhost:8080' && !empty($_COOKIE['no_redirects']) && empty($_GET['test_redirect'])) {
            return null;
        }
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $url = $settings->unite['url'];
        $redirects = array(
            '/members/home' => '/',
            '/home/action/home' => '/',
            '/profile/:name' => '/u/:name',
            '/members/settings/general' => '/account/settings',
            '/members/settings/privacy' => '/account/privacy',
            '/members/settings/notifications' => '/account/privacy',
            '/members/settings/network' => '/account/networks',
            '/members/settings/password' => '/account/password',
            '/members/settings/delete' => '/account/membership',
            '/signout' => '/logout',
            '/videos' => '/videos',
            '/messages' => '/messages'
        );
        $uri = rtrim(str_replace('/index.php', '', $_SERVER['REQUEST_URI']), '/');
        $uri = explode('?', $uri)[0];
        $uriParts = explode('/', $uri);
        $send = '';
        foreach ($redirects as $key => $redirect) {
            $parts = explode('/', ltrim($key, '/'));
            $pattern = '';
            $mapping = array();
            $iteration = 0;
            foreach ($parts as $part) {
                if (substr($part, 0, 1) == ':') {
                    $mapping[$iteration] = $part;
                    $iteration++;
                    $part = '(\w+)';
                }
                $pattern .= '/' . $part;
            }
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
            if (preg_match($pattern, $uri, $params)) {
                array_shift($params);
                $pass = array();
                foreach ($params as $iteration => $param) {
                    if (!isset($mapping[$iteration])) {
                        continue;
                    }
                    $pass[$mapping[$iteration]] = $param;
                }
                foreach ($pass as $find => $replace) {
                    $redirect = str_replace('/' . $find, '/' . $replace, $redirect);
                }
                $send = $redirect;
            }
        }

        if (!$uri) {
            $send = '/';
        }

        if ($send) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url . $send);
            exit;
        }

        $sectionRedirects = [
            'forums',
            'polls',
            'videos',
            'messages'
        ];
        if (!empty($uriParts[1]) && in_array($uriParts[1], $sectionRedirects)) {
            $endpoint = $settings->unite['url'] . '/api/@SE/SEPHPBridge/redirect';
            $endpoint .= '?uri=' . urlencode($uri);
            $apiKey = $settings->unite['apiKey'];
            $request = new Zend_Http_Client($endpoint);
            $request->setHeaders(array(
                'se-client' => 'frontend',
                'se-api-key' => $apiKey
            ));
            $response = $request->request('GET');
            $body = json_decode($response->getBody());
            if (!empty($body->to)) {
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ' . $url . $body->to);
                exit;
            }
        }
    }
}
