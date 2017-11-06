<?php
    
namespace Zeo\Checkinout\Controller\Adminhtml\Day;
    
class Index extends \Zeo\Checkinout\Controller\Adminhtml\Day
{
    
    /**
     * Day list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Zeo_Checkinout::checkinout');
        $resultPage->getConfig()->getTitle()->prepend(__('Day'));
        $resultPage->addBreadcrumb(__('Zeo'), __('Zeo'));
        $resultPage->addBreadcrumb(__('Day'), __('Day'));
        return $resultPage;
    }
}
