<?php

class UniteBridge_Form_Admin_Settings extends Engine_Form {
    public function init () {
        $this->addElement('Text', 'componentHeader', array(
            'label' => 'Theme Header Component'
        ));

        $this->addElement('Text', 'componentFooter', array(
            'label' => 'Theme Footer Component'
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Save Settings',
            'type' => 'submit',
            'ignore' => true
        ));
    }
}
