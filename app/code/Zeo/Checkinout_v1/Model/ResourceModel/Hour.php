<?php
    
namespace Zeo\Checkinout\Model\ResourceModel;
    
class Hour extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('zeo_checkinout_hour', 'entity_id');
    }
}
