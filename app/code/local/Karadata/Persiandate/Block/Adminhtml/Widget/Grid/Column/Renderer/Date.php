<?php


class Karadata_Persiandate_Block_Adminhtml_Widget_Grid_Column_Renderer_Date extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Date {

    public function render(Varien_Object $row) {
        if ($data = $row->getData($this->getColumn()->getIndex())) {
            return Mage::helper('core')->formatDate($data);

//            $format = $this->_getFormat();
//            try {
//                if ($this->getColumn()->getGmtoffset()) {
//                    $data = Mage::app()->getLocale()
//                                    ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
//                } else {
//                    $data = Mage::getSingleton('core/locale')
//                                    ->date($data, Zend_Date::ISO_8601, null, false)->toString($format);
//                }
//            } catch (Exception $e) {
//                if ($this->getColumn()->getTimezone()) {
//                    $data = Mage::app()->getLocale()
//                                    ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
//                } else {
//                    $data = Mage::getSingleton('core/locale')->date($data, null, null, false)->toString($format);
//                }
//            }
//            return $data;
        }
        return $this->getColumn()->getDefault();
    }

}
