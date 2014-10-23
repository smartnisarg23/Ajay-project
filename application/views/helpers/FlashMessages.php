<?php

class Application_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{
    public function flashMessages()
    {
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $output = '';
        if (!empty($messages)) {
            foreach ($messages as $message) {
                $output .= '<div class="bg-'.key($message).' with-padding block-inner">' . current($message) . '</div>';
            }
        }
        return $output;
    }
}