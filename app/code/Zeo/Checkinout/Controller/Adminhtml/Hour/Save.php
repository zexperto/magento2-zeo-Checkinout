<?php
    
namespace Zeo\Checkinout\Controller\Adminhtml\Hour;
    
class Save extends \Zeo\Checkinout\Controller\Adminhtml\Hour
{
    function decimalHours($time)
    {
        $hms = explode(":", $time);
        return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
    }
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_objectManager->create('Zeo\Checkinout\Model\Hour');
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input([ ], [ ], $data);
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getEntityId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $data["entity_id"] = $id;
                
                
                
                // get the hours between dates
                $total_times_seconds =  strtotime($data["end_time"]) - strtotime($data["start_time"]);
                $hours = (int)($total_times_seconds/3600);
                $minutes = (int) (($total_times_seconds%3600)/60);
                $seconds = (($total_times_seconds%3600)%60);

                $timeFormat = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                // end get the date
                
              
                $total_time = $timeFormat;
                                
                $decimalHours = $this->decimalHours($total_time);
                
                $decimalHours= ceil($decimalHours*1000)/1000;;
              //  $decimalHours= round($decimalHours/ 0.05) * 0.05;
                
                $data["total_time"] = $total_time;
                $data["total"] = $decimalHours;
                
                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('zeo_checkinout/*/edit', ['id' => $model->getEntityId()]);
                    return;
                }
                $this->_redirect('zeo_checkinout/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id =(int ) $this->getRequest()->getParam('id');
                if (! empty($id)) {
                    $this->_redirect('zeo_checkinout/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('zeo_checkinout/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('zeo_checkinout/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('zeo_checkinout/*/');
    }
}
