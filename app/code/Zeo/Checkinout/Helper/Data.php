<?php
                
namespace Zeo\Checkinout\Helper;
        
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    protected $_customerSession;
    
    protected $_objectManager;
    
    protected $_resource;
    
    protected $_storeManager;
    
    protected $_employee_groups;
    
    protected $_manager_groups;
    
    protected $_logger;
    
    protected $_timezoneInterface;
    
    protected $_dayCollectionFactory;
    
    protected $_hourCollectionFactory;

    
    protected $_customerCollectionFactory;
    
    protected $_customerRepositoryInterface;
    
    
    protected $_hours_per_day = 8;
    protected $_break_per_day = 1;
    
    /**
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     *
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Psr\Log\LoggerInterface $logger,
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \Zeo\Checkinout\Model\ResourceModel\Day\CollectionFactory $dayCollectionFactory,
        \Zeo\Checkinout\Model\ResourceModel\Hour\CollectionFactory $hourCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $employees,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
        
    ) {
               
        $this->_scopeConfig = $scopeConfig;
        $this->_customerSession = $customerSession;
        $this->_resource = $resource;
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
        $this->_logger = $logger;
        $this->_timezoneInterface = $timezoneInterface;
        $this->_dayCollectionFactory = $dayCollectionFactory;
        $this->_hourCollectionFactory = $hourCollectionFactory;
        $this->_customerCollectionFactory= $employees;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        
      
       $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
      
        if (empty($this->_employee_groups)) {
            $_employee_group = trim($this->_scopeConfig->getValue('zeo_checkinout_section/general/employee_groups',$storeScope));
           
            if ($_employee_group != "") {
                $this->_employee_groups = explode(",", $_employee_group);
            }
        }
        
        if (empty($this->_manager_groups)) {
            $_manager_groups = trim($this->_scopeConfig->getValue('zeo_checkinout_section/general/manager_groups',$storeScope));
             
            if ($_manager_groups != "") {
                $this->_manager_groups = explode(",", $_manager_groups);
            }
        }
                
    }
    public function getTypes() {
        $types = $this->_objectManager->get('\Zeo\Checkinout\Model\System\Config\Type')->toOptionArray();
        return $types;
    }
    public function getEmployees() {
        $collection = $this->_customerCollectionFactory->create();
        $collection->addAttributeToFilter("group_id", ["in"=> $this->_employee_groups]);
        return $collection;
     }
     
     public function getEmployeeStatus($id) {
        
         $day = $this->getDate("day");
         
         $collection = $this->_dayCollectionFactory->create();
         $collection->addFieldToFilter("day", $day);
         $day_id = $collection->getFirstItem()->getEntityId();
         if($day_id >0) {
             $hourCollection = $this->_hourCollectionFactory->create();
             $hourCollection->addFieldToFilter("day_id", $day_id);
             $hourCollection->addFieldToFilter("end_time", "00:00:00");
             if($hourCollection->getSize()>0) {
                 return $hourCollection->getFirstItem()->getType();
             }
         }
         return -1;
     }
     
    public function getCheckedInModel() {
        
        
        $day = $this->getDate("day");
    
        $collection = $this->_dayCollectionFactory->create();
        $collection->addFieldToFilter("day", $day);
        $day_id = $collection->getFirstItem()->getEntityId();
        if($day_id >0) {
            $hourCollection = $this->_hourCollectionFactory->create();
            $hourCollection->addFieldToFilter("day_id", $day_id);
            $hourCollection->addFieldToFilter("end_time", "00:00:00");
            $hour_model = $hourCollection->getFirstItem();
            return  $hour_model;
        }
    
    return 0;
    }
    
    
    
     public function isEmployee() {
         $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
         

         if ($_customerSession->isLoggedIn()) {
             $group_id = $_customerSession->getCustomer()->getGroupId();
             
             if(in_array($group_id,  $this->_employee_groups)) {
                 return true;
             }
         }
         return false;
     }
     public function isManager() {
         $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
         
         
         if ($_customerSession->isLoggedIn()) {
             $group_id = $_customerSession->getCustomer()->getGroupId();
             
             if(in_array($group_id,  $this->_manager_groups)) {
                 return true;
             }
         }
         return false;
     }
    public function isDisable()
    {
        return $module_status = (boolean) $this->_scopeConfig->getValue('advanced/modules_disable_output/Zeo_Checkinout');
    }
    public function getDate($type = "") {
        $day = $this->_timezoneInterface->date()->format('Y-m-d');
        $time = $this->_timezoneInterface->date()->format('H:i:s');
        $today = $this->_timezoneInterface->date()->format('Y-m-d H:i:s');
        
        if($type =="day")
        {
            return $day;
        }
        if($type =="time")
        {
            return $time;
        }
        return $today ;
    }
    
    public function getThisMonthDays() {
        
        $query_date = $this->getDate("day");
        
        // First day of the month.
        $first_day =   date('Y-m-01', strtotime($query_date));
        // Last day of the month.
        $last_day = date('Y-m-t', strtotime($query_date));
        
        $customer_id = $this->_customerSession->getCustomer()->getId(); 
        $collection = $this->_dayCollectionFactory->create();
        $collection->addFieldToFilter("customer_id", $customer_id);
        
        $collection->addFieldToFilter('day', array('from'=>$first_day, 'to'=>$last_day));
        $collection->setOrder("day","desc");
        
        $data = [];
        // to join the table
        //$hours_table   = $this->_resource->getTableName('zeo_checkinout_hour'); // It will return table with prefix
        //$collection->getSelect()->join(  array('hour' => $hours_table),   'main_table.entity_id = hour.day_id',  array('entity_id as day_id','start_time','end_time','total' ));
        
        foreach ($collection as $day) {
            $item = $day->getData();
                $hours_collection= $this->_hourCollectionFactory->create();
                $hours_collection->addFieldToFilter("day_id", $day["entity_id"]);
                $item["hours"] = $hours_collection->getData();
            $data[] = $item;
        }
        
        
        
       // print_r($collection->getData());
        return  $data;

    }
    public function getCurrentCustomerId() {
        return $this->_customerSession->getCustomer()->getId();
    }
    
    public function getWeekEndTotals($params) {
        $employee_id = $params["employee_id"];
        $start_date = $params["start_date"];
        $end_date = $params["end_date"];
        
        $collection = $this->_dayCollectionFactory->create();
        $collection->addFieldToFilter("customer_id", $employee_id);
        $collection->addFieldToFilter("day_sequence", ["in"=>[6,7]]);
        $collection->addFieldToFilter('day', array('from'=>$start_date, 'to'=>$end_date));
        
        $hours_table   = $this->_resource->getTableName('zeo_checkinout_hour'); // It will return table with prefix
        $collection->getSelect()->join(  array('hour' => $hours_table),   'main_table.entity_id = hour.day_id',  array('SUM(total) as totals' ));
        
        $totals = $collection->getFirstItem()->getData("totals");
        
        return $totals;
    }
   
    public function getReportResult($params) {
        
        
        $employee_id = $params["employee_id"];
        $start_date = $params["start_date"];
        $end_date = $params["end_date"];
        
        
        
        $collection = $this->_dayCollectionFactory->create();
        $collection->addFieldToFilter("customer_id", $employee_id);
      //  $collection->addFieldToFilter("day_sequence", ["nin"=>[6,7]]);
        $collection->addFieldToFilter('day', array('from'=>$start_date, 'to'=>$end_date));
        $collection->setOrder("day","desc");
        
        $data = [];
        $total = 0;
        $total_work = 0;
        foreach ($collection as $day) {
            $item = $day->getData();
            $hours_collection= $this->_hourCollectionFactory->create();
            $hours_collection->addFieldToFilter("day_id", $day["entity_id"]);
            $item["hours"] = $hours_collection->getData();
            
            $line_total = $this->getSumDay($day["entity_id"]);
           
            if(!in_array($day["day_sequence"],[6,7])){
                $total += $line_total;
            }
            $item["line_total"] = $line_total;
            $item["line_total_hours"] = intdiv($line_total * 60, 60)." h";
            $item["line_total_minutes"] = ($line_total * 60) % 60 ." m";
            
            
            $line_total_work = $this->getSumDay($day["entity_id"],"0");
            if(!in_array($day["day_sequence"],[6,7])){
                $total_work+= $line_total_work;
            }
            $item["line_total_work"] = $line_total_work;
            $item["line_total_hours_work"] = intdiv($line_total_work* 60, 60)." h";
            $item["line_total_minutes_work"] = ($line_total_work* 60) % 60 ." m";
            
            $data[] = $item;
            
        }
        
        $customer_name = "";
        if($employee_id>0) {
            $customer = $this->_customerRepositoryInterface->getById($employee_id);
            $customer_name = $customer->getFirstname(). " ".$customer->getLastname();
            
            
        }
        
        $end_date_time = strtotime($end_date);
        $start_date_time = strtotime($start_date);
        $datediff = $end_date_time- $start_date_time;
        
        $total_days = floor($datediff/ (60 * 60 * 24))+1; 
        
        if($total_days == 0 ) {
            $total_days = 1;
        }
        $working_days = $this->getWorkingDays($start_date, $end_date, []);
       
        $logged_time_format= $this->getHoursMinutes($total);
        $complete_time_format = $this->getHoursMinutes($total_work);
        $complete_break_format = $this->getHoursMinutes($total - $total_work);
        $allowed_break= $working_days * $this->_break_per_day; 
        $required_minutes = $working_days * $this->_hours_per_day * 60;
        $required_time=  $this->getHoursMinutesFromMinutes($required_minutes);
        
        $extra_work_minutes = $this->getMinutesFromTotal($total) - $required_minutes;
        $extra_work_format =   $this->getHoursMinutesFromMinutes($extra_work_minutes);// $this->getHoursMinutesFromMinutes($this->getMinutesFromTotal($total) - $required_time);
        
        
        $weekend_totals = $this->getWeekEndTotals($params);
        $weekend_hoour_format = $this->getHoursMinutesFromMinutes($this->getMinutesFromTotal($weekend_totals));

        $total_logged_time_format = $this->getHoursMinutes($total + $weekend_totals);
        
        $result = [
            "total_logged_time"   => $total_logged_time_format,
            "logged_time"   => $logged_time_format,
            "complete_time"     =>$complete_time_format,
            "working_days" => $working_days,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "total_days" => $total_days,
            "days" => $data,
            "total" => $total,
            "total_work" => $total_work,
            "employee_name" => $customer_name,
            "required_time" => $required_time,
            "allowed_break" => $this->fix($allowed_break).":00",
            
            "total_hours" =>($total_hours=  intdiv($total* 60, 60))." h",
            "total_minutes" => ($total_minutes = ($total* 60) % 60) ." m",
            "total_hours_work" => ($total_hours_work =intdiv($total_work* 60, 60))." h",
            "total_minutes_work" => ($total_minutes_work=($total_work* 60) % 60) ." m",
            "average_hours_per_day" => intdiv(($total/$working_days)* 60, 60)." h" .":".(($total/$working_days)* 60) % 60 ." m",
            "average_hours_work_per_day" => intdiv(($total_work/$working_days)* 60, 60)." h" .":".(($total_work/$working_days)* 60) % 60 ." m",
            "extra_work" =>$extra_work_format,// $this->fix($total_hours- $required_time- $allowed_break) . ":".$this->fix($total_minutes),
            "breaks" => $complete_break_format,//$this->fix($total_hours- $total_hours_work).":".$this->fix($total_minutes- $total_minutes_work),
            "extra_break_minutes" => $extra_break_minutes = (($allowed_break * 60) - (($total_hours * 60 + $total_minutes)- ($total_hours_work* 60 + $total_minutes_work))),
            "extra_break_hour" => $this->fix((int)($extra_break_minutes/60)).":".$this->fix((int)($extra_break_minutes%60)),
            "weekend_hours" => $weekend_hoour_format,
        ];
        
        //($result["allowed_break"]-$result["breaks"])
       
        return  $result;
    }
    public function getMinutesFromTotal($total) {
        $hours = intdiv($total* 60, 60) ;
        $minutes = ($total* 60) % 60;
        $total_minutes = $hours * 60 + $minutes;
        return $total_minutes;
    }
    public function getHoursMinutes($total) {

        $hours = intdiv($total* 60, 60) ;
        $minutes = ($total* 60) % 60;
        $total_minutes = $hours * 60 + $minutes;
        
        return $this->fix($hours).":".$this->fix($minutes);
    }
    public function getHoursMinutesFromMinutes($minutes) {
        
        $prefix = "";
        if($minutes < 0) {
            $minutes = $minutes * -1;
            $prefix = "-";
        }
        $hours = (int) ($minutes / 60);
        $minutes = $minutes % 60;
        
        $result = $prefix.$this->fix($hours).":".$this->fix($minutes);
        return  $result;
    }
    public function fix($number) {
        
        if($number<10 && $number>=0) {
            return "0".$number;
        }else if ($number > -10 && $number<=0) {
            return "- 0".(-1 * $number);
        }
        
        return $number;
    }
    public function getSumDay($day_id, $type = "")
    {
        $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        // Group Price query with start_date and end_date
        $select = $connection->select()->from(
                ['entity' => $this->_resource->getTableName('zeo_checkinout_hour')],
                [
                    new \Zend_Db_Expr('SUM(total)  as total')
                ]
                );
        $bind[':day_id'] = $day_id;
        $select->where('entity.day_id = :day_id');
        if($type !="") {
            $bind[':type'] = $type;
            $select->where('entity.type = :type');
        }
        $result = $connection->fetchOne($select, $bind);
        return $result;
    }
    
    public function doCheckIn($type = ""){
        $day = $this->getDate("day");
        $day_sequance = date('w', strtotime($day));
        $start_time = $this->getDate("time");
        
        $customer_id = $this->_customerSession->getCustomer()->getId();
        $collection = $this->_dayCollectionFactory->create();
        $collection->addFieldToFilter("day", $day);
        $collection->addFieldToFilter("customer_id", $customer_id);
        
        $have_day = $collection->getSize();
        $day_id = 0;
        if($have_day == 0) {
            
            $model = $this->_objectManager->create('Zeo\Checkinout\Model\Day');
            $model->setData("day", $day);
            $model->setData("customer_id", $customer_id);
            $model->setData("status", 1);
            $model->setData("day_sequence", $day_sequance);
            
            $model->save();
            $day_id = $model->getEntityId();
        } else {
            $day_id = $collection->getFirstItem()->getEntityId();
            
        }
        
        $hourCollection = $this->_hourCollectionFactory->create();
        $hourCollection->addFieldToFilter("day_id", $day_id);
        $hourCollection->addFieldToFilter("end_time", "00:00:00");
        $can_checkin = $hourCollection->getSize();
        if($can_checkin == 0 ) {
            $hour_model = $this->_objectManager->create('Zeo\Checkinout\Model\Hour');
            $hour_model->setData("day_id",$day_id);
            $hour_model->setData("start_time", $start_time);
            $hour_model->setData("end_time", "00:00:00");
            $hour_model->setData("status", 1);
            
            if($type == "start_break") {
                $hour_model->setData("type",\Zeo\Checkinout\Model\System\Config\Type::BREAK);
            }else {
                $hour_model->setData("type",\Zeo\Checkinout\Model\System\Config\Type::WORK);
            }
            
            $hour_model->save();
            return true;
        } else {
            return __("You are already checked in, Please checkout to be able to check in.");
        }
    }
    
    public function doCheckOut($type= ""){
        
        $day = $this->getDate("day");
        $end_time = $this->getDate("time");
        
        $customer_id = $this->_customerSession->getCustomer()->getId();
        $collection = $this->_dayCollectionFactory->create();
        $collection->addFieldToFilter("day", $day);
        $collection->addFieldToFilter("customer_id", $customer_id);
        
        $have_day = $collection->getSize();
        if($have_day == 0) {
            return __("Error day");
        } else {
            $day_id = $collection->getFirstItem()->getEntityId();
            $hourCollection = $this->_hourCollectionFactory->create();
            $hourCollection->addFieldToFilter("day_id", $day_id);
            $hourCollection->addFieldToFilter("end_time", "00:00:00");
            $can_checkin = $hourCollection->getSize();
            if($can_checkin == 0 ) {
                return __("Error hour");
            }else {
                $hour_model = $hourCollection->getFirstItem();
                $start_time = $hour_model->getStartTime();
                $counter = date('H:i:s', strtotime($end_time) - strtotime($start_time) );
                $decimalHours = $this->decimalHours($counter);
                $decimalHours= ceil($decimalHours*1000)/1000;;
                $hour_model->setData("end_time",$end_time);
                $hour_model->setData("total_time",$counter);
                $hour_model->setData("total",$decimalHours);
                $hour_model->save();
                
                if($type!= "") {
                    $this->doCheckIn($type);
                }
            }
        }
        return true;
    }
    function decimalHours($time)
    {
        $hms = explode(":", $time);
        return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
    }
    
    function getWorkingDays($startDate,$endDate,$holidays){
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);
        
        
        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;
        
        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);
        
        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);
        
        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)
            
            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;
                
                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }
        
        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
        //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }
        
        //We subtract the holidays
        foreach($holidays as $holiday){
            $time_stamp=strtotime($holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                $workingDays--;
        }
        
        return $workingDays;
    }
    
}
