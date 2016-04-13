<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Html
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Html
 * @author __AUTHOR__
 */
class Pelican_Html_Extract {
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $html = '';
    
    /**
     * __DESC__
     *
     * @access private
     * @var __TYPE__
     */
    private $_return = array();
    
    /**
     * __DESC__
     *
     * @access private
     * @var __TYPE__
     */
    private $_counter = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $buttonCounter = '';
    
    /**
     * __DESC__
     *
     * @access private
     * @var __TYPE__
     */
    private $_uniqueId = '';
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $aAttr = array('common' => array('id', 'name', 'class', 'value', 'style', 'type', 'src'), 'meta' => array('content', 'http-equiv'), 'a' => array('href', 'target'), 'link' => array('href', 'media'));
    
    /**
     * Html Pelican_Form parser
     *
     * @access public
     * @param string $html The actual Pelican_Html string
     * @return array
     */
    public function __construct($html) {
        if (is_array($html)) {
            $this->html = join('', $html);
        } else {
            $this->html = $html;
        }
        $this->_return = array();
        $this->_counter = array();
        $this->buttonCounter = 0;
        $this->_uniqueId = md5(time());
    }
    
    /**
     * Parses the tags
     *
     * @access public
     * @param __TYPE__ $type __DESC__
     * @param string $mandatoryAttr (option) __DESC__
     * @return string
     */
    public function getTags($type, $mandatoryAttr = '') {
        $type = strtolower($type);
        if ($type == 'form') {
            return $this->getFormTags();
        } elseif ($type == 'head') {
            return $this->getGlobalTag('head');
        } elseif ($type == 'body') {
            return $this->getGlobalTag('body');
        } else {
            $aAttr = $this->aAttr['common'];
            if ($this->aAttr[$type]) {
                $aAttr = array_merge($aAttr, $this->aAttr[$type]);
            }
            if (preg_match_all("/<" . $type . ".*>(.*)</isU", $this->html, $tags)) {
                foreach($tags[0] as $tag) {
                    $return = array();
                    $use = (empty($mandatoryAttr) ? true : false);
                    foreach($aAttr as $attr) {
                        $val = $this->_getAttr($attr, $tag);
                        if ($val) {
                            $return['attrs'][$attr] = $val;
                            if (!empty($mandatoryAttr)) {
                                if ($mandatoryAttr == $attr) {
                                    $use = true;
                                }
                            }
                        }
                    }
                    if ($use) {
                        $this->_return[$type][$this->_counter[$type]] = $return;
                    }
                    $this->_counter[$type]++;
                }
            }
            return $this->_return[$type];
        }
    }
    
