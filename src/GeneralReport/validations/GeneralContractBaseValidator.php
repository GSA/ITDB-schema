<?php

namespace Pittsburgh\Controllers\ITDB2;

require_once("Pittsburgh/Controllers/ITDB2/GeneralReport/models/GeneralDataReportsDetails.php");
require_once("Pittsburgh/Controllers/ITDB2/InvestmentReport/validations/FieldValidator.php");
require_once("Pittsburgh/Lib/Utility/UtilityFunctions.php");

use Pittsburgh\Lib\Utility as Utility;

class GeneralContractBaseValidator
{
  public $parent    = '';
  public $warnings  = array();
  private $refCodes = array();
  static $sqlCache  = array();
  static $ignore    = array('operation', 'comment');
  protected $pdo;
  protected $FieldValidator;
  public $uii;
  protected $errorObj = array();

  /**
   * Not used as of yet but may be needed in the future
   * @param $subSection
   * @param $parent
   */
  static function validateCollection($subSection, $parent) {
      
  }

  /**
   * BaseValidator constructor.
   *
   * @param object $pdo
   * @param string $reportType
   * @param int $reportPrimaryID - contractsReport database table identifier
   */
  public function __construct($pdo, $reportType, $reportUII)
  {
    $this->pdo        = $pdo;
    $this->uii = $reportUII;
    $this->FieldValidator = new FieldValidator($this->pdo, 'CON');
  }

  /**
   * Base validation method, configures object and performs various kinds of validations by default.
   * called by GeneralContractBaseValidator::validateSection
   * @param array $obj - section data
   *
   * @return array - errors and warnings, when applicable
   */
  public function validate(&$obj, $parent = '', $cid = null)
  {
    $this->parent   = $parent;
    $this->errorObj   = [];
    $this->warnings = [];



    // Get the related model and type for this validator
    $models = array_flip(GeneralReportContractsReport::$models);
    $model  = str_replace('Validator', '', get_class($this));
    $class  = preg_replace('/[A-Za-z0-9\\\\]+\\\\([A-Za-z0-9]+)$/', '$1', $model);
    $type   = isset($models[$class]) ? $models[$class] : "";

    // $type = NULL;
    if (!$type) {
      return "000616-CON: The system encountered an unexpected error. (Model type not found)";
      // return self::$errorMessages['000616-CON']['code'].': '.self::$errorMessages['000616-CON']['message']; // ;
    }

    // $this->errorObj[] = "000611-CON: Operation 'get' is not allowed here.";

    // Validate identifiers (e.g. check for duplicates)
    if (class_exists($model)) {
      $error = $model::processIdentifiers($obj, true);
      if (is_string($error) && strlen($error) > 0) {
        $this->errorObj[] = $error;
      }
    }

    $this->performStructuralValidations($obj);
    $this->performSharedValidations($obj);

    // no get operations are validated here.
    if (GeneralReport::$operation == 'get' || GeneralReport::$operationNextContract == 'get') {
        $this->errorObj[] = "000611-CON: Operation 'get' is not allowed here.";
    }
    // Investment level validations
    else if ($type == 'details') {
        if (GeneralReport::$operation == 'add') {
            $this->performAddValidations($obj);
        } else if(GeneralReport::$operation == 'update') {
            $this->performUpdateValidations($obj);
        } else if (GeneralReport::$operation == 'delete') {
            GeneralReport::$deleteEntireInvestment = true;
            $this->performDeleteValidations($obj);
        } else {
        $this->errorObj[] = "000631-CON: Operation '".GeneralReport::$operation."' is not allowed here.";
      }
    } 
    // Contracts level validations
    elseif ($type == 'Contracts') {
      if (GeneralReport::$operationNextContract == 'add') {  // structure changes dealing with Contracts->Contract
        $this->performAddContractsValidations($obj);
      } elseif (GeneralReport::$operationNextContract == 'update') {
        $this->performUpdateContractsValidations($obj);
      } elseif (GeneralReport::$operationNextContract == 'delete') {
        GeneralReport::$deleteEntireInvestment = false;
        $this->performDeleteContractsValidations($obj);
      } else {
        $this->errorObj[] = "000632-CON: Operation '".GeneralReport::$operationNextContract."' is not allowed here".($cid !== null ? " for contract: $cid" : '').'.';
      }
      $this->validateFields($obj, GeneralDataReportsDetails::$fieldList);
    } elseif ($type != 'details' && $type != 'Contracts') {
      //We should never encounter this because operation is required via the schema
      $this->errorObj[] = "000612-CON: Required operation tag is missing.";
    }

    $result = array();
    if (count($this->errorObj) > 0) {
      GeneralReport::$errorCount += count($this->errorObj);
      $result['errors'] = $this->errorObj;
    }
    if (count($this->warnings) > 0) {
      GeneralReport::$warningCount += count($this->warnings);
      $result['warnings'] = $this->warnings;
    }
    return $result;
  }

  /**
   * Implement prototype to validate the structure of a section.
   *
   * @param array $obj - section data
   */
  protected function performStructuralValidations(&$obj)
  {
  }

  /**
   * Prototype to perform validations for 'delete' operations.
   *
   * @param array $obj - section data
   */
  protected function performDeleteValidations(&$obj)
  {
  }

