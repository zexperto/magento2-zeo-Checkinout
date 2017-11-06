<?php
    
namespace Zeo\Checkinout\Model\ResourceModel\Day ;
    
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Zeo\Checkinout\Model\Day', 'Zeo\Checkinout\Model\ResourceModel\Day');
    }
}
