<?php
/**
 * SocialEngine
 *
 * @package    UniteBridge
 * @copyright  Webligo Developments
 * @license    http://www.socialengine.com/license/
 */

class UniteBridge_AdminManageController extends Core_Controller_Action_Admin
{
    public function indexAction()
    {
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $this->view->error = '';
        $this->view->unite = $unite = $settings->unite;
        $this->view->form = $form = new UniteBridge_Form_Admin_Connect();
        $this->view->reset = $this->getRequest()->getParam('reset', false);

        $form->populate($unite);

        if ($this->getRequest()->isPost()) {
            if (!$form->isValid($this->getRequest()->getPost())) {
                return;
            }
            $values = $form->getValues();
            $checkUrl = $values['url'];
            if (substr($checkUrl, 0, 8) !== 'https://') {
                $checkUrl = 'https://' . $checkUrl;
            }
            $url = parse_url($checkUrl);
            try {
                $request = new Zend_Http_Client('https://' . $url['host'] . '/manifest.json');
                $response = $request->request('GET');
                if ($response->getHeader('se-id')) {
                    $auth = md5(time());
                    $db = Engine_Db_Table::getDefaultAdapter();
                    $db->update('engine4_core_settings',
                        array('value' => 'https://' . $url['host']),
                        array('name = ?' => 'unite.url')
                    );
                    $db->update('engine4_core_settings',
                        array('value' => $auth),
                        array('name = ?' => 'unite.auth')
                    );

                    $return = urlencode($this->view->serverUrl() . $this->view->url());
                    header('Location: https://' . $url['host'] . '/acp/sephp/connect?return=' . $return . '&auth=' . $auth);
                    exit;
                } else {
                    $this->view->error = 'Not a valid Unite site.';
                }
            } catch (Exception $e) {
                $this->view->error = $e->getMessage();
            }
        }

    }
}
