<?php

class Ambimax_SetDefaultCaching_Block_Adminhtml_System_Cacheinfo
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_template = 'ambimax/setdefaultcaching/system/cacheinfo.phtml';

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) // @codingStandardsIgnoreLine
    {
        $this->setTemplate($this->_template);
        return $this->_toHtml();
    }
}