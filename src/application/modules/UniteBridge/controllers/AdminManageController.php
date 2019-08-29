<?php
/**
 * SocialEngine
 *
 * @package    UniteBridge
 * @copyright  Webligo Developments
 * @license    http://www.socialengine.com/license/
 */

class UniteBridge_AdminManageController extends Core_Controller_Action_Admin {
    private $unite;

    private $settings;

    public function init () {
        $this->settings = Engine_Api::_()->getApi('settings', 'core');
        $this->unite = $this->settings->unite;
    }

    public function settingsAction () {
        $this->view->error = '';
        $this->view->form = $form = new UniteBridge_Form_Admin_Settings();

        $form->populate($this->unite);

        if ($this->getRequest()->isPost()) {
            if (!$form->isValid($this->getRequest()->getPost())) {
                return;
            }
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $values = $form->getValues();
                $this->settings->unite = $values;
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
            $form->addNotice('Your changes have been saved.');
        }
    }

    public function indexAction (){

        $this->view->error = '';
        $this->view->unite = $unite = $this->unite;
        $this->view->reset = $reset = $this->getRequest()->getParam('reset', false);

        if (!empty($unite['url']) && !$reset) {
            return $this->settingsAction();
        }

        $this->view->form = $form = new UniteBridge_Form_Admin_Connect();

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
