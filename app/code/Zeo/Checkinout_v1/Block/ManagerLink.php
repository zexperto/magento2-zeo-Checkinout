<?php


namespace Zeo\Checkinout\Block;


class ManagerLink extends \Magento\Framework\View\Element\Html\Link\Current
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
        if ( $this->_helper->isManager() == true && ($this->getPath()== "checkinout/employee" || $this->getPath()== "checkinout/report") ) {
            return parent::_toHtml();
        }
        
        
        return ;
    }
}