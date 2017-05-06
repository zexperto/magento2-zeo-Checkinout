<?php

namespace Zeo\Checkinout\Controller\Index;

class CheckinPost extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var Validator
     */
    protected $formKeyValidator;
    
    protected $_dayCollectionFactory;
    
    protected $_hourCollectionFactory;
    
    protected $_helper;
    
    
    /**
     * @param Context $context
     * @param Session $customerSession
     * @param Validator $formKeyValidator
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Zeo\Checkinout\Model\ResourceModel\Day\CollectionFactory $dayCollectionFactory,
        \Zeo\Checkinout\Model\ResourceModel\Hour\CollectionFactory $hourCollectionFactory,
        \Zeo\Checkinout\Helper\Data $dataHelper
    ) {
                parent::__construct($context);
                $this->session = $customerSession;
                $this->formKeyValidator = $formKeyValidator;
                $this->_dayCollectionFactory = $dayCollectionFactory;
                $this->_hourCollectionFactory = $hourCollectionFactory;
                $this->_helper = $dataHelper;
    }
    
    
    
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $validFormKey = $this->formKeyValidator->validate($this->getRequest());

        if ($validFormKey && $this->getRequest()->isPost()) {
            //$form_key = $this->getRequest()->getParam("form_key");
            echo  $this->_helper->doCheckIn();
        }
        
       //return $resultRedirect->setPath('*/*/index');
    }
    
}