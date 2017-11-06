<?php

namespace Zeo\Checkinout\Block\Index;

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
    
    public function getHelper() {
        return $this->_helper;
    }
    public function getReportResult() {
        
        $query_date = $this->getDate();
        
        $first_day =   date('Y-m-01', strtotime($query_date));
        $last_day = date('Y-m-d', strtotime($query_date));

        $params["employee_id"] = $this->_helper->getCurrentCustomerId();
        $params["start_date"] = $first_day;
        $params["end_date"] = $last_day;
        return  $this->_helper->getReportResult($params);
    }
    
}