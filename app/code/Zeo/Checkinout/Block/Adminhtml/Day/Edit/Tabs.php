<?php
    
namespace Zeo\Checkinout\Block\Adminhtml\Day\Edit;
    
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
            $this->setId('zeo_checkinout_day_edit_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(__('Day'));
    }
}
