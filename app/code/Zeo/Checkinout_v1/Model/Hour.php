<?php
    
namespace Zeo\Checkinout\Model;
    
class Hour extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Zeo\Checkinout\Model\ResourceModel\Hour');
    }
}
