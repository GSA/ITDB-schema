<?php

namespace Pittsburgh\Controllers\ITDB2;

require_once("Pittsburgh/Controllers/ITDB2/GeneralReport/models/GeneralRisksDetails.php");
require_once("Pittsburgh/Controllers/ITDB2/GeneralReport/validations/FieldValidator.php");
require_once("Pittsburgh/Controllers/ITDB2/GeneralReport/util/GRUtils.php");

use Pittsburgh\Lib\Utility\UtilityFunctions as UtilityFunctions;
use Pittsburgh\Controllers\ITDB2\GeneralReport\GRutils as GRUtils;

class GeneralRisksBaseValidator {

  public $parent      = '';
  public $warnings    = [];
  private $refCodes   = [];
  static $sqlCache    = [];
  static $ignore      = ['operation', 'comment'];
  protected $errorObj = [];
  static $riskAreas   = [];
  protected $pdo;
  protected $FieldValidator;
  public $uii;
  static $errorMessages = [
    '000001-RSK' => [
      'error' => 'Field InvestmentRiskArea with value %s only allows integers from the set: {1,2,3,4,5,6,7,8,9,10,11,12,13}.',
      'description' => 'Only 1-13 integers allowed for this field; this is an existing field but is being moved from MITBCD to a new endpoint and so the data format will not be validated through the XML',
      'code' => '000001-RSK',
      'type'  => 'error', //not used and for informational purposes as of now
      'status'  => 'XSD', // temp field
    ],
    '000002-RSK' => [
      'error' => 'Investment with UII %s cannot provide Risk Area %s more than once.',
      'description' => 'One and only one entry per risk area. This validation is for missing risk areas.',
      'code' => '000002-RSK',
      'type'  => 'error',
      'status'  => 'XSD',
      'note'  => 'Error 1871: Element \'Risk\': This element is not expected. on line 123',
    ],
    '000003-RSK' => [
      'error'  => 'Response has not been provided for InvestmentRiskArea: %s.',
      'description' => 'One and only one entry per risk area. This validation is for risk areas provided more than once.',
      'code' => '000003-RSK',
      'type'  => 'error',
      'status'  => 0,
      'note'  => 'in an update, one risk area can be updated at a time',
    ],
    '000004-RSK' => [
      'error'  => 'isActiveRisk is required for each InvestmentRiskArea',
      'description' => 'This field has been added as a mandatory field to the guidance',
      'code' => '000004-RSK',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000005-RSK' => [
      'error'  => 'Field isActiveRisk with value %s only allows the values: {\'Yes\', \'No\'}',
      'description' => 'Only \'Yes\', \'No\' allowed for this field ',
      'code' => '000005-RSK',
      'type'  => 'error',
      'status' => 'XSD',
    ],
    '000006-RSK' => [
      'error'  => 'investmentRiskProbability should NOT be reported if the value for isActiveRisk is "No".',
      'description' => 'valid value \'36\' has been removed for this data field',
      'code' => '000006-RSK',
      'type'  => 'warning',
      'status'  => 1,
    ],
    '000007-RSK' => [
      'error'  => 'investmentRiskProbability is required and should be reported if the value for isActiveRisk is "Yes".',
      'description' => 'This field is now dependent upon the new isActiveRisk field; also see ALL RISKS',
      'type'  => 'warning',
      'code' => '000007-RSK',
      'status'  => 1,
    ],
    '000008-RSK' => [
      'error'  => 'Field investmentRiskProbability with value XXXX allows only integers from the set: {1,2,3,4,5}.',
      'description' => 'Only 1-5 integers allowed for this field ',
      'code' => '000008-RSK',
      'type'  => 'error',
      'status' => 'XSD',
    ],
    '000009-RSK' => [
      'error'  => 'investmentRiskImpact should NOT be reported if the value for isActiveRisk is "No".',
      'description' => 'This field is now dependent upon the new isActiveRisk field; also see ALL RISKS',
      'code' => '000009-RSK',
      'type'  => 'warning',
      'status'  => 1,
    ],
    '000010-RSK' => [
      'error'  => 'investmentRiskImpact is required and should be reported if the value for isActiveRisk is "Yes".',
      'description' => 'This field is now dependent upon the new isActiveRisk field; also see ALL RISKS',
      'code' => '000010-RSK',
      'type'  => 'warning',
      'status'  => 1,
    ],
    '000011-RSK' => [
      'error'  => 'Field investmentRiskImpact with value %s only allows integers from the set: {1, 2, 3, 4, 5}.',
      'description' => 'Only 1-5 integers allowed for this field',
      'code' => '000011-RSK',
      'type'  => 'error',
      'status' => 'XSD',
    ],
    '000012-RSK' => [
      'error'  => 'investmentRiskMitigationPlan is required for Risk Areas where [investmentRiskProbability * investmentRiskImpact] is 6 or greater.',
      'description' => 'This field is only required for high impact/probability risks',
      'code' => '000012-RSK',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000013-RSK' => [
      'error'  => 'investmentRiskMitigationPlan should NOT be reported if the value for isActiveRisk is \'No\'',
      'description' => 'This field is now dependent upon the new isActiveRisk field; also see ALL RISKS',
      'code' => '000013-RSK',
      'type'  => 'warning',
      'status'  => 1,
    ],
    '000014-RSK' => [
      'error'  => 'Field mitigationPlan with value %s may not exceed 1000 characters',
      'description' => 'Mitigation Plan has a 1000 character limit',
      'code' => '000014-RSK',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000015-RSK' => [
      'error' => 'risksReport for UII %s already has %s riskAreas. Use the update operation to edit risk data for %s',
      'description' => 'Checks the database to see if there are 13 risk areas.',
      'code' => '000015-RSK',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000016-RSK' => [
      'error' => 'Investment with UII %s cannot provide Risk Area %s more than once.',
      'description' => 'Checks user input riskAreas for duplicates',
      'code' => '000016-RSK',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000017-RSK' => [
      'error' => 'Response has not been provided for all riskAreas %s must be submitted at one time.',
      'description' => 'User must add all riskAreas at one time',
      'code' => '000017-RSK',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000099-RSK' => [
      'error'  => 'The system encountered an unspecified error',
      'description' => 'This error should never be hit because other errors should be triggered if $obj is not an array ',
      'code' => '000099-RSK',
      'type'  => 'error',
      'status'  => 1,
    ],
  ];

