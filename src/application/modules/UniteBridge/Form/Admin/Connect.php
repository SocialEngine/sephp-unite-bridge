<?php
/**
 * SocialEngine
 *
 * @package    UniteBridge
 * @copyright  Webligo Developments
 * @license    http://www.socialengine.com/license/
 */

class UniteBridge_Form_Admin_Connect extends Engine_Form
{
    public function init()
    {
        $this->setTitle('Unite Bridge')
            ->setDescription('Setup your Unite Bridge.');

        $this->addElement('Text', 'url', array(
            'label' => 'Unite URL'
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Connect',
            'type' => 'submit',
            'ignore' => true
        ));
    }
}
