<?php return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'unite-bridge',
    'version' => '1.0.0',
    'sku' => 'unite-bridge',
    'path' => 'application/modules/UniteBridge',
    'title' => 'Unite Bridge',
    'description' => '',
    'author' => 'Webligo',
    'callback' =>
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
    'actions' =>
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' =>
    array (
      0 => 'application/modules/UniteBridge',
    ),
    'files' =>
    array (
      0 => 'application/languages/en/unite-bridge.csv',
    )
  ),
    // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onRenderContent',
            'resource' => 'UniteBridge_Plugin_Core'
        ),
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'UniteBridge_Plugin_Core'
        ),
        array(
            'event' => 'onItemPhoto',
            'resource' => 'UniteBridge_Plugin_Photo'
        )
    ),
    // Routes --------------------------------------------------------------------
    'routes' => array(
        'bridge_api_item' => array(
            'route' => 'bridge/api/:action/:item',
            'defaults' => array(
                'module' => 'unite-bridge',
                'controller' => 'item',
                'action' => 'index'
            ),
            'reqs' => array(
                'action' => '\D+',
                'item' => '[0-9]+'
            )
        ),
        'bridge_api' => array(
            'route' => 'bridge/api/:action',
            'defaults' => array(
                'module' => 'unite-bridge',
                'controller' => 'api',
                'action' => 'index'
            ),
            'reqs' => array(
                'action' => '\D+'
            )
        ),
        'bridge_connect' => array(
            'route' => 'bridge/connect/:action',
            'defaults' => array(
                'module' => 'unite-bridge',
                'controller' => 'index',
                'action' => 'index'
            ),
            'reqs' => array(
                'action' => '\D+'
            )
        ),
        'bridge_migrations' => array(
            'route' => 'bridge/migrations/:action',
            'defaults' => array(
                'module' => 'unite-bridge',
                'controller' => 'migration',
                'action' => 'index'
            ),
            'reqs' => array(
                'action' => '\D+'
            )
        )
    )
);
