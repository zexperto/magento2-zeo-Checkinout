<?php

namespace Zeo\Checkinout\Controller\Index;

class CheckoutPost extends \Magento\Customer\Controller\AbstractAccount
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
            $type = $this->getRequest()->getParam("type");
            echo  $this->_helper->doCheckOut($type);
        }
        
       //return $resultRedirect->setPath('*/*/index');
    }
   
}