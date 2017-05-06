<?php
    
namespace Zeo\Checkinout\Block\Adminhtml\Hour\Edit\Tab;
    
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use Zeo\Checkinout\Model\System\Config\Status;
use Zeo\Checkinout\Model\System\Config\Type;
use Zeo\Checkinout\Model\System\Config\Days;
    
class Main extends Generic implements TabInterface
{

    protected $_status;
    protected $_type;
    protected $_days;
    
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Status $status,
        Type $type,
        Days $days,
        array $data = []
    ) {
        $this->_status = $status;
        $this->_type= $type;
        $this->_days = $days;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    
   /**
    *
    * {@inheritdoc}
    */
    public function getTabLabel()
    {
        return __('Item Information');
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function getTabTitle()
    {
        return __('Item Information');
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function canShowTab()
    {
        return true;
    }
                
   /**
    *
    * {@inheritdoc}
    */
    public function isHidden()
    {
        return false;
    }
                
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_zeo_checkinout_hour');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Item Information')
        ]);
        if ($model->getEntityId()) {
            $fieldset->addField('entity_id', 'hidden', [
              'name' => 'id'
             ]);
        }
        
        $fieldset->addField('day_id', 'select', [
            'name' => 'day_id',
            'required' => true,
            'label' => __('Day'),
            'title' => __('Day'),
            'options' => $this->_days->toOptionArray()
            ]);

        $fieldset->addField('start_time', 'text', [
            'name' => 'start_time',
            'required' => true,
            'label' => __('Start Time'),
            'title' => __('Start Time'),
            ]);

        $fieldset->addField('end_time', 'text', [
            'name' => 'end_time',
            'required' => true,
            'label' => __('End Time'),
            'title' => __('End Time'),
            ]);
        
        $fieldset->addField('total_time', 'text', [
            'name' => 'total_time',
            'required' => false,
            'label' => __('Total Time'),
            'title' => __('Total Time'),
        ]);

        $fieldset->addField('total', 'text', [
            'name' => 'total',
            'required' => false,
            'label' => __('Total'),
            'title' => __('Total'),
            'class' => 'validate-zero-or-greater'
            ]);
                
        $fieldset->addField('type', 'select', [
            'name' => 'type',
            'label' => __('Type'),
            'options' => $this->_type->toOptionArray()
        ]);
        
        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'options' => $this->_status->toOptionArray()
        ]);
                
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
