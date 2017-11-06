<?php

namespace Zeo\Checkinout\Block\Report;


class Index extends \Magento\Framework\View\Element\Template {

    
    protected $_helper;
    
    
    
    protected $_customerSession;
    
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Zeo\Checkinout\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_helper = $dataHelper;
        $this->_customerSession= $customerSession;
        parent::__construct($context, $data);

    }


    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
   
    public function getDate() {
       return  $this->_helper->getDate("day");
    }
    public function getTime() {
        return $this->_helper->getDate("time");
    }
    
    public function getEmployees() {
        return $this->_helper->getEmployees();
    }
    public function getFormAction() {
        return $this->getUrl('checkinout/report/reportpost');
    }
    
   
    public function getPostValues()
    {
        return $this->_customerSession->getReportFormData();
    }
    public function getReportResult() {
        $params= $this->_customerSession->getReportFormData();
        return  $this->_helper->getReportResult($params);
    }
    public function getHelper() {
        return $this->_helper;
    }
    
}