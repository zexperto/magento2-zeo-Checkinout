<?php
    
namespace Zeo\Checkinout\Block\Adminhtml;
    
class Hour extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_hour'; /* block grid.php directory */
        $this->_blockGroup = 'Zeo_Checkinout';
        $this->_headerText = __('Hour');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
    }
}
