<?php
    
namespace Zeo\Checkinout\Controller\Adminhtml\Hour;
    
class Index extends \Zeo\Checkinout\Controller\Adminhtml\Hour
{
    
    /**
     * Hour list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Zeo_Checkinout::checkinout');
        $resultPage->getConfig()->getTitle()->prepend(__('Hour'));
        $resultPage->addBreadcrumb(__('Zeo'), __('Zeo'));
        $resultPage->addBreadcrumb(__('Hour'), __('Hour'));
        return $resultPage;
    }
}
