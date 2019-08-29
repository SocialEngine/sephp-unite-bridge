<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


class UniteBridge_IndexController extends Core_Controller_Action_Standard
{
    public function ssoAction () {
        if ($this->getRequest()->get('logout')) {
            $viewer = Engine_Api::_()->user()->getViewer();
            if ($viewer->getIdentity()) {
                Engine_Api::_()->user()->getAuth()->clearIdentity();

                if (!empty($_SESSION['login_id'])) {
                    Engine_Api::_()->getDbtable('logins', 'user')->update(array(
                        'active' => false,
                    ), array(
                        'login_id = ?' => $_SESSION['login_id'],
                    ));
                    unset($_SESSION['login_id']);
                }
            }
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        $token = $this->getRequest()->get('token');
        $userTable = Engine_Api::_()->getDbtable('users', 'user');
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $url = $settings->unite['url'] . '/api/@SE/SEPHPBridge/sso?token=' . $token;
        $apiKey = $settings->unite['apiKey'];
        $request = new Zend_Http_Client($url);
        $request->setHeaders(array(
            'se-client' => 'frontend',
            'se-api-key' => $apiKey
        ));
        $response = $request->request('GET');
        $body = $response->getBody();
        if ($body) {
            $body = json_decode($body);
            $data = $body->data;

            if (!$data->sephpUserId) {
                $uniteUser = $data->viewer;
                $res = $userTable->createRow()->setFromArray(array(
                    'email' => $uniteUser->email,
                    'username' => $uniteUser->username,
                    'displayname' => $uniteUser->name
                ))->save();

                $request = new Zend_Http_Client(
                    $settings->unite['url'] . '/api/@SE/SEPHPBridge/users/' . $uniteUser->id
                );
                $request->setHeaders(array(
                    'se-client' => 'acp',
                    'se-api-key' => $apiKey,
                    'se-viewer-token' => $settings->unite['viewerToken']
                ));
                $request->setParameterPost('se_user_id', $res);
                $response = $request->request('PUT');
                $response->getBody();
            }

            $ipObj = new Engine_IP();
            $db = Engine_Db_Table::getDefaultAdapter();
            $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));
            $userSelect = $userTable->select()
                ->where('user_id = ?', $body->data->sephpUserId);
            $user = $userTable->fetchRow($userSelect);

            $temp = generateRandomString(64);
            $db->beginTransaction();
            try {
                $user->setFromArray(array(
                    'password' => $temp
                ));
                $user->save();

                $db->commit();
            } catch( Exception $e ) {
                $db->rollBack();
                print_r($e->getMessage());
                exit;
            }

            Engine_Api::_()->user()->authenticate($user['email'], $temp);

            $loginTable = Engine_Api::_()->getDbtable('logins', 'user');
            $insert = array(
                'user_id' => $user->getIdentity(),
                'email' => $user['email'],
                'ip' => $ipExpr,
                'timestamp' => new Zend_Db_Expr('NOW()'),
                'state' => 'success',
                'active' => true,
            );
            $loginTable->insert($insert);
            $_SESSION['login_id'] = $login_id = $loginTable->getAdapter()->lastInsertId();
        }
        return $this->_helper->redirector->gotoRoute(array('action' => 'home'), 'user_general', true);
    }

    public function authAction () {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $settings->reloadSettings();
        $response = null;
        $params = file_get_contents('php://input');
        if (!empty($params)) {
            $params = json_decode($params);
        }
        if ($settings->unite['auth'] !== $params->auth) {
            $response = array(
                'error' => 'Auth token does not match'
            );
        } else {
            $token = generateRandomString(128);
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->update('engine4_core_settings',
                array('value' => $token),
                array('name = ?' => 'unite.token')
            );
            $db->update('engine4_core_settings',
                array('value' => $params->viewerToken),
                array('name = ?' => 'unite.viewerToken')
            );
            $db->update('engine4_core_settings',
                array('value' => $params->apiKey),
                array('name = ?' => 'unite.apiKey')
            );
            $db->update('engine4_core_settings',
                array('value' => $params->siteId),
                array('name = ?' => 'unite.siteId')
            );
            $db->update('engine4_core_settings',
                array('value' => time()),
                array('name = ?' => 'unite.versionId')
            );
            $settings->reloadSettings();
            $response = array(
                'token' => $token
            );
        }
        UniteBridge_Controller_Response::json($response);
    }
}
