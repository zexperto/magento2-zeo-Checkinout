<?php


namespace Zeo\Checkinout\Block;


class EmployeeLink extends \Magento\Framework\View\Element\Html\Link\Current
{
   
    protected $_helper;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param array $data
     */
    public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Framework\App\DefaultPathInterface $defaultPath,
            array $data = [],
            \Zeo\Checkinout\Helper\Data $dataHelper
            
            ) {
                parent::__construct($context, $defaultPath, $data);
                $this->_helper = $dataHelper;
    }
    
    protected function _toHtml()
    {   
        if ( $this->_helper->isEmployee() == true && $this->getPath()== "checkinout/index" ) {
            return parent::_toHtml();
        }
        
        
        return ;
    }
}