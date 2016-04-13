<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Reader
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
include 'External/Spreadsheet/Excel/Reader.php';

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Reader
 * @author Raphael
 */
class Pelican_Reader_Excel extends Spreadsheet_Excel_Reader
{
    /**
     * __DESC__
     *
     * @access public
     * @param  string   $file                (option) Unknown_type
     * @param  bool     $store_extended_info (option) Unknown_type
     * @param  string   $outputEncoding      (option) Unknown_type
     * @return __TYPE__
     */
    public function __construct($file = '', $store_extended_info = true, $outputEncoding = '')
    {
        parent::__construct($file, $store_extended_info, $outputEncoding);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $count Unknown_type
     * @return string
     */
    public function getColName($count)
    {
        // /$this->colnames;

    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $label Unknown_type
     * @return __TYPE__
     */
    public function getSheetIndex($label)
    {
        foreach($this->boundsheets as $key => $item) if ($item['name'] == $label) return $key;

        return false;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function sheetCount()
    {
        return count($this->boundsheets);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  bool     $row_numbers (option) Unknown_type
     * @param  bool     $col_letters (option) Unknown_type
     * @param  string   $sheet       (option) Unknown_type
     * @param  __TYPE__ $table_class (option) Unknown_type
     * @return __TYPE__
     */
    public function toHtml($row_numbers = false, $col_letters = false, $sheet = 0, $table_class = 'excel')
    {
        $this->dump($row_numbers = false, $col_letters = false, $sheet = 0, $table_class = 'excel');
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  bool     $row_numbers (option) Unknown_type
     * @param  bool     $col_letters (option) Unknown_type
     * @param  string   $sheet       (option) Unknown_type
     * @param  __TYPE__ $table_class (option) Unknown_type
     * @return string
     */
    public function toCsv($row_numbers = false, $col_letters = false, $sheet = 0, $table_class = 'excel')
    {
        $outs = array();
        for ($row = 1;$row <= $this->rowcount($sheet);$row++) {
            $outs_inner = array();
            for ($col = 1;$col <= $this->colcount($sheet);$col++) {
                // Account for Rowspans/Colspans $rowspan = $this->rowspan($row,
                // $col, $sheet); $colspan = $this->colspan($row, $col, $sheet);
                // for ($i=0; $i<$rowspan; $i++) {
                for ($j = 0;$j < $colspan;$j++) {
                    if ($i > 0 || $j > 0) {
                        $this->sheets[$sheet]['cellsInfo'][$row + $i][$col + $j]['dontprint'] = 1;
                    }
                }
            }
            if (!$this->sheets[$sheet]['cellsInfo'][$row][$col]['dontprint']) {
                $val = $this->val($row, $col, $sheet);
                $val = ($val == '') ? '' : addslashes(htmlentities($val));
                $outs_inner = "\"" . $val . "\"";
                $outs_inner = $val;
            }
        }
        $outs = implode(',', $outs_inner);
        $out = implode("\n", $outs);

        return ($out);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  string   $sheet (option) __DESC__
     * @return __TYPE__
     */
    public function toArray($sheet = 0)
    {
        $arr = array();
        for ($row = 1;$row <= $this->rowcount($sheet);$row++) for ($col = 1;$col <= $this->colcount($sheet);$col++) $arr[$row][$col] = $this->val($row, $col, $sheet);

        return $arr;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  string   $sheet      (option) __DESC__
     * @param  __TYPE__ $thRow      (option) __DESC__
     * @param  __TYPE__ $ignoreCols (option) __DESC__
     * @param  __TYPE__ $ignoreRows (option) __DESC__
     * @return __TYPE__
     */
    public function queryTab($sheet = 0, $thRow = 1, $ignoreCols = array(), $ignoreRows = array())
    {
        $arr = array();
        for ($row = 1;$row <= $this->rowcount($sheet);$row++) if (!in_array($row, $ignoreRows)) {
            for ($col = 1;$col <= $this->colcount($sheet);$col++) if (!in_array($col, $ignoreCols)) {
                if ($thRow) {
                    if ($thRow == $row) {
                        $th[$col] = $this->val($row, $col, $sheet);
                    } else {
                        $arr[$row][$th[$col]] = $this->val($row, $col, $sheet);
                    }
                } else {
                    $arr[$row][$col] = $this->val($row, $col, $sheet);
                }
            }
        }

        return $arr;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  string   $sheet (option) __DESC__
     * @return __TYPE__
     */
    public function dumpToAssocArray($sheet = 0)
    {
        $colNames = array();
        for ($col = 1;$col <= $this->colcount($sheet);$col++) $colNames[$col] = strtolower($this->val(1, $col, $sheet));
        $arr = array();
        for ($row = 2;$row <= $this->rowcount($sheet);$row++) for ($col = 1;$col <= $this->colcount($sheet);$col++) {
            $colNameTemp = $colNames[$col];
            $arr[$row][$colNameTemp] = $this->val($row, $col, $sheet);
        }

        return $arr;
    }
}