  /**
   *Function to validate a field based on type defined in model.
   *
   * @param array $fields - field list from model with parameters
   */
  protected function validateFields(&$obj, $fields) {
    if (!is_array($fields)) return;
    foreach ($fields as $name => $params) {
      $params['params']['predicate'] = '';
      if (isset($params['type']) && !empty($params['type']) && isset($obj[$name]) && (!empty($obj[$name]) || $obj[$name] == 0)) {
        if (!isset($params['params'])) $params['params'] = '';
        //This is a placeholder in case we implement predicates for Contracts
        $res = $this->FieldValidator->validateType($obj[$name], $name, $params['type'], $params['params']);
        if ($res !== true) {
          if (isset($params['params']['warning'])) {
            $this->warnings[] = $res;
          } else {
            $this->errorObj[] = $res;
          }
        }
      }

      if (isset($params['maxLength']) && !empty($params['maxLength']) && isset($obj[$name]) && !empty($obj[$name])) {
        $res = $this->FieldValidator->validateLength($obj[$name], $name, $params['maxLength']);
        if ($res !== true) {
          $this->errorObj[] = $res;
        }
      }
    }
  }

  /**
   * Determine if this is the first submission of the budget year.
   *
   * @return bool
   */
  public function isFirstAnnualSubmission()
  {

    // If there isn't a submission after August 1, then this must be a first submission.
    // TODO: ITD-698 Change this back to '-08-01'
    $cutoffDate = (BusinessCase::getBudgetYear() - 2) . '-06-01';

    $query = "SELECT datediff(max(createdDate),'".$cutoffDate."') AS diff FROM investmentEvent WHERE contractsReportId=? AND eventType='investment'";

    $param_array = array($this->uii);
    $sth = $this->pdo->prepare($query);
    $sth->execute($param_array);
    $result = 0;
    $row = $sth->fetch();
    if ($row) {
      $result = $row->diff;
    }
    if ($result == 0) {
      return true;
    } else {
      return false;
    }

  }

  /**
   * Get the Contracts Report ID from the UII
   *
   * @return integer
   */
  protected function getContractsReportIDFromRequest($uii, $contractID = null, $agencyContractID = null)
  {
    return GeneralContractsBaseModel::getIdFromRequest($uii, $contractID, $agencyContractID);
  }

  /**
   * Implement prototype to perform validations for both 'add' and 'update' operations.
   *
   * @param array $obj - section data
   */
  protected function performSharedValidations(&$obj)
  {
    if (Utility\UtilityFunctions::validateUII($this->uii) === false) {
        $this->errorObj[] = "000614-CON: An unique investment identifier must be provided for the operation: ".GeneralReport::$operation.".";
    }
  }

  /**
   * Implement prototype to perform validations for 'add' operations.
   *
   * @param array $obj - section data
   */

  protected function performAddValidations(&$obj)
  {
    
  }

  /**
   * New method for validating nodes inside of Contracts->Contract
   * @param $obj
   */
  protected function performAddContractsValidations(&$obj)
  {
    if (!in_array(GeneralReport::$operation, array('add', 'update')) ) {
        $this->errorObj[] = '000625-CON: You cannot add a contract unless you are using an add or update investment operation..';
    } else {
      if (empty($obj['PIID'])) {
          $this->errorObj[] = "000618-CON: The PIID is required.";
      }

      if (isset($obj['referencePIID']) && empty($obj['referencePIID'])) {
          $this->errorObj[] = '000619-CON: The ReferencePIID canot be empty.';
      }

      if (isset($obj['contractID'])) {
           $this->errorObj[] = "000620-CON: The contractID cannot be used when the operation is add.";
      }
    }
  }


  /**
   * Implement prototype to perform validations for 'update' operations.
   *
   * @param array $obj - section data
   */
  protected function performUpdateValidations(&$obj)
  {
  }
  
  protected function performUpdateContractsValidations(&$obj) {
    if (!in_array(GeneralReport::$operation, array('update')) ) {
        $this->errorObj[] = '000624-CON: You cannot update a contract unless you are using an update investment operation.';
    } else {
      $reportId = static::getContractsReportIDFromRequest($this->uii, $obj['contractID'] ?? null, $obj['agencyContractID'] ?? null);
      if ($reportId === false) {
        $error = '';
        if (isset($obj['contractID'])) {
          $error .= ', contractID: '.$obj['contractID'];
        }
        if (isset($obj['agencyContractID'])) {
          $error .= ', agencyContractID: '.$obj['agencyContractID'];
        }
      $this->errorObj[] = "000615-CON: No contract was found with provided information of UII: {$this->uii}{$error}. You must first use an add operation.";
      }

      if (!isset($obj['contractID']) && !isset($obj['agencyContractID'])) {
          $this->errorObj[] = '000621-CON: Either contractID or agencyContractID must be present for operation update.';
      }
    }
  }
  
  protected function performDeleteContractsValidations(&$obj) {
    if (!in_array(GeneralReport::$operation, array('update')) ) {
        $this->errorObj[] = '000622-CON: You cannot delete a contract unless you are using an update investment operation.';
    } else {
      if (!isset($obj['contractID']) && !isset($obj['agencyContractID'])) {
          $this->errorObj[] = '000623-CON: Either contractID or agencyContractID must be present for operation delete.';
      }
    }
  }
}

