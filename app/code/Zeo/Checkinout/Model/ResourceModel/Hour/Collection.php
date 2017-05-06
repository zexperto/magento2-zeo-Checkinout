<?php
    
namespace Zeo\Checkinout\Model\ResourceModel\Hour ;
    
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Zeo\Checkinout\Model\Hour', 'Zeo\Checkinout\Model\ResourceModel\Hour');
    }
}
