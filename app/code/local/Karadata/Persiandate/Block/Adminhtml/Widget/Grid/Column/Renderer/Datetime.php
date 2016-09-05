<?php


class Karadata_Persiandate_Block_Adminhtml_Widget_Grid_Column_Renderer_Datetime extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime {

    public function render(Varien_Object $row) {
        if ($data = $this->_getValue($row)) {

            return Mage::helper('core')->formatDate($data);
//            $timezone = new DateTimeZone('UTC');
//            $format = 'Y-m-d H:i:s';
//            $date = DateTime::createFromFormat($format, $data, $timezone);
//            $timestamp = $date->format('U');
//            $date = DateTime::createFromFormat('Y-m-d H:i:s', $data, new DateTimeZone('UTC'));
//            var_dump($date->format('e'), 'U->', $date->format('U'), strtotime($data));
//            $format = $this->_getFormat();
//            try {
//                $data = Mage::app()->getLocale()
//                                ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
//            } catch (Exception $e) {
//                $data = Mage::app()->getLocale()
//                                ->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
//            }
//            return $data;
        }
        return $this->getColumn()->getDefault();
    }

}
