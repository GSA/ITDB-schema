<?php

namespace Pittsburgh\Controllers\ITDB2;

require_once("Pittsburgh/Controllers/ITDB2/GeneralReport.php");
require_once("Pittsburgh/Lib/Utility/UtilityFunctions.php");

use Pittsburgh\lib\Utility as Utility;

class GeneralContractsBaseModel
{
  static $fieldList       = array();
  static $ignore          = array('operation', 'comment');
  static $identifier      = 'id';
  static $table           = 'generalDataReports';
  static $agencyIdentifier = 'contractID';
  static $validator;
  static $primaryID;


  /**
   * TODO: simplify this by creating different methods for delete, save
   * @param array $obj - section data
   * @param object $pdo - PDO object to perform operations with
   *
   * @return mixed - int|string record ID on success, error message on failure
   */
  static function performOperation($operation, $details, &$obj)
  {

    // _err(["performOperationsBySection", $operation, $obj],1, __FILE__, __LINE__);
    // TODO: develop.fy21 operation empty because we are inside of details!!!
    //$operation = isset($obj['operation']) ? $obj['operation'] : '';
    $pdo       = BaseReport::getPDO();

    // _err([$operation, $details, $obj],1,__FILE__,__LINE__);

    switch ($operation) {
      case 'add':
        $procedure = GeneralDataReportsDetails::$addProcedure;
        $results   = self::performAddUpdateOperation($obj, $details,  $pdo, $procedure, true);
        break;
      case 'update':
        $procedure = GeneralDataReportsDetails::$updateProcedure;
        $results   = self::performAddUpdateOperation($obj, $details,  $pdo, $procedure,false);
        break;
      case 'delete':
        // _err([$details, $obj],1,__FILE__, __LINE__);
        $procedure = GeneralDataReportsDetails::$deleteIdProcedure;
        $results   = self::performDeleteOperation($obj, $details,  $pdo, $procedure);
        break;
      case 'delete-all':
        // _err($operation,1,__FILE__,__LINE__);
        $procedure = GeneralDataReportsDetails::$deleteAllProcedure;
        $results   = self::performDeleteAllOperation($obj, $details,  $pdo, $procedure);
        break;
      default:  // if operation empty in this section, then we skip!
        return;
    }
    return $results;
  }

  /**
   *
   * @param $section
   * @return bool
   */
  protected static function parseModel($section)
  {
    return isset(GeneralReportContractsReport::$models[$section]) ? GeneralReportContractsReport::$models[$section] : false;
  }


  /**
   * Determine if a submission section may consist of repeating instances,
   * e.g. "projectts" section can be one or many projects.
   *
   * @param string $section - submission section (XML) name
   *
   * @return bool

  protected static function allowsMultiple($section)
  {

  _err($section, 0);
  $mult = array(
  );
  return in_array($section, $mult);
  }
   */

  /**
   * Determine if a submission section may consist of repeating instances,
   * e.g. "projectts" section can be one or many projects.
   *
   * @param string $section - submission section (XML) name
   *
   * @return bool
   */
  protected static function allowsMultiple($section)
  {
    $mult = ['Contracts'];
    return in_array($section, $mult);
  }

  /**
   * Perform database operations for a section of a valid submission.
   * 
   * @param array $obj - section data
   *
   * @return array - captures the result of each operation
   */
  static function performOperationsBySection(&$obj)
  {
    $operationResults = array();
    $ignore           = static::$ignore;
    $mainOperation    = isset($obj['operation']) ? $obj['operation']: NULL;
    $details          = isset($obj['details']) ? $obj['details']: NULL;
    if (GeneralReport::$deleteEntireInvestment) {
      // override operation here so performOperation() knows that we want to delete ALL BY UIII
      $operationResults[] = self::performOperation('delete-all', $details, $obj);
    } else {
      foreach ($obj as $key => &$section) {
        if (!in_array($key, $ignore)) {
          $model = self::parseModel($key);

          if ($model) {

            if (self::allowsMultiple($key)) {
              foreach ($section as $skey => &$subSection) {
                if (!in_array($skey, $ignore)) { // if this is not a section we nee to ignore
                  if (isset($subSection[0])) { // if we are in a repeating part of XML
                    // perform an operation for each subsection
                    foreach ($subSection as $key => &$sec) {
                      $operation          = isset($sec['operation']) ? $sec['operation']: NULL;
                      $operationResults[] = self::performOperation($operation, $details, $sec);
                    }
                  } else { // if we are NOT in a repeating part of XML, or ONE contract is in the ADD
                    $operation = isset($subSection['operation']) ? $subSection['operation']: NULL;
                    $operationResults[] = self::performOperation($operation, $details, $subSection);
                  }
                }
              }
            } else {
              // _err(["NOT A MULTIPLE", $details],1, __FILE__, __LINE__);
            }
          }
        }
      }
    }
    return $operationResults;
  }

