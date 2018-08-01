<?php

class MageProfis_RichSnippets_Model_Adminhtml_System_Config_Source_Mode
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'magento', 'label' => Mage::helper('adminhtml')->__('Magento')),
            array('value' => 'trustedshops', 'label' => Mage::helper('adminhtml')->__('Trusted Shops')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'magento' => Mage::helper('adminhtml')->__('Magento'),
            'trustedshops' => Mage::helper('adminhtml')->__('Trusted Shops'),
        );
    }

}
