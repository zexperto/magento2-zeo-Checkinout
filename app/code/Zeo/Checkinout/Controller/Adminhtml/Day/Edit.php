<?php
    
namespace Zeo\Checkinout\Controller\Adminhtml\Day;
    
class Edit extends \Zeo\Checkinout\Controller\Adminhtml\Day
{
    
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Zeo\Checkinout\Model\Day');
        if ($id) {
            $model->load($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('zeo_checkinout/*');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $resultPage = $this->resultPageFactory->create();
        if ($id) {
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Items Entry'));
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('Add Items Entry'));
        }
    
        $this->_coreRegistry->register('current_zeo_checkinout_day', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('day_day_edit');
        $this->_view->renderLayout();
    }
}
