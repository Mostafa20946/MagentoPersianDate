<?php

class Karadata_Persiandate_Model_Observer {

//inputs to sanitize in html output code and/also the name of inputs in post array or filter string

    public $postInputs = array(
        'general' => array(// category page
            'custom_design_from', 'custom_design_to'
        ),
        'design' => array(
            'date_from', 'date_to'
        ),
        'product' => array(
            'special_from_date', 'special_to_date', 'news_from_date', 'news_to_date',
            'custom_design_from', 'custom_design_to'
        ),
        'COMMON' => array(// system -> Design
            'to_date', 'from_date', 'from', 'to'
        )
    );
    public $htmlInputs = array(
        'from_date[from]', //in advertisment -> price rules
        'from_date[to]', //in advertisment -> price rules
        'to_date[from]', //in advertisment -> price rules
        'to_date[to]', //in advertisment -> price rules
        'customer_since[to]', //customer page
        'customer_since[from]', //customer page
        'created_at[to]',
        'created_at[from]', //sales > order
        'order_created_at[to]', //sales > order
        'order_created_at[from]',
        'date_to[to]', //system->design
        'date_to[from]', //system->design
        'date_from[to]', //system->design
        'date_from[from]', //system->design
        'time[from]', //system tools support
        'time[to]', //system tools support
        'updated_at[from]', //sales > recurring profiles
        'updated_at[to]', //sales > recurring profiles
        'sitemap_time[from]', //catalog> sitemap
        'sitemap_time[to]', //catalog> sitemap
        'session_start_time[from]', //customer > online
        'session_start_time[to]', //customer > online
        'last_activity[from]', //customer > online
        'last_activity[to]', //customer > online
        'added_at[from]', //newletter
        'added_at[to]', //newletter
        'modified_at[from]', //newletter
        'modified_at[to]', //newletter
        'start_at[from]', //newletter
        'start_at[to]', //newletter
        'finish_at[from]', //newletter
        'finish_at[to]', //newletter
        'queue_start[from]', //newletter
        'queue_start[to]', //newletter
        'creation_time[from]', //cms
        'creation_time[to]', //cms
        'update_time[from]', //cms
        'update_time[to]', //cms
        'date_posted[from]', //cms > vote
        'date_posted[to]', //cms > vote
        'date_closed[from]', //cms > vote
        'date_closed[to]', //cms > vote
        'report_from', //reports > most sold
        'report_to', //reports > most sold
    );

    function __construct() {
        //add post data like inputs to existing html inputs
        foreach ($this->postInputs as $groupName => $items) {
            foreach ($items as $item)
                if ($groupName == 'COMMON')
                    $this->htmlInputs[] = $item;
                else
                    $this->htmlInputs[] = $groupName . '[' . $item . ']';
        }
    }

    public function beforeHtmlEcho(Varien_Event_Observer $observer) {

        $html = $observer->getTransport()->getHtml();
        $htmlEditdFlag = false;
        if (empty($html))
            return;
        libxml_use_internal_errors(TRUE);
        $dom = new DOMDocument('1.0', 'UTF-8');
// must use php >= 5.4 and libxml >= 2.6
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);

        foreach ($this->htmlInputs as $input) {
            $elements = $xpath->query("//input[@name='$input']");
            if ($elements->length) {
                foreach ($elements as $element) {
                    if ($element->getAttribute('converted') == 1)
                        return; //it means the page proccessed before so skipp all
                    $oldDate = $element->getAttribute('value');
                    $newDate = Mage::helper('core')->toJalali($element->getAttribute('value'));
// alternative to change dom procedure
// $html = str_replace($oldDate, $newDate . '" converted="1', $html);
                    if (!empty($newDate)) {
                        $htmlEditdFlag = true;
                        $element->setAttribute('value', $newDate);
                        $element->setAttribute('converted', '1');
                    }
                }
            }
        }
        if ($htmlEditdFlag) {
            $html = $dom->saveHTML();
            $observer->getTransport()->setHtml($html);
        }

//          USEFULL SNIPPETS
//          
//        Mage::app()->getFrontController()->getRequest()->isXmlHttpRequest();
//        $dom->removeChild($dom->firstChild);
//        $dom->replaceChild($dom->firstChild->firstChild, $dom->firstChild);
//        $el = $xpath->query("//body")->item(0);
//        $html = $dom->saveHTML($el);
//
//        $html = preg_replace(array("/^\<\!DOCTYPE.*?<html><body>/si",
//            "!</body></html>$!si"), "", $dom->saveHTML());
//
//        foreach ($xpath->query('body/*') as $item)
//            $new.= $dom->saveHTML($item);
    }

    function preDispatch(Varien_Event_Observer $observer) {

//grid filter pages
        $filter = !empty(Mage::app()->getFrontController()->getRequest()->getParams()['filter']) ? Mage::app()->getFrontController()->getRequest()->getParams()['filter'] : false;

        if (!empty($filter)) {
            $filter = urldecode(base64_decode($filter));
            $filters = explode('&', $filter);
            foreach ($filters as $f) {
                $t = '';
                $t = explode('=', $f);
                if (in_array($t[0], $this->htmlInputs)) {
                    $newf = str_replace($t[1], Mage::helper('core')->toGregorian($t[1]), $f);
                    $filter = str_replace($f, $newf, $filter);
                }
            }

            Mage::app()->getFrontController()->getRequest()->setParam('filter', base64_encode($filter));
            $filter = Mage::app()->getFrontController()->getRequest()->getParams()['filter'];
            mage::log(urldecode(base64_decode($filter)), null, 'sido');
            return;
        }

//below procedure is for forms that post data so we change date to gregorian

        foreach ($this->postInputs as $handle => $desiredInputs) {
            if ($handle == 'COMMON') {
                $data = !empty(Mage::app()->getFrontController()->getRequest()->getParams()) ? Mage::app()->getFrontController()->getRequest()->getParams() : false;
            } else
                $data = !empty(Mage::app()->getFrontController()->getRequest()->getParams()[$handle]) ? Mage::app()->getFrontController()->getRequest()->getParams()[$handle] : false;

            if (!empty($data)) {
                $newPost = array();
                foreach ($desiredInputs as $input)
                    if (!empty($data[$input])) {
                        $newDate = Mage::helper('core')->toGregorian($data[$input]);
                        $newPost[$input] = $newDate;
                    }
                $mergedNewsPost = array();

                if ($handle == 'COMMON')
                    $mergedNewsPost = array_merge($data, $newPost);
                else
                    $mergedNewsPost[$handle] = array_merge($data, $newPost);

                $mergedNewsPost = array_merge(Mage::app()->getFrontController()->getRequest()->getParams(), $mergedNewsPost);

                Mage::app()->getFrontController()->getRequest()->setPost($mergedNewsPost);
                return;
            }
        }
    }

}
