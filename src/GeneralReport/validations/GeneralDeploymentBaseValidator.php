<?php

namespace Pittsburgh\Controllers\ITDB2;

require_once("Pittsburgh/Controllers/ITDB2/GeneralReport/models/GeneralDeploymentDetails.php");
require_once("Pittsburgh/Controllers/ITDB2/GeneralReport/validations/FieldValidator.php");

use Pittsburgh\Lib\Utility\UtilityFunctions as UtilityFunctions;

class GeneralDeploymentBaseValidator {

  public $parent      = '';
  public $warnings    = [];
  private $refCodes   = [];
  static $sqlCache    = [];
  static $ignore      = ['operation', 'comment'];
  protected $errorObj = [];
  protected $pdo;
  protected $FieldValidator;
  public $uii;
  static $errorMessages = [
    '000001-DEP' => [
      'error' => 'projectActivityID %s is not valid for UII  %s',
      'description' => '{UII} & {projectActivityID} in the Deployment Report = {UII} & {projectActivityID} combo in the Project Activity database table',
      'code' => '000001-DEP',
      'type'  => 'error', //not used and for informational purposes as of now
      'status'  => 1, // temp field
    ],
    '000002-DEP' => [
      'error' => 'agencyProjectActivityID %s is not valid for UII %s',
      'description' => '{UII} & {agencyProjectActivityID} in the Deployment Report = {UII} & {agencyProjectActivityID} combo in the Project Activity database table',
      'code' => '000002-DEP',
      'type'  => 'error',
      'status'  => 'This error is output by the stored procedure',
    ],
    '000003-DEP' => [
      'error'  => 'Activity with [Insert "projectActivityID" OR "agencyProjectActivityID"] XXXX is not a lowest level child activity',
      'description' => '{UII} & {ID} in Project Activity Database Table row has {Has No Child Activity} <> 1 in the Project Activity Database',
      'code' => '000003-DEP',
      'type'  => 'error',
      'status'  => 'This error is output by the stored procedure',
    ],
    '000004-DEP' => [
      'error'  => 'multipleReleases is required and cannot be blank for activities',
      'description' => 'Verify that request file either includes the multipleReleases node or the database has multipleReleases data for the ID XXXX IF reportsReleases = \'Yes\'',
      'code' => '000004-DEP',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000005-DEP' => [
      'error'  => '%s may not exceed 3 dates',
      'description' => 'releasesToProd cannot have more than three child date nodes',
      'code' => '000005-DEP',
      'type'  => 'error',
      'status' => 'XSD',
    ],
    '000006-DEP' => [
      'error'  => '%s may not exceed 3 dates',
      'description' => 'releasesToTest cannot have more than three child date nodes',
      'code' => '000006-DEP',
      'type'  => 'error',
      'status' => 'XSD',
    ],
    '000007-DEP' => [
      'error'  => 'Activity with %s %s is not reporting any Production Release data',
      'description' => 'releasesToProd cannot have more than three child date nodes, [Insert "projectActivityID" OR "agencyProjectActivityID"]',
      'type'  => 'warning',
      'code' => '000007-DEP',
      'status' => 'XSD',
    ],
    '000008-DEP' => [
      'error'  => 'Activity with  %s %s is not reporting any Test Release data',
      'description' => 'Verify that the request file either includes releasesToTeset Node OR the database has releasesToTest data for the ID XXXX IF multipleReleases=\'Yes\'',
      'code' => '000008-DEP',
      'type'  => 'warning',
      'status' => 'XSD',
    ],
    '000009-DEP' => [
      'error'  => 'rollbackPercentage is required and cannot be blank when multipleReleases = \'Yes\'',
      'description' => 'Verify that request file either includes the rollbackPercentage node or the database has rollbackPercentage data for the ID XXXX IF multipleReleases = \'Yes\'',
      'code' => '000009-DEP',
      'type'  => 'warning',
      'status' => 1
    ],
    '000010-DEP' => [
      'error'  => 'releaseCadence is required and should not be blank when multipleReleases = \'Yes\'',
      'description' => 'Verify that request file either includes the releaseCadence node or the database has releaseCadence data for the ID XXXX IF multipleReleases = \'Yes\'',
      'code' => '000010-DEP',
      'type'  => 'warning',
      'status' => 1
    ],
    '000011-DEP' => [
      'error'  => 'userEngagement is required and cannot be blank',
      'description' => 'Verify that request file either includes the userEngagement node or the database has userEngagement data for the ID XXXX ',
      'code' => '000011-DEP',
      'type'  => 'error',
      'status' => 1
    ],
    '000012-DEP' => [
      'error'  => 'releaseReview is required and cannot be blank when userEngagment does not equal "None"',
      'description' => 'Verify that request file either includes the releaseReview node or the database has releaseReview data for the ID %s IF userEnagement <> "1"',
      'code' => '000012-DEP',
      'type'  => 'error',
      'status' => 1
    ],
    '000013-DEP' => [
      'error'  => 'reportsReleases only allows the values: {"Yes", "No"}',
      'description' => 'reportsReleases only allows the values: {"Yes", "No"}',
      'code' => '000013-DEP',
      'type'  => 'error',
      'status'  => 'XSD',
      'XSD' =>'DOMDocument::schemaValidate(): Element \'reportsReleases\': [facet \'pattern\'] The value \'XXX\' is not accepted by the pattern \'[Nn][Oo]\''
    ],
    '000014-DEP' => [
      'error'  => 'multipleReleases only allows the values: {"Yes", "No"}',
      'description' => 'multipleReleases only allows the values: {"Yes", "No"}',
      'code' => '000014-DEP',
      'type'  => 'error',
      'status'  => 'XSD',
      'XSD' => 'DOMDocument::schemaValidate(): Element \'multipleReleases\': [facet \'pattern\'] The value \'safasdf\' is not accepted by the pattern \'[Nn][Oo]\'.'
    ],
    '000015-DEP' => [
      'error' => '%s Date field %s with value %s is an invalid date format: The formats YYYY-MM-DD and MM/DD/YYYY are valid formats.',
      'description' => 'date must match the formats YYYY-MM-DD or MM/DD/YYYY ',
      'code' => '000015-DEP',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000016-DEP' => [
      'error'  => 'rollbackPercentage with value XXXX requires data as a rate, values must be numbers between 0-1 in the following format: x.xxxxxx',
      'description' => 'rollbackPercentage must be a decimal between 0 and 1, and must not exceed 6 digits following the decimal place ',
      'code' => '000016-DEP',
      'type' => 'error',
      'xsd' => 'DOMDocument::schemaValidate(): Element \'multipleReleases\': [facet \'pattern\'] The value \'safasdf\' is not accepted by the pattern \'[Nn][Oo]\'.'
    ],
    '000017-DEP' => [
      'error'  => 'releaseCadence with value XXXX requires data as a decimal',
      'description' => 'releaseCadence must be reported as a standard number - decimals allowed',
      'code' => '000017-DEP',
      'type'  => 'error',
      'status'  => 1,
      'XSD'  => 'DOMDocument::schemaValidate(): Element \'releaseCadence\': \'3addf\' is not a valid value of the atomic type \'xs:decimal\'.',
    ],
    '000018-DEP' => [
      'error'  => 'userEngagment with value XXXX only allows the values: {1, 2, 3, 4}',
      'description' => 'userEngagement must be part of the set {1, 2, 3, 4}',
      'code' => '000018-DEP',
      'type'  => 'error',
      'status' => 'xsd updated with limitedInt type',
      'xsd' => 'DOMDocument::schemaValidate(): Element \'userEngagement\': \'1fer\' is not a valid value of the atomic type \'limitedInt\'.'
    ],
    '000019-DEP' => [
      'error'  => 'releaseReview exceeds the limit of 1000 characters',
      'description' => 'releaseReview must not exceed 1000 characters',
      'code' => '000019-DEP',
      'type'  => 'error',
      'status'  => 1,
    ],
    '000020-DEP' => [
      'error'  => 'Operation \'get\' is not allowed here.',
      'description' => 'Cannot use get in certain situations.',
      'code' => '000020-DEP',
      'type'  => 'error',
      'status'  => 0,
    ],
    '000021-DEP' => [
      'error'  => 'Operation $operationNextContract is not allowed at the investment level',
      'description' => 'Operation not allowed here',
      'code' => '000021-DEP',
      'type'  => 'error',
      'status'  => 0,
    ],
    '000022-DEP' => [
      'error'  => 'Required operation tag is missing.',
      'description' => 'May need to remove this for details level at least',
      'code' => '000022-DEP',
      'type'  => 'error',
      'status' => 'XSD',
      'XSD' => 'taken care of by xsd'
    ],
    '000025-DEP' => [
      'error'  => 'An unique investment identifier must be provided for the operation: $operation',
      'description' => 'Invalid UII',
      'code' => '000025-DEP',
      'type'  => 'error',
      'status' => 'XSD',
      'XSD' => 'taken care of by xsd'
    ],
    '000026-DEP' => [
      'error'  => '%s dates need to be in this format: mm/dd/yyyy',
      'description' => 'releasesToProd cannot have more than three child date nodes',
      'code' => '000026-DEP',
      'type'  => 'error',
      'status' => 'XSD',
      'XSD' => 'taken care of by xsd'
    ],
    '000027-DEP' => [
      'error'  => 'An unique investment identifier must be provided for the operation: $operation',
      'description' => 'Invalid UII',
      'code' => '000027-DEP',
      'type'  => 'error',
      'status' => 'XSD',
      'XSD' => 'taken care of by xsd'
    ],
    '000028-DEP' => [
      'error'  => '%s must have at least 1 date or be removed',
      'description' => 'Empty tags should not be allowed <releasesToTest></releasesToTest>',
      'code' => '000028-DEP',
      'type'  => 'error',
      'status' => 1,
    ],
    '000030-DEP' => [
      'error'  => 'The system encountered an unexpected error.',
      'description' => 'Generic message when there is an error with a model',
      'code' => '000030-DEP',
      'type'  => 'error',
      'status' => 'There is already a generic error for this but Michael created a new one and may refer to this error code',
    ],
  ];

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
  public function __construct($pdo, $reportType, $reportUII) {
    $this->pdo = $pdo;
    $this->uii = $reportUII;
    $this->FieldValidator = new \Pittsburgh\Controllers\ITDB2\GeneralReport\FieldValidator($this->pdo, 'DEP');
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
    $models = array_flip(GeneralReportDeploymentReport::$models);
    $model = str_replace('Validator', '', get_class($this));
    $class = preg_replace('/[A-Za-z0-9\\\\]+\\\\([A-Za-z0-9]+)$/', '$1', $model);
    $type = isset($models[$class]) ? $models[$class] : "";

    if (!isset($type) || empty($type)) {
      $this->errorObj[] = self::outputErrorMessage('000030-DEP');
    }

    // Validate identifiers (e.g. check for duplicates)
    if (class_exists($model)) {
      $error = $model::processIdentifiers($obj, true);
      if (is_string($error) && strlen($error) > 0) {
        $this->errorObj[] = $error;
      }
    }

    $this->performStructuralValidations($obj);

    // need to handle types differently, details or Activities and a case statement is easier to read
    switch ($type) {
      case 'details':
        break;
      case 'Activities':
        GeneralDeploymentBaseModel::fillUnsetValues($obj, ['UII' => $this->uii], $obj);
        if (GeneralReport::$operation == 'add') {  // structure changes dealing with Contracts->Contract
          $this->performAddActivitiesValidations($obj);
        } elseif (GeneralReport::$operation == 'update') {

          $this->performAddActivitiesValidations($obj);
        } else {
          $operationNextContract = GeneralReport::$operationNextContract;
          $cid = ($cid !== null ? " for contract: $cid" : '');
          $this->errorObj[] = "000029-DEP: Operation '".GeneralReport::$operationNextContract."' is not allowed here".($cid !== null ? " for contract: $cid" : '').'.';
          $this->errorObj[] =  self::outputErrorMessage('00021-DEP', self::$errorMessages);
        }
        $this->validateFields($obj, GeneralDeploymentDetails::$fieldList);
        break;
      default:
        break;
    }

    // don't need this for details section
    $this->performSharedValidations($obj);
    //
    // no get operations are validated here.
    if (GeneralReport::$operation == 'get' || GeneralReport::$operationNextContract == 'get') {
      $this->errorObj[] = static::$errorMessages['000020-DEP']['code'] . ': ' . static::$errorMessages['000020-DEP']['error'];
    } elseif ($type != 'details' && $type != 'Activities') {  // TODO: should not hardcode this
      $this->errorObj[] = self::outputErrorMessage('000022-DEP', self::$errorMessages);
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
        $res = $this->FieldValidator->validateLength($obj[$name], $name, $params['maxLength']);
        if ($res !== true) {
          $this->errorObj[] = $res;
        }
      }
    }
  }

  /**
   * Implement prototype to perform validations for both 'add' and 'update' operations.
   * // TODO: test
   * @param array $obj - section data
   */
  protected function performSharedValidations(&$obj) {
    // TODO: doesn't seem to catch empty UII
    if (UtilityFunctions::validateUII($this->uii) === false) {
      $operation = GeneralReport::$operation;
      $this->errorObj[] = self::outputErrorMessage('000025-DEP');
    }
  }

  /**
   * Validate the Deployment Report Activity->Activities
   * @param $obj
   */
  protected function performAddActivitiesValidations(&$obj) {
    // if operation i not add or update, then this error is caught in GeneralReport.php line 743
    // first check to make sure this is an add and that required fields are in XML
    if (GeneralReport::$operation !== 'update' && GeneralReport::$operation !== 'add') {
      $this->errorObj[] = self::outputErrorMessage('000024-DEP');
    } else {

      //rollback percentage is required if multipleReleases = yes
      if (isset($obj['multipleReleases']) && strcasecmp($obj['multipleReleases'], 'YES') === 0) {
        // if multipleReleases is empty and reportsReleases is yes, then error out
        if (!isset($obj['rollbackPercentage']) || empty($obj['rollbackPercentage']) && $obj['rollbackPercentage'] !== '0') {
          $this->warnings[] = self::outputErrorMessage('000009-DEP');
        }
        // releaseCadence is required and should not be blank when multipleReleases = 'Yes'
        // if multipleReleases is empty and reportsReleases is yes, then error out
        if (!isset($obj['releaseCadence']) || empty($obj['releaseCadence'])) {
          $this->warnings[] = self::outputErrorMessage('000010-DEP');
        }
      } else if (!isset($obj['multipleReleases']) || empty($obj['multipleReleases'])) {
        // if reportReleases = 'No' then multipleReleases is required
        if (GeneralReport::$operation == 'add') {
          $this->errorObj[] = self::outputErrorMessage('000004-DEP');
        }
      }

      if (isset($obj['multipleReleases']) && strcasecmp($obj['multipleReleases'], 'YES') === 0) {

        if(isset($obj['projectActivityID']) || !empty($obj['projectActivityID']))
        {
          $id   = $obj['projectActivityID'];
          $name = 'projectActivityID';
        }
        else if(isset($obj['agencyProjectActivityID']) || !empty($obj['agencyProjectActivityID']))
        {
          $id   = $obj['agencyProjectActivityID'];
          $name = 'agencyProjectActivityID';
        }

        // if multipleReleases is empty and reportsReleases is yes, then error out
        if (!isset($obj['releasesToProd']) || empty($obj['releasesToProd'])) {
          $this->warnings[] = sprintf(self::outputErrorMessage('000007-DEP'), $name, $id);
        }
        // releaseCadence is required and should not be blank when multipleReleases = 'Yes'
        // if multipleReleases is empty and reportsReleases is yes, then error out
        if (!isset($obj['releasesToTest']) || empty($obj['releasesToTest'])) {
          $this->warnings[] = sprintf(self::outputErrorMessage('000008-DEP'), $name, $id);
        }
      }

//      // check to see if multipleReleases is in XML, required only if reportReleases = 'Yes'
//      if (isset($obj['reportsReleases']) && strcasecmp($obj['reportsReleases'], 'YES') === 0) {
//
//        /*
//         * TODO:  no longer depends on reportsReleases
//        // if multipleReleases is empty and reportsReleases is yes, then error out
//        if (!isset($obj['multipleReleases']) || empty($obj['multipleReleases'])) {
//          $this->errorObj[] = self::outputErrorMessage('000004-DEP');
//        }
//        */
//      } else {
//        //rollback percentage is required if multipleReleases = yes
//        if (isset($obj['multipleReleases']) && strcasecmp($obj['multipleReleases'], 'YES') === 0) {
//          // if multipleReleases is empty and reportsReleases is yes, then error out
//          if (!isset($obj['rollbackPercentage']) || empty($obj['rollbackPercentage'])) {
//            $this->warnings[] = self::outputErrorMessage('000009-DEP');
//          }
//          // releaseCadence is required and should not be blank when multipleReleases = 'Yes'
//          // if multipleReleases is empty and reportsReleases is yes, then error out
//          if (!isset($obj['releaseCadence']) || empty($obj['releaseCadence'])) {
//            $this->warnings[] = self::outputErrorMessage('000010-DEP');
//          }
//        } else if (!isset($obj['multipleReleases']) || empty($obj['multipleReleases'])) {
//          // if reportReleases = 'No' then multipleReleases is required
//          $this->errorObj[] = self::outputErrorMessage('000004-DEP');
//        }
//      }

      // check to see if userEngagement is in XML (required)
      if (!isset($obj['userEngagement']) || empty($obj['userEngagement'])) {
        $this->errorObj[] = self::outputErrorMessage('000011-DEP');
      }


      // Verify that request file either includes the releaseReview node or the database has releaseReview data for the ID XXXX IF userEnagement <> "1"
      // releaseReview is required and cannot be blank when userEngagment does not equal "1"
      if (!isset($obj['releaseReview']) || empty($obj['releaseReview'])) {
        if (isset($obj['userEngagement']) &&  $obj['userEngagement'] != '1') {
          $this->errorObj[] = self::outputErrorMessage('000012-DEP');
        }
      } else {
        // check to see if releaseReview is not over 1000 characters
        if (isset($obj['releaseReview']) && strlen($obj['releaseReview']) > 1000) {
          $this->errorObj[] = sprintf(self::outputErrorMessage('000019-DEP'));
        }
      }
      // check to see if there's dates first
      $this->checkForDates($obj, 'releasesToProd');
      $this->checkForDates($obj, 'releasesToTest');
    }

    // 000001-DEP check for 0 or 1 to see if investmentActiity table has a match with our add submission
    // $projectActivityID       = isset($obj['projectActivityID']) ?? NULL;  // comes back as true
    // $agencyProjectActivityID = isset($obj['agencyProjectActivityID']) ?? NULL;
    $projectActivityID       = isset($obj['projectActivityID'])?$obj['projectActivityID']:NULL;
    $agencyProjectActivityID = isset($obj['agencyProjectActivityID'])?$obj['agencyProjectActivityID']:NULL;
    $use_val = isset($projectActivityID) ? $projectActivityID : $agencyProjectActivityID;
    $item = isset($projectActivityID) ? 1 : 2;
    $result                  = GeneralDeploymentBaseModel::investmentActivityExists($this->uii, $projectActivityID, $agencyProjectActivityID, $item);
    // TODO: may need to return other errors depending on projectActivityID or agencyProjectActivityID
    if(empty($result[0])) {
      if($item == 1 || $item == 0) {
        $this->errorObj[] = sprintf(static::$errorMessages['000001-DEP']['code'].': '.static::$errorMessages['000001-DEP']['error'],$use_val, $this->uii);
      }
      else{
          $this->errorObj[] = sprintf(static::$errorMessages['000002-DEP']['code'].': '.static::$errorMessages['000002-DEP']['error'],$use_val, $this->uii);
      }
    }
  }

  /**
   * Check to see if releaseToProd and releaseToTest dates are in XML
   * @param $obj
   * @param $key
   *
   * @return string
   */
  function checkForDates(&$obj, $key) {
    if (array_key_exists($key, $obj)) {
      if (!is_array($obj[$key]['date']) && !is_string($obj[$key]['date'])) {
        $this->errorObj[] = sprintf(self::outputErrorMessage('000028-DEP'), $key);
      } else if (is_array($obj[$key]['date']) && count($obj[$key]['date']) > 3) {
        $this->errorObj[] = sprintf(self::outputErrorMessage('000006-DEP'), $key);
      } else {
        $this->validateReleases($obj, $key);
      }
    }
  }

  /**
   * Loop through each date and make sure it's in the correct format
   * @param $obj
   * @param $release
   */
  function validateReleases(&$obj, $release) {
    // make sure there's dates for this release
    if (!is_array($obj[$release]['date'])) {
      $dateObj = array($obj[$release]['date']);
    } else {
      $dateObj = $obj[$release]['date'];
    }

    // loop through all dates and check for errors
    foreach ($dateObj as $i => $date) {
      if (empty($date)) {
        $date = '';
      }
      
      $dateRes = $this->validateDate($date);
      

      if (empty($dateRes) || !isset($dateRes)) {
        $this->errorObj[] = sprintf(self::outputErrorMessage('000015-DEP'), $release, ($i + 1), $date);
      }
    }
  }

  /**
   * Find out what separator was used and divert to validateDateFully()
   * @param $date
   *
   * @return bool
   */
  function validateDate($date) {
    $result = false;

    // first check to see if there's slashes or dashes and divert
    if (strpos($date, '/') !== false) {
      $result = $this->validateDateFully($date, '/^(\d{2})\/(\d{2})\/(\d{4})$/', ['m' => 0, 'd' => 1, 'y' => 2]);
    } elseif (strpos($date, '-') !== false) {
      $result = $this->validateDateFully($date, '/^(\d{4})\-(\d{2})\-(\d{2})$/', ['y' => 0, 'm' => 1, 'd' => 2]);
    }

    return $result;
  }

  /**
   * Validate dates fully
   * @param $date
   * @param $separator
   *
   * @return bool
   */
  function validateDateFully($date, $fmt, $pos) {
    $result = false;
    if (isset($pos['m'], $pos['d'], $pos['y']) && preg_match($fmt, $date, $arr) === 1) {
      $arr = array_slice($arr, 1, 3);
      $result = checkdate((int) $arr[$pos['m']], (int) $arr[$pos['d']], (int) $arr[$pos['y']]);
    }

    return $result;
  }

  /**
   * Implement prototype to perform validations for 'update' operations.
   *
   * @param array $obj - section data
   */
  protected function performUpdateValidations(&$obj) {

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