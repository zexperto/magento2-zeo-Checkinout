<?php
    
namespace Zeo\Checkinout\Controller\Adminhtml\Day;
    
class NewAction extends \Zeo\Checkinout\Controller\Adminhtml\Day
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
