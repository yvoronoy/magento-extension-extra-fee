<?php
class Voronoy_ExtraFee_Block_Frontend_Model_Extra_Fee_Payment
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_itemRenderer;

    public function _prepareToRender()
    {
        $this->addColumn('payment_code', array(
            'label' => 'Payment Method',
            'renderer' => $this->_getRenderer(),
            'style' => 'width:150px',
        ));
        $this->addColumn('amount', array(
            'label' => 'Fee Amount (%)',
            'style' => 'width:100px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = 'Add Payment';
    }

    protected function  _getRenderer()
    {
        if (!$this->_itemRenderer) {
            $this->_itemRenderer = $this->getLayout()->createBlock(
                'voronoy_extrafee/config_adminhtml_form_field_payment', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getRenderer()
                ->calcOptionHash($row->getData('payment_code')),
            'selected="selected"'
        );
    }
}