  /*
  protected static function performOperationsBySection(&$obj)
  {
    $operationResults = array();
    $ignore = static::$ignore;
    foreach ($obj as $key => &$section) {
      if (!in_array($key, $ignore)) {
        $model = static::parseModel($key);
        if ($model) {
          // If we're deleting the entire investment, just unset all sections
          if (static::$deleteEntireInvestment && $model != 'ContractsReportDetails' && $model != 'InvestmentReportDetail' && $model != 'SystemsInventoryDetails') {

            $section = array();
            continue;
          }
          // Otherwise perform the operations
          if (static::allowsMultiple($key)) {
            foreach ($section as $skey => &$subSection) {
              if (!in_array($skey, $ignore)) {
                if (isset($subSection[0])) {
                  foreach ($subSection as $key => &$sec) {
                    $operationResults[] = static::performOperation($model, $sec);
                  }
                } else {
                  $operationResults[] = static::performOperation($model, $subSection);
                }
              }
            }
          } else {
            $operationResults[] = static::performOperation($model, $section);
          }
        }
      }
    }
    return $operationResults;
  }
*/
  /**
   * Unset data from a deleted object, leaving only the operation and identifier.
   *
   * @param $obj - section data
   */
  protected static function unsetDeletedObjectValues(&$obj) {
    $skip = array('operation', 'UII'); // identifier is UII in this case, we don't want to show the id in the response file.
    if (!is_array($obj)) return; //not sure how we got here but just in case
    foreach ($obj as $key => $val) {
      if (in_array($key, $skip)) continue;
      unset($obj[$key]);
    }
  }

  /*
   * Sets unset fields for the stored procedures
   * This is useful when updating a contracts
   *
   * @param array $obj - section data
   *
   */
  protected static function fillUnsetValues($obj, $details, &$params) {
    $id = static::getIdFromRequest($details['UII'], $obj['contractID'] ?? null, $obj['agencyContractID'] ?? null);
    if ($id !== null) {
      $values = static::get(BaseReport::getPDO(), $id);
      foreach ($values as $key => $val) {
        if (strlen($val) > 0 && !isset($obj[$key]) && !isset($details[$key])) {
          $params[$key] = $val;
        }
      }
    }
  }

  /*
   * Replaces object values with corresponding DB values
   *
   * @param array $obj - section data
   * @param object $pdo - PDO object to perform operations with
   *
   * @return void - adjusts object values
   */
  protected static function fillObjectWithDBValues(&$obj, $pdo) {
    $skip = array('operation', 'metricActuals', 'activities');
    // ITD-1888 These values should not be returned as part of the response
    $remove = array('id');
    $id = $obj[GeneralDataReportsDetails::$identifier];
    $dbVals = static::get($pdo, $id);



    if (!is_array($obj)) return; //not sure how we got here but just in case
    foreach ($obj as $key => $val) {
      if (in_array($key, $skip)) continue;

      // _err([$id, $dbVals],1,__FILE__,__LINE__);
      if (isset($dbVals[$key]) && $dbVals[$key] != '') {
        $obj[$key] = $dbVals[$key];
      } else { //if the value isn't in the db, unset it so we see there was an error in saving
        $obj[$key] = '';
      }
      if (in_array($key, $remove)) unset($obj[$key]);
    }
  }


