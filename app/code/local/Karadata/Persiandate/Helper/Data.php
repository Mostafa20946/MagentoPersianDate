<?php

require_once(Mage::getBaseDir('lib') . '/class.jdatetime.php');

class Karadata_Persiandate_Helper_Data extends Mage_Core_Helper_Data {

    public function toJalali($date, $locale = 'fa_IR') {
        if (!strstr($date, '/'))
            return $date;

        if (!empty($date)) {
            $date = explode('/', $date);
            $t = jDateTime::toJalali($date[0], $date[1], $date[2]);

            $t[1] = ( mb_strlen($t[1]) == 1) ? '0' . $t[1] : $t[1];

            return implode('/', array($t[0], $t[1], $t[2]));
        } else
            return false;
    }

    function toGregorian($date, $locale = false) {
        if (!strstr($date, '/'))
            return $date;

        if (!empty($date)) {
            $date = explode('/', $date);
            $t = jDateTime::toGregorian($date[0], $date[1], $date[2]);
            $t[1] = ( mb_strlen($t[1]) == 1) ? '0' . $t[1] : $t[1];
            return implode('/', array($t[0], $t[1], $t[2]));
        } else
            return false;
    }

    public function formatDate($date = null, $format = Mage_Core_Model_Locale::FORMAT_TYPE_SHORT, $showTime = false) {

        return jDateTime::date("l j F y - H:i", strtotime($date . " UTC"), true, true, 'Asia/Tehran');

//        if (!in_array($format, $this->_allowedFormats, true)) {
//            return $date;
//        }
//        if (!($date instanceof Zend_Date) && $date && !strtotime($date)) {
//            return '';
//        }
//        if (is_null($date)) {
//            $date = Mage::app()->getLocale()->date(Mage::getSingleton('core/date')->gmtTimestamp(), null, null);
//        } else if (!$date instanceof Zend_Date) {
//            $date = Mage::app()->getLocale()->date(strtotime($date), null, null);
//        }
//
//        if ($showTime) {
//            $format = Mage::app()->getLocale()->getDateTimeFormat($format);
//        } else {
//            $format = Mage::app()->getLocale()->getDateFormat($format);
//        }
    }

}
