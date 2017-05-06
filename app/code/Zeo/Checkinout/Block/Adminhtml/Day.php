<?php
    
namespace Zeo\Checkinout\Block\Adminhtml;
    
class Day extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_day'; /* block grid.php directory */
        $this->_blockGroup = 'Zeo_Checkinout';
        $this->_headerText = __('Day');
        $this->_addButtonLabel = __('Add New Entry');
        parent::_construct();
    }
}
