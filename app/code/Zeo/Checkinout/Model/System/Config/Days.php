<?php
    
namespace Zeo\Checkinout\Model\System\Config;
    
use Magento\Framework\Option\ArrayInterface;
use Zeo\Checkinout\Model\DayFactory;

class Days implements ArrayInterface
{

    protected $dayFactory;
    
    public function __construct(DayFactory$dayFactory)
    {
        $this->dayFactory= $dayFactory;
    }
    
    

    public function toOptionArray()
    {

        $items= $this->dayFactory->create()->getCollection()->setOrder("day","desc");
        
        $options[] = " ";
        foreach ($items as $item) {
            $options[$item->getEntityId()] = $item->getDay();
        }
        
        return $options;
        
    }
}
