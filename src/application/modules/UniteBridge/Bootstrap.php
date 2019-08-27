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
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $url = $settings->unite['url'];
        $redirects = array(
            // '/members/home' => '/',
            // '/profile/:name' => '/u/:name',
            '/members/settings/general' => '/account/settings',
            '/members/settings/privacy' => '/account/privacy',
            '/members/settings/notifications' => '/account/privacy',
            '/members/settings/network' => '/account/networks',
            '/members/settings/password' => '/account/password',
            '/members/settings/delete' => '/account/membership',
            '/signout' => '/logout'
        );
        $uri = rtrim(str_replace('/index.php', '', $_SERVER['REQUEST_URI']), '/');
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
            header('Location: ' . $url . $send);
            exit;
        }
    }
}
