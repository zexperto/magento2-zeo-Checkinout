<?php
    
namespace Zeo\Checkinout\Model\System\Config;
    
use Magento\Framework\Option\ArrayInterface;
    
class Type implements ArrayInterface
{

    const BREAK = 1;
    const WORK = 0;
    public function toOptionArray()
    {
        $options = [
            self::BREAK => __('Break'),
            self::WORK => __('Work')
        ];
        return $options;
    }
}
