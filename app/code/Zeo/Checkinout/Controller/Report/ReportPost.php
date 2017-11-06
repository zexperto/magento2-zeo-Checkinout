<?php

namespace Zeo\Checkinout\Controller\Report;

class ReportPost extends \Magento\Customer\Controller\AbstractAccount
{
    protected $_customerSession;

    
    /**
     * @param Context $context
     * @param Session $customerSession
     * @param Validator $formKeyValidator
     */
    public function __construct(
            \Magento\Framework\App\Action\Context $context,
            \Magento\Customer\Model\Session $customerSession
            ) {
                parent::__construct($context);
                $this->_customerSession= $customerSession;
    }
                
    
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($post);
        
        $_customerSession = $this->_objectManager->create('\Magento\Customer\Model\Session');
        $_customerSession->setReportFormData($post);
       
        $this->_redirect('*/*/');
    }
}