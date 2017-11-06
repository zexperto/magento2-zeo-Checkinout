<?php
    
namespace Zeo\Checkinout\Controller\Adminhtml\Hour;
    
class NewAction extends \Zeo\Checkinout\Controller\Adminhtml\Hour
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
