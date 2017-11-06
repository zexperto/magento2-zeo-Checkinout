<?php
    
namespace Zeo\Checkinout\Model\ResourceModel;
    
class Day extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('zeo_checkinout_day', 'entity_id');
    }
}
