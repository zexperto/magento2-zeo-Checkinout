<?php

namespace Zeo\Checkinout\Block\Employee;

class Index extends \Magento\Framework\View\Element\Template {

    
    protected $_helper;
    
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Zeo\Checkinout\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_helper = $dataHelper;
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
    public function getCheckedInModel() {
        return $this->_helper->getCheckedInModel();
    }
    
    public function getThisMonthDays() {
        return $this->_helper->getThisMonthDays();
    }
    
    public function getEmployees() {
        return $this->_helper->getEmployees();
    }
    
    public function getEmployeeStatus($id) {
        return $this->_helper->getEmployeeStatus($id);
    }
    
}