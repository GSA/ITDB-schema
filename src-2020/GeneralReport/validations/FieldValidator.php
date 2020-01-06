<?php

namespace Pittsburgh\Controllers\ITDB2\GeneralReport;

require_once("Pittsburgh/Controllers/ITDB2/InvestmentReport/validations/FieldValidator.php");

use \Pittsburgh\Controllers\ITDB2;

class FieldValidator extends \Pittsburgh\Controllers\ITDB2\FieldValidator
{
    private $pdo;
    private $refCodes = array();
    
    /**
     * Validate function. Based on type sent, will call appropriate function to validate field type
     *
     * @param string $val
     * @param string $name
     * @param string $type
     * @param array $params
     * 
     */
    public function validateType(&$val, $name, $type, $params = array()) {
        $res = true;
        if (is_array($val) && $type != 'array') { //either a duplicate or an empty element
            return $res;
        }
        switch ($type) {
        case 'intRange':
            $res = $this->validateIntRange($val, $name, $params);
            break;
        case 'float(11,10)':
            $res = $this->validateFloat('11,10', $val, $name, $params);
            break;
        case 'percentage':
            $res = $this->validatePercentage($val, $name, $params);
            break;
        default:
            $res = parent::validateType($val, $name, $type, $params);
            break;
        }
        
        return $res;
    }

    /**
     * 
     * @param string $val
     * @param type $name
     * @param type $params
     * @param type $max
     * @param type $min
     * @return boolean
     */
    protected function validateIntRange(&$val, $name, $params) {
      if (empty($val) || is_array($val)) {
        $val = '';
        return true;
      }
      $ret = preg_match("/^\d+$/", $val);
      if (!(bool) $ret ||
              (isset($params['max']) && (int)$val > (int)$params['max']) ||
              (isset($params['min']) && (int)$val < (int)$params['min'])) {
        return "000455-" . $this->errorType . ": " . $params['predicate'] . " Field $name with value $val is an invalid integer format: All entered characters must only be digits ranging from ".$params['min']." to ".$params['max'];
      }
      return true;
    }

    /**
     * Validate a field with provided regex
     *
     * @param string $val
     * @param string $name
     *
     */
    protected function validateFloat($type, &$val, $name, $params) {
        $regex = '/^\d{1,2}([.]\d{0,1})?$/';  //init regex
        if ($type === '3,1') {
            $regex = '/^\d{1,2}([.]\d{0,1})?$/';
            $description = 'Expected is a decimal value with at most 2 integers before the decimal and one after.';
        }
        if ($type === '11,10') {
            $regex = '/^\d{1,10}([.]\d{0,10})?$/';
            $description = 'Expected is a decimal value with at most 10 integers before the decimal and 10 after.';
        }
        $ret = preg_match($regex, $val);
        if(!(bool)$ret) {
            return "000869-".$this->errorType.": Field $name with value $val is an invalid format: ".$description;

        }
        return true;
    }

    /**
     * Validates a percentage 0 to 100
     * 
     * @param string $val user value
     * @param string $name field name
     * @param array $params
     * @return boolean|string true or error message
     */
    protected function validatePercentage(&$val, $name, $params) {
        if(preg_match("/^(0|(0?\.\d{1,2})?|1(\.0{1,2})?)$/", $val) !== 1) {
            return "000869-".$this->errorType.": Field $name with value $val is not a percentage (0.00 to 1.00).";

        }
        return true;
    }
}

