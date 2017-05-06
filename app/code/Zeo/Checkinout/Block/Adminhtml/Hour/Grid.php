<?php
    
namespace Zeo\Checkinout\Block\Adminhtml\Hour;
    
use Zeo\Checkinout\Model\System\Config\Status;
use Zeo\Checkinout\Model\System\Config\Type;
use Zeo\Checkinout\Model\System\Config\Days;
use Magento\Framework\Exception;
    
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
     
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
    protected $_type;
    protected $_days;
    protected $_collectionFactory;
    
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Zeo\Checkinout\Model\ResourceModel\Hour\CollectionFactory $collectionFactory,
        //\Zeo\Checkinout\Model\ResourceModel\Hour\Collection $collectionFactory,
        Status $status,
        Type $type,
        Days $days,
        array $data = []
    ) {

        $this->_status = $status;
        $this->_type= $type;
        $this->_days = $days;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();
        $this->setId('hourGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }
                
    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId =(int )$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }
                
    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        //$collection = $this->_collectionFactory->load();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
                
    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {

        $this->addColumn('entity_id', [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'entity_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);
        
        $this->addColumn('day_id', [
            'header' => __('Day'),
            'index' => 'day_id',
            'class' => 'day_id',
            'type' => 'options',
            'options' => $this->_days->toOptionArray()
            ]);

        $this->addColumn('start_time', [
            'header' => __('Start Time'),
            'index' => 'start_time',
            'class' => 'start_time'
            ]);

        $this->addColumn('end_time', [
            'header' => __('End Time'),
            'index' => 'end_time',
            'class' => 'end_time'
            ]);
        $this->addColumn('total_time', [
            'header' => __('Total Time'),
            'index' => 'total_time',
            'class' => 'total_time'
        ]);
        
        $this->addColumn('total', [
            'header' => __('Total'),
            'index' => 'total',
            'class' => 'total'
            ]);
           
        $this->addColumn('type', [
            'header' => __('Type'),
            'index' => 'type',
            'class' => 'type',
            'type' => 'options',
            'options' => $this->_type->toOptionArray()
        ]);
        
        $this->addColumn('status', [
            'header' => __('Status'),
            'index' => 'status',
            'class' => 'status',
            'type' => 'options',
            'options' => $this->_status->toOptionArray()
        ]);
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        return parent::_prepareColumns();
    }
                
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('entity_id');
        $this->getMassactionBlock()->addItem('delete', [
            'label' => __('Delete'),
            'url' => $this->getUrl('zeo_checkinout/*/massDelete'),
            'confirm' => __('Are you sure?')
        ]);
        return $this;
    }
    
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('zeo_checkinout/*/index', ['_current' => true]);
    }
    
    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('zeo_checkinout/*/edit', [
            'store' => $this->getRequest()->getParam('store'),
            'id' => $row->getEntityId()
        ]);
    }
}
