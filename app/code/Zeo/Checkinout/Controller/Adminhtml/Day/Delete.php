<?php
    
namespace Zeo\Checkinout\Controller\Adminhtml\Day;
    
class Delete extends \Zeo\Checkinout\Controller\Adminhtml\Day
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Zeo\Checkinout\Model\Day');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('zeo_checkinout/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('zeo_checkinout/*/edit', [
                        'id' => $this->getRequest()->getParam('id')
                ]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('zeo_checkinout/*/');
    }
}
