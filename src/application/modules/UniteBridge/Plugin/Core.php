<?php

class UniteBridge_Plugin_Core {
    private $unite;

    private $isEnabled = false;

    public function __construct () {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $this->unite = $settings->unite;
        if ($this->unite['url']) {
            $this->isEnabled = true;
        }
    }

    public function onRenderLayoutDefault ($event) {
        if (!$this->isEnabled) {
            return false;
        }
        $view = $event->getPayload();
        if ($view instanceof Zend_View) {
            $src = $this->unite['url'] . '/storage/' . $this->unite['siteId'] . '/' . $this->unite['versionId'] . '/js/bootstrap.js';
            $viewerUrl = $this->unite['url'] . '/storage/' . $this->unite['siteId'] . '/' . time() . '/js';
            $event->setResponse('
                <script
                    src="' . $viewerUrl . '/viewer.js"
                    type="application/javascript"></script>
                <script src="' . $src . '"></script>
                <style>
                    #root-loader-container {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        z-index: 10000;
                        background: #F2F3F8;
                    }
                    
                    svg#root-loader {
                        width: 80px;
                        height: 80px;
                        margin: -40px 0 0 -40px;
                        display:inline-block;
                        position: fixed;
                        top: 50%;
                        left: 50%;
                    }
                    .cls-1,.cls-2 {
                        fill:none;
                        stroke-linecap:bevel;
                        stroke-linejoin:round;
                    }
                    .cls-1 {
                        stroke-width: 2px;
                    }
                    .cls-2 {
                        fill:none;
                        stroke: #007bff;
                        stroke-width: 2px;
                    }
                 </style>
            ');
        }
    }

    public function onRenderContent ($event) {
        if (!$this->isEnabled) {
            return false;
        }
        $payload = $event->getPayload();
        if ($payload['name'] == 'core_error_requireuser') {
            $viewer = Engine_Api::_()->user()->getViewer();
            var_dump($viewer->getIdentity());
            var_dump($_SESSION);
            exit('requires user...');
            $url = $this->unite['url'] . '/login?return=' . urlencode($_SERVER['REQUEST_URI']);
            header('Location: ' . $url);
            exit;
        } else if ($payload['name'] == 'header') {
            $headerComponent = $this->unite['componentHeader'];
            $event->setResponse('
<div id="root-loader-container">
<svg id="root-loader" viewBox="-25 -25 100 100" preserveAspectRatio>
    <defs>
        <linearGradient id="gr-simple" x1="0" y1="0" x2="100%" y2="100%">
            <stop stop-color="rgba(0, 123, 255,.2)" offset="10%"/>
            <stop stop-color="rgba(0, 123, 255,.7)" offset="90%"/>
        </linearGradient>
    </defs>
    <circle class="cls-1" cx="26" cy="27" r="26" stroke="url(#gr-simple)"/>
    <path class="cls-2" d="M25,0A24.92,24.92,0,0,1,42.68,7.32" transform="translate(1 2)">
        <animateTransform
            attributeName="transform"
            type="rotate"
            dur="1s"
            from="0 26 27"
            to="360 26 27"
            repeatCount="indefinite"/>
    </path>
</svg>
</div>
<div data-render="' . $headerComponent . '"></div>
');
        } else if ($payload['name'] == 'footer') {
            $url = $this->unite['url'] . '/storage/' . $this->unite['siteId'] . '/' . $this->unite['versionId'] . '/js';
            $parsed = parse_url($this->unite['url']);
            $footerComponent = $this->unite['componentFooter'];
            $event->setResponse('
                <div data-render="' . $footerComponent . '"></div>
                <div id="root"></div>
                <script
                    src="' . $url . '/init.js"
                    type="application/javascript"
                    id="unite-bridge"
                    data-origin="' . $parsed['host'] . '"
                    data-site-id="' . $this->unite['siteId'] . '"></script>
                    <div id="root-modal"></div>
                <script>
                    en4.core.runonce.add(function() {
                        var el = document.getElementById(\'global_content\');
                        if (el) {
                            el.classList.add(\'container\');
                        }
                        
                        el = document.getElementById(\'global_wrapper\');
                        if (el) {
                            el.classList.add(\'main\');
                            el.classList.add(\'section-main\');
                        }
                    });
                </script>
            ');
        }
    }
}
