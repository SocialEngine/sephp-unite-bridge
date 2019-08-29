<?php

class UniteBridge_Form_Admin_Connect extends Engine_Form {
    public function init () {
        $this->setDescription('Setup your Unite Bridge.');

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