    /**
     * Parses the forms
     *
     * @access public
     * @return string
     */
    public function getFormTags() {
        if (preg_match_all("/<form.*>.+<\/form>/isU", $this->html, $forms)) {
            foreach($forms[0] as $form) {
                $this->buttonCounter = 0;
                //form details
                preg_match("/<form.*?name=[\"']?([\w\s-]*)[\"']?[\s>]/i", $form, $form_name);
                if ($form_name) {
                    $this->_return['form'][$this->_counter['form']]['attrs']['name'] = preg_replace("/[\"'<>]/", "", $form_name[1]);
                }
                preg_match("/<form.*?action=\"(.*?)\"|'(.*?)'?[\s]>/is", $form, $action);
                if ($action) {
                    $this->_return['form'][$this->_counter['form']]['attrs']['action'] = preg_replace("/[\"'<>]/", "", $action[1]);
                }
                preg_match("/<form.*?method=[\"']?([\w\s]*)[\"']?[\s>]/i", $form, $method);
                if ($method) {
                    $this->_return['form'][$this->_counter['form']]['attrs']['method'] = preg_replace("/[\"'<>]/", "", $method[1]);
                }
                preg_match("/<form.*?enctype=(\"([^\"]*)\"|'([^']*)'|[^>\s]*)([^>]*)?>/i", $form, $enctype);
                if ($enctype) {
                    $this->_return['form'][$this->_counter['form']]['attrs']['enctype'] = preg_replace("/[\"'<>]/", "", $enctype[1]);
                }
                preg_match("/<form.*?id=[\"']?([\w\s-]*)[\"']?[\s>]/i", $form, $id);
                if ($id) {
                    $this->_return['form'][$this->_counter['form']]['attrs']['id'] = preg_replace("/[\"'<>]/", "", $id[1]);
                }
                // Pelican_Form elements: input type = hidden
                if (preg_match_all("/<input[^<>]+type=[\"']hidden[\"'][^<>]+>/iU", $form, $hiddens)) {
                    foreach($hiddens[0] as $hidden) {
                        $this->_return['form'][$this->_counter['form']]['form_elements'][$this->_getName($hidden) ] = array('type' => 'hidden', 'value' => $this->_getValue($hidden));
                    }
                }
                // Pelican_Form elements: input type = text
                if (preg_match_all("/<input[^<>]+type=[\"']text[\"'][^<>]+>/iU", $form, $texts)) {
                    foreach($texts[0] as $text) {
                        $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($text) ] = array('type' => 'text', 'value' => $this->_getValue($text), 'id' => $this->_getId($text), 'class' => $this->_getClass($text));
                    }
                }
                // Pelican_Form elements: input type = Pelican_Security_Password
                if (preg_match_all("/<input[^<>]+type=[\"']password[\"'][^<>]+>/iU", $form, $passwords)) {
                    foreach($passwords[0] as $password) {
                        $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($password) ] = array('type' => 'password', 'value' => $this->_getValue($password));
                    }
                }
                // Pelican_Form elements: textarea
                if (preg_match_all("/<textarea.*>.*<\/textarea>/isU", $form, $textareas)) {
                    foreach($textareas[0] as $textarea) {
                        preg_match("/<textarea.*>(.*)<\/textarea>/isU", $textarea, $textarea_value);
                        $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($textarea) ] = array('type' => 'textarea', 'value' => $textarea_value[1]);
                    }
                }
                // Pelican_Form elements: input type = checkbox
                if (preg_match_all("/<input[^<>]+type=[\"']checkbox[\"'][^<>]+>/iU", $form, $checkboxes)) {
                    foreach($checkboxes[0] as $checkbox) {
                        if (preg_match("/checked/i", $checkbox)) {
                            $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($checkbox) ] = array('type' => 'checkbox', 'value' => 'on');
                        } else {
                            $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($checkbox) ] = array('type' => 'checkbox', 'value' => '');
                        }
                    }
                }
                // Pelican_Form elements: input type = radio
                if (preg_match_all("/<input[^<>]+type=[\"']radio[\"'][^<>]+>/iU", $form, $radios)) {
                    foreach($radios[0] as $radio) {
                        if (preg_match("/checked/i", $radio)) {
                            $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($radio) ] = array('type' => 'radio', 'value' => $this->_getValue($radio));
                        }
                    }
                }
                // Pelican_Form elements: input type = submit
                if (preg_match_all("/<input[^<>]+type=[\"']submit[\"'][^<>]+>/iU", $form, $submits)) {
                    foreach($submits[0] as $submit) {
                        $this->_return['form'][$this->_counter['form']]['buttons'][$this->buttonCounter] = array('type' => 'submit', 'name' => $this->_getName($submit), 'value' => $this->_getValue($submit));
                        $this->buttonCounter++;
                    }
                }
                // Pelican_Form elements: input type = Pelican_Index_Backoffice_Button
                if (preg_match_all("/<input[^<>]+type=[\"']button[\"'][^<>]+>/iU", $form, $buttons)) {
                    foreach($buttons[0] as $button) {
                        $this->_return['form'][$this->_counter['form']]['buttons'][$this->buttonCounter] = array('type' => 'button', 'name' => $this->_getName($button), 'value' => $this->_getValue($button));
                        $this->buttonCounter++;
                    }
                }
                // Pelican_Form elements: input type = reset
                if (preg_match_all("/<input[^<>]+type=[\"']reset[\"'][^<>]+>/iU", $form, $resets)) {
                    foreach($resets[0] as $reset) {
                        $this->_return['form'][$this->_counter['form']]['buttons'][$this->buttonCounter] = array('type' => 'reset', 'name' => $this->_getName($reset), 'value' => $this->_getValue($reset));
                        $this->buttonCounter++;
                    }
                }
                // Pelican_Form elements: input type = image
                if (preg_match_all("/<input[^<>]+type=[\"']image[\"'][^<>]+>/iU", $form, $images)) {
                    foreach($images[0] as $image) {
                        $this->_return['form'][$this->_counter['form']]['buttons'][$this->buttonCounter] = array('type' => 'image', 'name' => $this->_getName($image), 'value' => $this->_getValue($image));
                        $this->buttonCounter++;
                    }
                }
                // input type=select entries
                // Here I have to go on step around to grep at first all select names and then
                // the content. Seems not to work in an other way
                if (preg_match_all("/<select.*>.+<\/select>/isU", $form, $selects)) {
                    foreach($selects[0] as $select) {
                        if (preg_match_all("/<option.*>.+<\/option>/isU", $select, $all_options)) {
                            foreach($all_options[0] as $option) {
                                if (preg_match("/selected/i", $option)) {
                                    if (preg_match("/value=[\"'](.*)[\"']\s/iU", $option, $option_value)) {
                                        $option_value = $option_value[1];
                                        $found_selected = 1;
                                    } else {
                                        preg_match("/<option.*>(.*)<\/option>/isU", $option, $option_value);
                                        $option_value = $option_value[1];
                                        $found_selected = 1;
                                    }
                                }
                            }
                            if (!isset($found_selected)) {
                                if (preg_match("/value=[\"'](.*)[\"']/iU", $all_options[0][0], $option_value)) {
                                    $option_value = $option_value[1];
                                } else {
                                    preg_match("/<option>(.*)<\/option>/iU", $all_options[0][0], $option_value);
                                    $option_value = $option_value[1];
                                }
                            } else {
                                unset($found_selected);
                            }
                            $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($select) ] = array('type' => 'select', 'value' => trim($option_value));
                        }
                    }
                }
                // Pelican_Form elements: input type = --not defined--
                if (preg_match_all("/<input[^<>]+name=[\"'](.*)[\"'][^<>]+>/iU", $form, $inputs)) {
                    foreach($inputs[0] as $input) {
                        if (!preg_match("/type=(\"([^\"]*)\"|'([^']*)'|[^>\s]*)([^>]*)?>/is", $input)) {
                            if (!isset($this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($input) ])) {
                                $this->_return['form'][$this->_counter['form']]['elements'][$this->_getName($input) ] = array('type' => 'text', 'value' => $this->_getValue($input), 'id' => $this->_getId($input), 'class' => $this->_getClass($input));
                            }
                        }
                    }
                }
                // Update the Pelican_Form counter if we have more then 1 Pelican_Form in the Pelican_Html table
                $this->_counter['form']++;
            }
        }
        return $this->_return['form'];
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $type __DESC__
     * @return __TYPE__
     */
    public function getGlobalTag0($type) {
        if (!substr_count($this->html, '<head')) {
            $this->html = str_replace('<body', '</head><body', $this->html);
            $this->html = str_replace('<html', '<html><head><', $this->html);
            $this->html = str_replace('< ', '<', $this->html);
        }
        if (!$this->htmlDom) {
            $this->html = preg_replace('/(\s)\/\/(.*)(\s)/', '\\1/* \\2 */\\3', $this->html);
            $this->htmlDom = str_get_html($this->html);
        }
        $this->_return[$type] = $this->htmlDom->find($type, 0)->innertext;
        return $this->_return[$type];
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $type __DESC__
     * @return __TYPE__
     */
    public function getGlobalTag($type) {
        $this->html = preg_replace('/(\s)\/\/(.*)(\s)/', '\\1/* \\2 */\\3', $this->html);
        if (!$this->_return[$type]) {
            if (!substr_count($this->html, '<head')) {
                $this->html = str_replace('<body', '</head><body', $this->html);
                $this->html = str_replace('<html', '<html><head><', $this->html);
                $this->html = str_replace('< ', '<', $this->html);
            }
            $this->html = str_replace("<" . $type, "\n<" . $type, $this->html);
            $this->html = str_replace("</" . $type . ">", "\n</" . $type . ">", $this->html);
            $pattern = '#<' . $type . '[^>]*>(.*?)<\/' . $type . '>#si' . ($type == 'body' ? 'U' : '');
            preg_match($pattern, $this->html, $temp);
            if (!$temp && $type == 'head') {
                $pattern = '#<html(.*?)[^>]*>(.*?)<body[^>]*>(.*?)<\/body>(.*?)</html>#siU';
                preg_match($pattern, $this->html, $temp2);
                if ($temp2[2]) {
                    $temp[1] = $temp2[2];
                }
            }
            $this->_return[$type] = $temp[1];
            //directdebug($this->_return[$type], $type);
            
        }
        return $this->_return[$type];
    }
    
    /**
     * __DESC__
     *
     * @access private
     * @param __TYPE__ $attr __DESC__
     * @param string $string __DESC__
     * @return __TYPE__
     */
    public function _getAttr($attr, $string) {
        if (preg_match("/" . $attr . "=(\"([^\"]*)\"|'([^']*)'|[^>\s]*)([^>]*)?>/is", $string, $match)) {
            $val_match = trim($match[1]);
            $val_match = trim($val_match, "\"\'");
            unset($string);
            return trim($val_match, '"');
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $name __DESC__
     * @param __TYPE__ $arguments __DESC__
     * @return __TYPE__
     */
    public function __call($name, $arguments) {
        $pref = substr($name, 0, 4);
        if ($pref == '_get') {
            return self::_getAttr(strtolower(str_replace('_get', '', $name)), implode('', $arguments));
        } else {
            return false;
        }
    }
}
