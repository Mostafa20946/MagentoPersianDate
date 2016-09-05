<?php


class Karadata_Persiandate_Block_Adminhtml_Report_Sales_Grid_Column_Renderer_Date extends
Mage_Adminhtml_Block_Report_Sales_Grid_Column_Renderer_Date {

    public function render(Varien_Object $row) {
        if ($data = $row->getData($this->getColumn()->getIndex())) {
            
            return Mage::helper('core')->formatDate($data);

//            switch ($this->getColumn()->getPeriodType()) {
//                case 'month' :
//                    $dateFormat = 'yyyy-MM';
//                    break;
//                case 'year' :
//                    $dateFormat = 'yyyy';
//                    break;
//                default:
//                    $dateFormat = Varien_Date::DATE_INTERNAL_FORMAT;
//                    break;
//            }
//
//            $format = $this->_getFormat();
//            try {
//                $data = ($this->getColumn()->getGmtoffset()) ? Mage::app()->getLocale()->date($data, $dateFormat)->toString($format) : Mage::getSingleton('core/locale')->date($data, Zend_Date::ISO_8601, null, false)->toString($format);
//            } catch (Exception $e) {
//                $data = ($this->getColumn()->getTimezone()) ? Mage::app()->getLocale()->date($data, $dateFormat)->toString($format) : Mage::getSingleton('core/locale')->date($data, $dateFormat, null, false)->toString($format);
//            }
//            return $data;
        }
        return $this->getColumn()->getDefault();
    }

}