  /**
   * Prototype to get data for a section of the submission.
   *
   * @param object $pdo - PDO object to perform operations with
   * @param int $id - value of the identifier related to this section of the submission
   *
   * @return array
   */
  public static function get($pdo, $id)
  {
    $args[] = (int)$id; 
    $where['id'] = ' id = ?';

    list($sth, $args) = static::generalReportAgencySql(GeneralDataReportsDetails::$tablePrefix, static::getFields(true), $where, $args);
    $sth->execute($args);
    $result = array();
    if ($sth->rowCount()) {
      $result = $sth->fetch(\PDO::FETCH_ASSOC);
      foreach ($result as $field => $val) {
        if (isset(GeneralDataReportsDetails::$fieldList[$field]) && isset(GeneralDataReportsDetails::$fieldList[$field]['type']) && GeneralDataReportsDetails::$fieldList[$field]['type'] == 'array') {
          $result[$field] = explode('|', $val);
        }

      }
    }
    return $result;
  }

  /**
   * Prototype to get all data for a section of the submission.
   *
   * @param object $pdo - PDO object to perform operations with
   *
   * @return array
   */
  public static function getAll($pdo, $uii)
  {
    $tablePrefix = GeneralDataReportsDetails::$tablePrefix;
    $where['id'] = "$tablePrefix.currentUII = ?";
    $args[] = $uii;
    list($sth, $args) = static::generalReportAgencySql($tablePrefix, static::getFields(true), $where, $args);
    $sth->execute($args);
    $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
   
    return $result;
  }
  
  /**
   * Will check if we are using an agencyContractID by UII
   * 
   * @param string $uii the uii for the investment
   * @param string $agencyContractID the agencyContractID
   * @return bool|int false if not found or an integer if found
   */
  public static function agencyContractIdUIIExists($uii, $agencyContractID = null) {
    $tablePrefix = GeneralDataReportsDetails::$tablePrefix;
    
    // see  GeneralDataReportsDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralDataReportsDetails::$fieldList[GeneralDataReportsDetails::$identifier]['dbName'];
    
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "$tablePrefix.currentUII = ?";
      $args[] = $uii;
    }
    
    // Add agencyContractID or contractID to the where clause
    if (!empty($agencyContractID)) {
      $where['id'] = "$tablePrefix.agencyContractID = ?";
      $args[] = $agencyContractID;
      static::$agencyIdentifier = 'agencyContractID';
    }
    
    $rowId = false;
    // required to search by UII and agencyContractID or contractID
    if (isset($where['currentUII'], $where['id']) && count($args) == 2) {
      list($sth, $args) = static::generalReportAgencySql($tablePrefix, "$tablePrefix.$identifier", $where, $args);
      $sth->execute($args);
      $rowId = $sth->rowCount() > 0 ? $sth->fetch(\PDO::FETCH_NUM) : false;
    }
    
