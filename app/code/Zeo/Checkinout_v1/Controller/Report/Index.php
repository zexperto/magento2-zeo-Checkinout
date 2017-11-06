<?php

namespace Zeo\Checkinout\Controller\Report;

class Index extends \Magento\Customer\Controller\AbstractAccount
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}