  /**
   * Not used as of yet but may be needed in the future
   * @param $subSection
   * @param $parent
   */
  function validateCollection($subSection, $parent) {
    self::$riskAreas = array_column($subSection, 'riskArea');
    // if we are doing an add, check for all risk areas AND check for duplicate risks
    if(GeneralReportRisksReport::$operation == 'add') {
      return ['errors' => array_merge($this->checkForAllRiskAreas($subSection), $this->checkForDupRiskAreas($subSection))];
    } elseif(GeneralReportRisksReport::$operation == 'update') {
      // if we're doing an update, then we only need to check for duplicate risks
      // since less than 13 risks can be submitted on updates
      return ['errors' => array_merge($this->checkForDupRiskAreas($subSection))];
    }

  }

  /**
   * BaseValidator constructor.
   *
   * @param object $pdo
   * @param string $reportType
   * @param int $reportPrimaryID - contractsReport database table identifier
   */
  public function __construct($pdo, $reportType, $reportUII) {
    $this->pdo = $pdo;
    $this->uii = $reportUII;
    $this->FieldValidator = new \Pittsburgh\Controllers\ITDB2\GeneralReport\FieldValidator($this->pdo, 'RSK');
  }

  /**
   * Base validation method, configures object and performs various kinds of validations by default.
   * called by GeneralContractBaseValidator::validateSection
   * @param array $obj - section data
   *
   * @return array - errors and warnings, when applicable
   */
  public function validate(&$obj, $parent = '', $cid = null) {
    $this->parent = $parent;
    $this->errorObj = [];
    $this->warnings = [];

    // Get the related model and type for this validator
    $models = array_flip(GeneralReportRisksReport::$models);
    $model  = str_replace('Validator', '', get_class($this));
    $class  = preg_replace('/[A-Za-z0-9\\\\]+\\\\([A-Za-z0-9]+)$/', '$1', $model);
    $type   = isset($models[$class]) ? $models[$class] : "";

    // in case type is null, output a generic error
    if (!isset($type) || empty($type)) {
      $this->errorObj[] = self::outputErrorMessage('000099-RSK');
    }

    // Validate identifiers (e.g. check for duplicates)
    if (class_exists($model)) {
      $error = $model::processIdentifiers($obj, true, $cid);
      if (is_string($error) && strlen($error) > 0) {
        $this->errorObj[] = $error;
      }
    }

    $this->performStructuralValidations($obj);

    // need to handle types differently, details or Risks and a case statement is easier to read
    switch ($type) {
      case 'details':
        if (GeneralReport::$operation == 'add') {
          $this->checkRiskAddedAlready($obj);
        } else if (GeneralReport::$operation == 'delete') {
          GeneralReport::$deleteEntireInvestment = true;
        }
        break;
      case 'Risks':
        if (GeneralReport::$operation == 'add') {  // structure changes dealing with Contracts->Contract
          $this->performAddUpdateRisksValidations($obj);
        } elseif (GeneralReport::$operation == 'update') {
          $this->performAddUpdateRisksValidations($obj);
        } else {

        }
        $this->validateFields($obj, GeneralRisksDetails::$fieldList);

        // 000014-RSK Field mitigationPlan with value %s may not exceed 1000 characters
        $res = UtilityFunctions::validateLength(!empty($obj['mitigationPlan']) ? $obj['mitigationPlan'] : NULL, 1000);
        if ($res !== true) {
          $this->errorObj[] = sprintf(self::outputErrorMessage('000014-RSK', self::$errorMessages), $obj['mitigationPlan'] );
        }

        break;
      default:
        break;
    }

    // no get operations are validated here.
    if (GeneralReport::$operation == 'get' || GeneralReport::$operationNextContract == 'get') {
      $this->errorObj[] = static::$errorMessages['000099-RSK']['code'] . ': ' . static::$errorMessages['000099-RSK']['error'];
    } elseif ($type != 'details' && $type != 'Risks') {  // TODO: should not hardcode this
      $this->errorObj[] = self::outputErrorMessage('000099-RSK', self::$errorMessages);
    }

    $result = [];
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
  protected function performStructuralValidations(&$obj) {

  }

  /**
   * Prototype to perform validations for 'delete' operations.
   *
   * @param array $obj - section data
   */
  protected function performDeleteValidations(&$obj) {

  }

  /**
   * Function to validate a field based on type defined in model.
   *
   * @param array $fields - field list from model with parameters
   */
  protected function validateFields(&$obj, $fields) {

    if (!is_array($fields))
      return;
    foreach ($fields as $name => $params) {
      $params['params']['predicate'] = '';
      if (isset($params['type']) && !empty($params['type']) && isset($obj[$name]) && (!empty($obj[$name]) || $obj[$name] == 0)) {
        if (!isset($params['params']))
          $params['params'] = '';
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
        $res = UtilityFunctions::validateLength($obj[$name], $params['maxLength']);
        if ($res !== true) {
          $this->errorObj[] = self::outputErrorMessage('000014-RSK', self::$errorMessages);
        }
      }
    }
  }

  /**
   * Implement prototype to perform validations for both 'add' and 'update' operations.
   * @param array $obj - section data
   */
  protected function performSharedValidations(&$obj) {
    // doesn't seem to catch empty UII
    if (UtilityFunctions::validateUII($this->uii) === false) {
      $operation = GeneralReport::$operation;
      $this->errorObj[] = self::outputErrorMessage('000099-RSK');
    }
  }

  /**
   * Check for duplicate risk areas
   * @param $subSection
   *
   * @return array
   */
  protected function checkForDupRiskAreas($subSection) {
    $errors = [];
    if (!empty(self::$riskAreas)) {
      $racs = array_filter(array_count_values(self::$riskAreas), function($ra) { return $ra !== 1; });
      foreach($racs as $ra => $rac) {
        $errors[] = sprintf(self::outputErrorMessage('000016-RSK'), $this->uii, $ra);
        GeneralReport::$errorCount++;
      }
    }
    
    return $errors;
  }
  
  /**
   * Make sure there is an entry for all 13 risk areas
   * Does not need to run on updates
   * @param $subSection
   *
   * @return array|bool
   */
  protected function checkForAllRiskAreas($subSection) {
    $errors = array();
    if(is_array(self::$riskAreas)) {
      if(count(self::$riskAreas) !== 13) {
        $errors[] = sprintf(self::outputErrorMessage('000017-RSK'), GeneralRisksBaseModel::RISK_MAX);
        GeneralReport::$errorCount++;
      } else {
        foreach(GRUtils::needsRisks(self::$riskAreas) as $nr) {
          $errors[] = sprintf(self::outputErrorMessage('000003-RSK'), $nr);
          GeneralReport::$errorCount++;
        }
      }
    }
    return $errors;
  }

  /**
   * Perform validations for updates
   * @param $obj
   */
  protected function performAddUpdateRisksValidations(&$obj) {


    // 000004-RSK check every investment for isActiveRisk:  isActiveRisk is required for each InvestmentRiskArea
    if (!isset($obj['isActive']) || empty($obj['isActive'])) {
      $this->errorObj[] = sprintf(self::outputErrorMessage('000004-RSK'));
    }

    //  000006-RSK and 000007-RSK
    $this->checkRiskProbability($obj);

    // 000009-RSK and 000010-RSK
    $this->checkInvestmentRiskImpact($obj);

    // 000013-RSK
    $this->checkInvestmentRiskMitigationPlan($obj);

    // 000012-RSK
    $this->checkProbabilityAndImpact($obj);

  }

  /**
   * 000006-RSK investmentRiskProbability should NOT be reported if the value for isActiveRisk is "No"
   * 000007-RSK investmentRiskProbability is required and should be reported if the value for isActiveRisk is "Yes"
   * @param $obj
   */
  protected function checkRiskProbability(&$obj) {
    /*if ((isset($obj['isActive']) && strcasecmp($obj['isActive'], 'NO') === 0 // if isActive is NO
      && (isset($obj['riskProbability']) || !empty($obj['riskProbability'] ))))  { // and riskProbability has a value then error out
      $this->warnings[] = sprintf(self::outputErrorMessage('000006-RSK'));
    } else*/
    if((isset($obj['isActive']) && strcasecmp($obj['isActive'], 'YES') === 0 // if isActive is YES
      && (!isset($obj['riskProbability']) || empty($obj['riskProbability'] ))))  {  // and riskProbability is not set or empty, then error out
      $this->warnings[] = sprintf(self::outputErrorMessage('000007-RSK'));
    }
  }

  /**
   * 000009-RSK investmentRiskImpact should NOT be reported if the value for isActiveRisk is "No"
   * 000010-RSK investmentRiskImpact is required and should be reported if the value for isActiveRisk is "Yes"
   * @param $obj
   */
  protected function checkInvestmentRiskImpact(&$obj) {
    /*if ((isset($obj['isActive']) && strcasecmp($obj['isActive'], 'NO') === 0 // if isActive is NO
      && (isset($obj['riskImpact']) || !empty($obj['riskImpact'] ))))  { // and investmentRiskImpact has a value then error out
      $this->warnings[] = sprintf(self::outputErrorMessage('000009-RSK'));
    } else*/
    if((isset($obj['isActive']) && strcasecmp($obj['isActive'], 'YES') === 0 // if isActive is YES
      && (!isset($obj['riskImpact']) || empty($obj['riskImpact'] ))))  {  // and investmentRiskImpact is not set or empty, then error out
      $this->warnings[] = sprintf(self::outputErrorMessage('000010-RSK'));
    }
  }

  /**
   * 000013-RSK investmentRiskMitigationPlan should NOT be reported if the value for isActiveRisk is 'No'
   * @param $obj
   */
  protected function checkInvestmentRiskMitigationPlan(&$obj) {
    if ((isset($obj['isActive']) && strcasecmp($obj['isActive'], 'NO') === 0 // if isActive is NO
      && (isset($obj['mitigationPlan']) || !empty($obj['mitigationPlan'] ))))  { // and investmentRiskMitigationPlan has a value then error out
      $this->warnings[] = sprintf(self::outputErrorMessage('000013-RSK'));
    }
  }

  /**
   * 000012-RSK investmentRiskMitigationPlan is required for Risk Areas where [investmentRiskProbability * investmentRiskImpact] is 6 or greater.
   * @param $obj
   */
  protected function checkProbabilityAndImpact(&$obj) {

    // before doing anything, make sure everything is set so we don't get notice errors
    if(isset($obj['riskProbability']) ||  !empty($obj['riskProbability']))  { // if riskProbability is set and not empty

      if (isset($obj['riskImpact']) ||  !empty($obj['riskImpact'])) { // and riskImpact is set and not empty
        // check to see if [investmentRiskProbability * investmentRiskImpact] is 6 or greater
        if((int)$obj['riskProbability'] * (int)$obj['riskImpact'] > 5) {

          // make sure investmentRiskMitigationPlan is set
          if(!isset($obj['mitigationPlan']) ||  empty($obj['mitigationPlan'])) {
            $this->errorObj[] = sprintf(self::outputErrorMessage('000012-RSK'));
          }
        }
      }
    }
  }

  /**
   * Implement prototype to perform validations for 'update' operations.
   *
   * @param array $obj - section data
   */
  protected function performUpdateValidations(&$obj) {

  }


  /**
   * @param $obj
   */
  protected function checkRiskAddedAlready(&$obj) {
    if (($cr = GeneralRisksBaseModel::getCountFromUii($obj['UII'])) >= GeneralRisksBaseModel::RISK_MAX) {
      $this->errorObj[] = sprintf(self::outputErrorMessage('000015-RSK'), $obj['UII'], $cr, $obj['UII']);
    }
  }

  /**
   * Ouput an error message
   * @param $code
   *
   * @return string
   */
  static function outputErrorMessage($code) {
    return static::$errorMessages[$code]['code'].': '.static::$errorMessages[$code]['error'];
  }
}