    return $rowId;
  }
  
  /**
   * Will find a row matching UII and agencyContractID or UII and contractID
   * 
   * @param string a UII for the contract at the investment level
   * @param string a database id value
   * @param string an agencyContractID value
   * @return boolean|int id field from the database for row
   */
  public static function getIdFromRequest($uii, $contractID = null, $agencyContractID = null) {
    $tablePrefix = GeneralDataReportsDetails::$tablePrefix;
    
    // see  GeneralDataReportsDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralDataReportsDetails::$fieldList[GeneralDataReportsDetails::$identifier]['dbName'];
    
    $args = array();
    
    // Add agencyContractID and UII to the where clause
    if (isset($uii, $agencyContractID)) {
      if (is_array($agencyContractID)) {
        $agencyContractID = '';
      }
      $where['agencyCurrentUII'] = "($tablePrefix.agencyContractID = ? AND $tablePrefix.currentUII = ?)";
      $args[] = $agencyContractID;
      $args[] = $uii;
      static::$agencyIdentifier = 'agencyContractID';
    }
    // Add contractID and UII to the where clause
    if (isset($uii, $contractID)) {
      if (is_array($contractID)) {
        $contractID = '';
      }
      $where['contractCurrentUII'] = "($tablePrefix.$identifier = ? AND $tablePrefix.currentUII = ?)";
      $args[] = $contractID;
      $args[] = $uii;
    }
    
    $rowId = false;
    $argsc = count($args);
    // required to search by UII and agencyContractID or contractID
    if ((isset($where['agencyCurrentUII'])  || isset($where['contractCurrentUII'])) 
            && ($argsc === 2 || $argsc === 4)) {
      list($sth, $args) = static::generalReportAgencySql($tablePrefix, "$tablePrefix.$identifier", $where, $args);
      $sth->execute($args);
      $rowId = $sth->rowCount() > 0 ? $sth->fetchColumn() : false;
    }
    
    return $rowId;
  }
  
  /**
   * Will find a group of matching contract investments by UII
   * 
   * @param string a UII for the contract at the investment level
   * @param int integer for limit
   * @return boolean|array ids from the database for UII
   */
  public static function getIdsFromUii($uii, $limit = false) {
    $args = [];
    $tablePrefix = GeneralDataReportsDetails::$tablePrefix;
    
    // see  GeneralDataReportsDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralDataReportsDetails::$fieldList[GeneralDataReportsDetails::$identifier]['dbName'];
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "$tablePrefix.currentUII = ?";
      $args[] = $uii;
    }
    $rowIds = false;
    // required to search by UII
    if ((isset($where['currentUII']) || !empty($where['currentUII'])) && count($args) === 1) {
      list($sth, $args) = static::generalReportAgencySql($tablePrefix, "$tablePrefix.$identifier", $where, $args, $limit);
      $sth->execute($args);
      $rowIds = $sth->rowCount() > 0 ? $sth->fetchColumn(0) : false;
    }
    
    return $rowIds;
  }

  /**
   * Use to return count of investment contracts for uii
   * 
   * @param string $uii
   * @return boolean|int the count or false on no results
   */
  public static function getCountFromUii($uii) {
    $tablePrefix = GeneralDataReportsDetails::$tablePrefix;
    
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "$tablePrefix.currentUII = ?";
      $args[] = $uii;
    }
    
    $rowIds = false;
    // required to search by UII
    if (isset($where['currentUII']) && count($args) == 1) {
      list($sth, $args) = static::generalReportAgencySql($tablePrefix, "COUNT(*) as count", $where, $args, $limit);
      $sth->execute($args);
      $rowIds = $sth->rowCount() > 0 ? $sth->fetchColumn(0) : false;
    }
    
    return $rowIds;
  }
  
  /**
   * Returns report PDOStatement containing the agency check
   * 
   * @param string $tablePrefix prefix of table for the generalReports table and fields
   * @param string $fields fields with table prefix
   * @param array $where an array of containing each part of the where clause
   * @param array $args an array containing the arguments used in a PDOStatement::execute call
   * @return array with index 0 a PDOStatement and index 1 the arguments
   */
  private static function generalReportAgencySql($tablePrefix, $fields, $where, $args, $limit = false) {
    list($itPortfolioTable, $where, $args) = Utility\UtilityFunctions::getItPortfolioJoin($tablePrefix, $where, $args);
    
    $sql = "SELECT %s FROM %s as %s INNER JOIN %s WHERE %s";
    $params = array($fields, static::$table, $tablePrefix, $itPortfolioTable, implode(' AND ', $where));
    if (is_int($limit)) {
      $sql .= " LIMIT %s";
      $params[] = $limit;
    }
    $sql = vsprintf($sql, $params);
    $pdo = Utility\UtilityFunctions::getPDO();
    
    return array($pdo->prepare($sql), $args);
  }

  /**
   * Helper function to create SQL for getting data for a section of the submission.
   *
   * @return string
   */
  protected static function getFields($tblPrefix = false)
  {
    $query = '';
    $first = true;

    // _err(static::$fieldList,1, __FILE__, __LINE__);
    foreach (GeneralDataReportsDetails::$fieldList as $field => $val) {
      if (empty($val['skipOnGet'])) {
        if (!$first) {
          $query .= ",";
        }
        $first = false;
        if ($tblPrefix === true) {
          $query .= GeneralDataReportsDetails::$tablePrefix . ".";
        }
        if (isset($val['dbName'])) {
          $query .= "`$val[dbName]` as `$field`";
        }
      }
    }
    return $query;
  }

  /**
   * Validate identifiers and set record ID from agency ID for a section of the submission.
   *
   * @param array $obj - section data
   * @param boolean $validate - is function being called by a validator?
   *
   * @return mixed - null on success, string error message on failure
   */
  public static function processIdentifiers(&$obj, $validate = false)
  {
    // Lookup the model type by class name, e.g. IRProject = projects
    $models = array_flip(GeneralReportContractsReport::$models);

    $class = preg_replace('/[A-Za-z0-9\\\\]+\\\\([A-Za-z0-9]+)$/', '$1', get_called_class());

    $type = isset($models[$class]) ? $models[$class] : "";
    if (!$type) {
      return "The system encountered an unexpected error. (Model type not found)";
    }

    // If this is not a section we need to worry about here, skip this part
    $skip = array('logicalStructure');
    if (in_array($type, $skip)) {
      return;
    }

    if ($validate) {
      // Check for duplicate UIIs in the XML
      if ($type == 'details') {
        if (array_key_exists($obj['UII'], GeneralReportContractsReport::$identifiers['xml'][$type])) {
          return "Detected duplicate UII: {$obj['UII']}";
        } else {
          GeneralReportContractsReport::$identifiers['xml'][$type][$obj['UII']] = 1;
        }
      }

      // Check for duplicate record IDs in the XML
      if ($type != 'details' && isset($obj[static::$identifier]) && $obj[static::$identifier]) {
        if (in_array($obj[static::$identifier], GeneralReportContractsReport::$identifiers['xml'][$type])) {
          return "Detected duplicate " . static::$identifier . ": {$obj[static::$identifier]}";
        } else {
          GeneralReportContractsReport::$identifiers['xml'][$type][] = $obj[static::$identifier];
        }
      }

      // Check for duplicate agency IDs in the XML
      if ($type != 'details' && isset($obj[static::$agencyIdentifier]) && $obj[static::$agencyIdentifier]) {
        // Agency IDs are prefixed with SIR ID in the XML identifiers digest to create a unique index
        $index = GeneralReportContractsReport::$reportUII . '|' . $obj[static::$agencyIdentifier];
        if (in_array($index, GeneralReportContractsReport::$identifiers['xml'][$type])) {
          return "Detected duplicate " . static::$agencyIdentifier . ": {$obj[static::$agencyIdentifier]}";
        } else {
          GeneralReportContractsReport::$identifiers['xml'][$type][] = $index;
        }
      }
    }

    // If the submission specifies a record ID, make sure it really belongs to this investment
    if ($type != 'details' && isset($obj[static::$identifier]) && $obj[static::$identifier]) {
      // Record IDs are prefixed with SIR ID in the DB identifiers digest to verify the SIR association
      $id = GeneralReportContractsReport::$reportUII . '|' . $obj[static::$identifier];
      if (!array_key_exists($id, GeneralReportContractsReport::$identifiers['db'][$type])) {
        return "A record with " . static::$identifier. " {$obj[static::$identifier]} was not found for this investment.";
      }
    }

    // Otherwise if an update submission specifies an agency ID, set the related record ID
    elseif ($type != 'details' && isset($obj[static::$agencyIdentifier]) && $obj[static::$agencyIdentifier]) {
      // Agency IDs are prefixed with SIR ID in the DB identifiers digest to verify the SIR association
      $aid = GeneralReportContractsReport::$reportUII . '|' . $obj[static::$agencyIdentifier];
      if (in_array($aid, GeneralReportContractsReport::$identifiers['db'][$type])) {
        $id = array_search($aid, GeneralReportContractsReport::$identifiers['db'][$type]);
        // Strip the SIR ID prefix before setting the record ID
        $obj[static::$identifier] = str_replace(GeneralReportContractsReport::$reportUII . '|', '', $id);
      }
    }
  }


  /**
   * New method for performing delete one or multiple entries by ID
   * @param $obj
   * @param $details
   * @param $pdo
   * @param $procedure
   *
   * @return mixed
   */
  static function performDeleteOperation($obj, $details, $pdo, $procedure) {
    $params = static::getIdFromRequest($details['UII'], $obj['contractID'] ?? null, $obj['agencyContractID'] ?? null);
    //$params = static::prepareProcedure($obj, $details);
    if ($params != false) {
      // $procedure = 'testNow';
      $sql = "CALL $procedure(?)";
      $sth = $pdo->prepare($sql);
      $sth->execute(array($params));
      $result = $sth->fetchColumn(0);
      $sth->closeCursor();
      // Normal results are expected to be a single numeric value, but some errors
      // are returned in multiple columns, so we must use FETCH_ASSOC instead of
      // FETCH_COLUMN and do some gymnastics to extract normal results
      // TODO: develop.fy21 new function for checking database results and returning them
      $errorResult = self::processDatabaseErrors($details, $result, $procedure, $sth);
    } else {
      $result = array();
      $errorResult = self::processDatabaseErrors($obj+$details, 'deletion failed', $procedure, '');
    }
    
    if ($errorResult['error']) {
      return $errorResult['message'];
    } else {
      // If we get here, it's a normal result -- hooray!
      $obj = array(GeneralDataReportsDetails::$identifier => $errorResult['data']) + $obj;
      // TODO: not sure what this does exactly??!
      static::fillObjectWithDBValues($obj, $pdo);
      return $errorResult['data'];
    }
  }

  /**
   * New method for performing delete all entries with UII
   * @param $obj
   * @param $details
   * @param $pdo
   * @param $procedure
   *
   * @return mixed
   */
  static function performDeleteAllOperation($obj, $details,  $pdo, $procedure) {

    // _err($details['UII'], 1, __FILE__, __LINE__);

    if (Utility\UtilityFunctions::validateUIIForAgency($details['UII'])) {
      $sql = "CALL $procedure(?)";
      $sth = $pdo->prepare($sql);
      $sth->execute(array($details['UII']));
      $result = $sth->fetch(\PDO::FETCH_ASSOC);
      $sth->closeCursor();

      // _err($result, 1, __FILE__, __LINE__);
      // Normal results are expected to be a single numeric value, but some errors
      // are returned in multiple columns, so we must use FETCH_ASSOC instead of
      // FETCH_COLUMN and do some gymnastics to extract normal results
      if (is_array($result) && !empty($result)) {
        $keys = array_keys($result);
        if (count($keys) == 1) {
          $result = $result[$keys[0]];
        }
      }

      // _err($procedure);
      // TODO: develop.fy21 new function for checking database results and returning them
      $errorResult = self::processDatabaseErrors($details, $result, $procedure, $sth);
    } else {
      $result = array();
      $errorResult = self::processDatabaseErrors($obj + $details, 'deletion failed', $procedure, '');
    }



    // _err($errorResult,1, __FILE__,__LINE__ );
    // _err($errorResult,1,__FILE__,__LINE__);
    if($errorResult['error']) {
      return $errorResult['message'];
    } else {
      // If we get here, it's a normal result -- hooray!
      $obj = array(GeneralDataReportsDetails::$identifier => $errorResult['data']) + $obj;

      // TODO: not sure what this does exactly??!
      static::fillObjectWithDBValues($obj, $pdo);
      return $errorResult['data'];
    }


  }


  /**
   * Add/Update operation
   * @param $mainOperation
   * @param $operation
   * @param $model
   * @param $obj
   * @param $pdo
   * @return mixed
   */
  static function performAddUpdateOperation(&$obj, $details, $pdo, $procedure, $add = true) {
    // begin with no error
    $errorResult['error'] = 0;
    
    $params = static::prepareProcedure($obj, $details);    // _err(["2", $procedure, $params],1, __FILE__, __LINE__);

    // This will updated a contract
    if ($add === false) {
      unset($params['agencyContractID']);
      
      // Do not do the update becuase we cannot find the primary key value contractID
      // See GeneralDataReportsDetails::$identifier and GeneralContractsBaseModel::getIdFromRequest
      if ($params['contractID'] === false) {
        $errorResult['error'] = 1;
        $errorResult['message'] = sprintf('000627-CON: contractReport not found using: %s of %s', static::$agencyIdentifier, $obj['contractID'] ?? $obj['agencyContractID'] ?? null);
      }
    } 
    // this will add a new contract
    else if ($add === true) {
      if (array_key_exists('agencyContractID', $params)) {
        static::$agencyIdentifier = 'agencyContractID';
        $params['agencyContractID'] = trim($params['agencyContractID']);
        // Do not do the insert by making this error message, $errorResult
        // This is when a duplicate agencyContractID exists
        if (static::agencyContractIdUIIExists($params['UII'], $params['agencyContractID'])) {
          $errorResult['error'] = 1;
          $errorResult['message'] = "000628-CON: For UII of {$params['UII']}, agencyContractID, {$params['agencyContractID']} must be unique.";
        }
      }
      // Add operation cannot contain contractID which is the primary key
      // GeneralDataReportsDetails::$identifier should reflect that
      unset($params['contractID']);
    }
    
    if (!$errorResult['error']) {
      foreach($params as $key=>$param) {
        // ignore fields we don't need for ADDS
        if ($key == 'comment') {
          unset($params['comment']);
        }
        if ($key == 'contractId') {
          unset($params['contractId']);
        }
        if ($key == 'operation') {
          // _err([$field,$val], 0, __FILE__, __LINE__);
          unset($params['operation']);
        }
      }
      $qmarks = implode(',', array_fill(0, count($params), '?'));
      $sql    = "CALL $procedure($qmarks)";
      $sth    = $pdo->prepare($sql);    $sth->execute(array_values($params));
      $result = $sth->fetch(\PDO::FETCH_ASSOC);
      $sth->closeCursor();

      // Normal results are expected to be a single numeric value, but some errors
      // are returned in multiple columns, so we must use FETCH_ASSOC instead of
      // FETCH_COLUMN and do some gymnastics to extract normal results
      if (is_array($result) && !empty($result)) {
        $keys = array_keys($result);
        if (count($keys) == 1) {
          $result = $result[$keys[0]];
        }
      }

      // TODO: develop.fy21 new function for checking database results and returning them
      $errorResult = self::processDatabaseErrors($obj, $result, $procedure, $sth);
    }

    // _err($errorResult,1, __FILE__,__LINE__ );
    // _err($errorResult,1,__FILE__,__LINE__);
    if($errorResult['error']) {
      return $errorResult['message'];
    } else {
      // If we get here, it's a normal result -- hooray!
      $obj = array(GeneralDataReportsDetails::$identifier => $errorResult['data']) + $obj;

      // TODO: not sure what this does exactly??!
      static::fillObjectWithDBValues($obj, $pdo);

      // _err([static::fillObjectWithDBValues($obj, $pdo), $errorResult['data']],1,__FILE__,__LINE__);
      return $errorResult['data'];
    }
  }



  /**
   * Prepare stored procedure parameters from section data.
   *
   * @param array $obj - section data
   *
   * @return array - section data prepared for PDO
   */
  protected static function prepareProcedure(&$obj, $details)
  {

    // TODO: develop.fy21 object is missing last two fields!!!
    // _err($details, 1, __FILE__, __LINE__);
    // static::prepareData($obj);

    $fields   =  static::prepareData($obj);

    // _err([$fields,$obj] , 1, __FILE__, __LINE__);
    
    // The $params variable relies on the order of GeneralDataReportsDetails::$fieldList ordering.
    // It should not be changed or you will have to change the stored procedure ordering.
    $params = array_fill_keys(array_keys(GeneralDataReportsDetails::$fieldList), '');
    static::fillUnsetValues($obj, $details, $params);
    $params['UII']              = $details['UII'];
    if (isset($details['reportsContracts'])) {
      $params['reportsContracts'] = $details['reportsContracts'];
    }
    if (isset($details['agencyContractID'])) {
      $params['agencyContractID'] = $details['agencyContractID'];
    }


    // _err($fields, 1, __FILE__, __LINE__);
    // TODO: use $ignore
    foreach ($fields as $field => $val) {

      // the operation should not be sent to any stored procedures any more at least!
      if ($field == 'operation') {
        continue;
      }
      // ignore fields that are nested children, e.g. metric actuals
      if (isset($val['children'])) {
        continue;
      }
      // ignore duplicated fields
      if (isset($obj[$field]) && is_array($obj[$field]) && count($obj[$field]) > 1) {
        continue;
      }

      // nullify any fields that are empty
      if (empty($obj[$field])) {
        $obj[$field] = NULL;
      }
      //$params[] = $obj[$field];
      // better to send entire object with keys!!!
      $params[$field] = $obj[$field];

    }

    // TODO: develop.fy21 use Utility Function
    $params['timePeriodID'] = Utility\UtilityFunctions::getLatestSubmittedTimePeriodForAgency(Utility\UtilityFunctions::getAgency());
    $user                   = BaseReport::getUser();
    $params['updatedBy']    = $user->email;


    // TODO: develop.fy20, still need time period and updated by



    // _err(["2", $params],1, __FILE__, __LINE__);
    return $params;
  }



  /**
   * Make any transformations needed on the data prior to save.
   * For example, date fields read in in the format "mm/dd/yy" need
   * to be transformed to YYYY-mm-dd prior to save.
   *
   * @param array $obj
   * @returns array
   */
  /*
  protected static function prepareData(&$obj)
  {
    $obj['id'] = GeneralReport::$reportPrimaryID;

    // _err(["GeneralDataReportsDetails::fieldList", GeneralDataReportsDetails::$fieldList],0, __FILE__, __LINE__);

    foreach (GeneralDataReportsDetails::$fieldList as $field => $val) {

      // _err(["2", $field, $val], 0, __FILE__, __LINE__);

      // if type value is NOT empty
      if (!empty($val['type'])) {
        $type = $val['type'];

        // if type is date
        if ($type == 'date' && isset($obj[$field]) && !empty($obj[$field])) {
          if (strtoupper($obj[$field]) == 'N/A') {
            $obj[$field] = NULL;
          } else {
            $obj[$field] = strtotime($obj[$field]) ? date("Y-m-d", strtotime($obj[$field])) : NULL;
          }
        }

        // TODO: develop.fy21 no moare implodes and arrays of PIID


      }
    }
    // _err(["obj", $obj, GeneralDataReportsDetails::$fieldList], 1, __FILE__, __LINE__);
    return $obj;
  }
  */

  protected static function prepareData(&$obj)
  {
    // modifies special fields such as dates and arrays such as OLD contracts reports PIIDS
    foreach (static::$fieldList as $field => $val) {
      if (!empty($val['type'])) {
        $type = $val['type'];
        if ($type == 'date' && isset($obj[$field]) && !empty($obj[$field])) {
          if (strtoupper($obj[$field]) == 'N/A') {
            $obj[$field] = NULL;
          } else {
            $obj[$field] = strtotime($obj[$field]) ? date("Y-m-d", strtotime($obj[$field])) : NULL;
          }
        }
        if ($type == 'array' && isset($obj[$field]) && !empty($obj[$field]) && is_array($obj[$field])) {
          $key = key($obj[$field]);
          if (isset($obj[$field][$key]) && is_array($obj[$field][$key])) {
            // Remove any blank nodes submitted
            // blank nodes are represeneted by blank arrays
            foreach ($obj[$field][$key] as $subkey=>$value) {
              if (is_array($value)) {
                unset($obj[$field][$key][$subkey]);
              }
            }
            $obj[$field] = implode('|', $obj[$field][$key]);
          } else if (isset($obj[$field][$key])) {//singular
            $obj[$field] = $obj[$field][$key];
          }
        }
      }
    }
    // _err(["GeneralDataReportsDetails::fieldList", $obj],1, __FILE__, __LINE__);
    return $obj;
  }

  /**
   * New method for checking results
   * @param $model
   * @param $obj
   */
  static function checkResults($model, &$obj) {

  }


  /**
   * @param $obj
   * @param $result
   * @param $procedure
   * @param $pdo
   * @param $sth
   *
   * @return array
   */
  static function processDatabaseErrors($obj, $result, $procedure, $sth) {

    // _err([$obj, $result],1, __FILE__, __LINE__);
    if ($procedure) {

      // _err($procedure);
      // Normal results should now be a non-zero integer; if not, it's an error
      if (empty($result) || !is_numeric($result)) {

        // Log some info that might be useful for debugging
        $err = array('msg' => $result, 'pdo' => ($sth instanceOf \PDOStatement ? $sth->errorInfo() : ''), 'data' => $obj);
        error_log("Error: A call to $procedure failed: " . json_encode($err));

        // If this error is from trying to delete a record that does not exist,
        // translate the error message into something more friendly for the user
        if (is_string($result)) {
          if (preg_match('/^.*GeneralDataReports.*$/', $result) === 1 && isset($obj['UII']) && !empty($obj['UII'])) {
            $result = "Deletion failed for UII {$obj['UII']}.";
          } elseif (strstr($result, 'deletion failed') && isset($obj[static::$agencyIdentifier]) && !empty($obj[static::$agencyIdentifier])) {
            $result .= " for " . static::$agencyIdentifier . " '{$obj[static::$agencyIdentifier]}'";
          }
          return [
            'error'=>1,
            'message'=> "000617-CON: ".$result,
            'data'=>NULL
          ];
        }
        // results are fine
        return [
          'error'=>0,
          'message'=>NULL,
          'data'=>$result
        ];
      } else {
        return [
          'error'=>0,
          'message'=>NULL,
          'data'=>$result
        ];
      }
    }
  }
}
