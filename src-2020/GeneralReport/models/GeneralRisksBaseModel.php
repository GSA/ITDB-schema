<?php

namespace Pittsburgh\Controllers\ITDB2;

require_once("Pittsburgh/Controllers/ITDB2/GeneralReport.php");
require_once("Pittsburgh/Lib/Utility/UtilityFunctions.php");

use Pittsburgh\lib\Utility as Utility;

class GeneralRisksBaseModel {

  static $fieldList = array();
  static $ignore = array('operation', 'comment');
  static $agencyIdentifier = 'riskArea';
  static $validator;
  static $primaryID;
  const RISK_MAX = 13;
  
  /**
   * @param array $obj - section data
   * @param object $pdo - PDO object to perform operations with
   *
   * @return mixed - int|string record ID on success, error message on failure
   */
  static function performOperation($operation, $details, &$obj)
  {
    $pdo       = BaseReport::getPDO();
    
    $results = array();

    switch ($operation) {
      case 'add':
        $procedure = GeneralRisksDetails::$addProcedure;
        $results   = self::performAddUpdateOperation($obj, $details, $pdo, $procedure, true);
        break;
      case 'update':
        $procedure = GeneralRisksDetails::$updateProcedure;
        $results   = self::performAddUpdateOperation($obj, $details, $pdo, $procedure, false);
        break;
      case 'delete-all':
        $procedure = GeneralRisksDetails::$deleteAllProcedure;
        $results   = self::performDeleteOperation($obj, $details, $pdo, $procedure);
        break;
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
    return isset(GeneralReportRisksReport::$models[$section]) ? GeneralReportRisksReport::$models[$section] : false;
  }

  /**
   * Determine if a submission section may consist of repeating instances,
   * e.g. "projects" section can be one or many projects.
   *
   * @param string $section - submission section (XML) name
   *
   * @return bool
   */
  protected static function allowsMultiple($section)
  {
    $mult = ['Risks'];
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
                      $operation          = isset(GeneralReport::$operation) ? GeneralReport::$operation: NULL;
                      $operationResults[] = self::performOperation($operation, $details, $sec);
                    }
                  } else { // if we are NOT in a repeating part of XML, or ONE risk is in the ADD
                    $operation = isset(GeneralReport::$operation) ? GeneralReport::$operation: NULL;
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
   * Sets unset fields for the stored procedures
   * This is useful when updating a risks
   *
   * @param array $obj - section data
   *
   */
  protected static function fillUnsetValues($obj, $details, &$params) {
    $id = static::getIdFromRequest($details['UII'], $obj['riskArea']);
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
   * @param int $id - the primary key of a risk
   * 
   * @return void - adjusts object values
   */
  protected static function fillObjectWithDBValues(&$obj, $pdo, $id = null) {
    $skip = array('operation', 'metricActuals', 'activities');
    // ITD-1888 These values should not be returned as part of the response
    $remove = array('id');
    if ($id !== null) {
      $dbVals = static::get($pdo, $id);
    }
    
    if (!is_array($obj)) return; //not sure how we got here but just in case
    foreach ($obj as $key => $val) {
      if (in_array($key, $skip)) continue;
        
      if (isset($dbVals[$key])) {
        $obj[$key] = $dbVals[$key];
      } else if ($key !== 'ErrorsAndWarnings') {
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
    // see  GeneralRisksDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralRisksDetails::$fieldList[GeneralRisksDetails::$identifier]['dbName'];
    
    $args[] = (int)$id; 
    $where['id'] = sprintf(' %s = ?', $identifier);

    list($sth, $args) = static::generalReportAgencySql(GeneralRisksDetails::$tablePrefix, static::getFields(true), $where, $args);
    $sth->execute($args);
    $result = array();
    if ($sth->rowCount()) {
      $result = $sth->fetch(\PDO::FETCH_ASSOC);
      foreach ($result as $field => $val) {
        if (isset(GeneralRisksDetails::$fieldList[$field]) && isset(GeneralRisksDetails::$fieldList[$field]['type']) && GeneralRisksDetails::$fieldList[$field]['type'] == 'array') {
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
    $tablePrefix = GeneralRisksDetails::$tablePrefix;
    $where['id'] = "$tablePrefix.currentUII = ?";
    $args[] = $uii;
    list($sth, $args) = static::generalReportAgencySql($tablePrefix, static::getFields(true), $where, $args);
    $sth->execute($args);
    $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }
  
  /**
   * Returns a count of UIIs found matching in the risks database, should be 1 or false
   * 
   * @param string $uii UII for deployment report row
   * @return bool|int false or count of UII found
   */
  public static function getCountFromUii($uii) {
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "itp.currentUII = ?";
      $args[] = $uii;
    }

    $rowIds = false;
    // required to search by UII
    if (isset($where['currentUII']) && count($args) == 1) {
      list($sth, $args) = static::generalReportAgencySql(GeneralRisksDetails::$tablePrefix, "COUNT(*) as count", $where, $args);
      $sth->execute($args);
      $rowIds = $sth->rowCount() > 0 ? $sth->fetchColumn(0) : false;
    }

    return $rowIds;
  }
  
  /**
   * Returns a count of UIIs found matching in itPortfolio, should be 1 or false
   * 
   * @param string $uii UII for deployment report row
   * @return bool|int false or count of UII found
   */
  public static function getCountFromUiiVerifyItPortfolio($uii) {
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "itp.currentUII = ?";
      $args[] = $uii;
    }

    $rowIds = false;
    // required to search by UII
    if (isset($where['currentUII']) && count($args) == 1) {
      list($sth, $args) = static::generalReportUIISql("COUNT(*) as count", $where, $args);
      $sth->execute($args);
      $rowIds = $sth->rowCount() > 0 ? $sth->fetchColumn(0) : false;
    }

    return $rowIds;
  }

  /**
   * 
   * 
   * @param string $uii CurrentUII
   * @param int $riskArea the id, 1-13 of the riskArea
   * @return bool|int generalDataRiskReports.deploymentReportsID or count of rows found
   */
  public static function getIdFromRequest($uii, $riskArea) {
    $tablePrefix = GeneralRisksDetails::$tablePrefix;
    
    // see  GeneralRisksDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralRisksDetails::$fieldList[GeneralRisksDetails::$identifier]['dbName'];
    
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "$tablePrefix.currentUII = ?";
      $args[] = $uii;
    }
    
    // Add currentUII to the where clause
    if (!empty($riskArea)) {
      $where['riskArea'] = "$tablePrefix.riskArea = ?";
      $args[] = $riskArea;
    }
    
    $rowId = false;
    // required to search by UII and riskArea
    if (isset($where['currentUII'], $where['riskArea']) && count($args) === 2) {
      list($sth, $args) = static::generalReportAgencySql($tablePrefix, "$tablePrefix.$identifier", $where, $args);
      $sth->execute($args);
      $rowId = $sth->rowCount() > 0 ? $sth->fetchColumn(0) : false;
    }
    
    return $rowId;
  }
  
  /**
   * Will find a group of matching risk investments by UII
   * 
   * @param string a UII for the risk
   * @param int integer for limit
   * @return boolean|array ids from the database for UII
   */
  public static function getIdsFromUii($uii, $limit = false) {
    $tablePrefix = GeneralRisksDetails::$tablePrefix;
    
    // see  GeneralRisksDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralRisksDetails::$fieldList[GeneralRisksDetails::$identifier]['dbName'];
    
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "$tablePrefix.currentUII = ?";
      $args[] = $uii;
    }
    
    $rowId = false;
    // required to search by UII and agencyContractID or contractID
    if (isset($where['currentUII']) && count($args) === 1) {
      list($sth, $args) = static::generalReportAgencySql($tablePrefix, "$tablePrefix.$identifier", $where, $args, $limit);
      $sth->execute($args);
      $rowId = $sth->rowCount() > 0 ? $sth->fetch(\PDO::FETCH_ASSOC) : false;
    }
    
    return $rowId;
  }
  
  /**
   * Will return a PDO statement and arguments for a PDOStatment::execute method call
   * 
   * @param string $sql SQL for use with sprintf
   * @param int $limit integer of max limit
   * @param int $offset integer of offset start
   * @param array $params array of parameters that can be used with sprintf for the $sql parameter
   * @param array $args arguments that can be used in a PDOStatement::execute method call
   */
  private static function finalizePDO($sql, $limit, $offset, $params, $args) {
    if (is_int($limit)) {
      $sql .= " LIMIT %s";
      $params[] = $limit;
    }
    $sql = vsprintf($sql, $params);
    $pdo = Utility\UtilityFunctions::getPDO();
    
    return array($pdo->prepare($sql), $args);
  }
  
  /**
   * Returns report PDOStatement containing the model table, agency check
   * 
   * @param string $tablePrefix prefix of table for the generalReports table and fields
   * @param string $fields fields with table prefix
   * @param array $where an array of containing each part of the where clause
   * @param array $args an array containing the arguments used in a PDOStatement::execute call
   * @param int $limit an integer of the limit
   * @return array with index 0 a PDOStatement and index 1 the arguments
   */
  private static function generalReportAgencySql($tablePrefix, $fields, $where, $args, $limit = false) {
    list($itPortfolioTable, $where, $args) = Utility\UtilityFunctions::getInvestmentJoinT1T5($tablePrefix, $where, $args);
    
    $sql = "SELECT %s FROM %s as %s INNER JOIN %s WHERE %s";
    $params = array($fields, GeneralRisksDetails::$table, $tablePrefix, $itPortfolioTable, implode(' AND ', $where));
    
    return static::finalizePDO($sql, $limit, null, $params, $args);
  }
  
  /**
   * Returns report PDOStatement containing the model table, agency check
   * 
   * @param string $tablePrefix prefix of table for the generalReports table and fields
   * @param string $fields fields with table prefix
   * @param array $where an array of containing each part of the where clause
   * @param array $args an array containing the arguments used in a PDOStatement::execute call
   * @param int $limit an integer of the limit
   * @return array with index 0 a PDOStatement and index 1 the arguments
   */
  private static function generalReportUIISql($fields, $where, $args, $limit = false) {
    list($itPortfolioTable, $where, $args) = Utility\UtilityFunctions::getInvestmentJoinT1T5('itp', $where, $args, false, true);
    
    $sql = "SELECT %s FROM %s WHERE %s";
    $params = array($fields, $itPortfolioTable, implode(' AND ', $where));
    
    return static::finalizePDO($sql, $limit, null, $params, $args);
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

    foreach (GeneralRisksDetails::$fieldList as $field => $val) {
      if (empty($val['skipOnGet'])) {
        if (!$first) {
          $query .= ",";
        }
        $first = false;
        if ($tblPrefix === true) {
          $query .= GeneralRisksDetails::$tablePrefix . ".";
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
   * @param int $cid - integer of position in xml tree of the UII for the details section starting at 0
   * 
   * @return mixed - null on success, string error message on failure
   */
  public static function processIdentifiers(&$obj, $validate = false, $cid = null)
  {
    // Lookup the model type by class name, e.g. IRProject = projects
    $models = array_flip(GeneralReportRisksReport::$models);

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
        if (array_key_exists($obj['UII'], GeneralReportRisksReport::$identifiers['xml'][$type])) {
          return "Detected duplicate UII: {$obj['UII']}";
        } else {
          GeneralReportRisksReport::$identifiers['xml'][$type][$obj['UII']] = 1;
        }
      }

      // Check for duplicate record IDs in the XML
      if ($type != 'details' && isset($obj[static::$identifier]) && $obj[static::$identifier]) {
        if (in_array($obj[static::$identifier], GeneralReportRisksReport::$identifiers['xml'][$type])) {
          return "Detected duplicate " . static::$identifier . ": {$obj[static::$identifier]}";
        } else {
          GeneralReportRisksReport::$identifiers['xml'][$type][] = $obj[static::$identifier];
        }
      }

      // Check for duplicate agency IDs in the XML
      if ($type != 'details' && !is_null($cid) && isset($obj[static::$agencyIdentifier]) && $obj[static::$agencyIdentifier]) {
        // Agency IDs are prefixed with SIR ID in the XML identifiers digest to create a unique index
        $index = GeneralReportRisksReport::getUII($cid) . '|' . $obj[static::$agencyIdentifier];
        if (in_array($index, GeneralReportRisksReport::$identifiers['xml'][$type])) {
          return "Detected duplicate " . static::$agencyIdentifier . ": {$obj[static::$agencyIdentifier]}";
        } else {
          GeneralReportRisksReport::$identifiers['xml'][$type][] = $index;
        }
      }
    }

    // If the submission specifies a record ID, make sure it really belongs to this investment
    if ($type != 'details' && !is_null($cid) && isset($obj[static::$identifier]) && $obj[static::$identifier]) {
      // Record IDs are prefixed with SIR ID in the DB identifiers digest to verify the SIR association
      $id = GeneralReportRisksReport::getUII($cid) . '|' . $obj[static::$identifier];
      if (!array_key_exists($id, GeneralReportRisksReport::$identifiers['db'][$type])) {
        return "A record with " . static::$identifier. " {$obj[static::$identifier]} was not found for this investment.";
      }
    }

    // Otherwise if an update submission specifies an agency ID, set the related record ID
    elseif ($type != 'details' && !is_null($cid) && isset($obj[static::$agencyIdentifier]) && $obj[static::$agencyIdentifier]) {
      // Agency IDs are prefixed with SIR ID in the DB identifiers digest to verify the SIR association
      $aid = GeneralReportRisksReport::getUII($cid) . '|' . $obj[static::$agencyIdentifier];
      if (in_array($aid, GeneralReportRisksReport::$identifiers['db'][$type])) {
        $id = array_search($aid, GeneralReportRisksReport::$identifiers['db'][$type]);
        // Strip the SIR ID prefix before setting the record ID
        $obj[static::$identifier] = str_replace(GeneralReportRisksReport::getUII($cid) . '|', '', $id);
      }
    }
  }

  /**
   * Will delete all 13 of the risks for a UII
   * 
   * @param array $obj
   * @param array $details
   * @param PDO $pdo
   * @param string $procedure
   * @return int
   */
  static function performDeleteOperation(&$obj, $details, $pdo, $procedure) {
    // begin with no error
    $errorResult['error'] = 0;
    
    $params[] = $details['UII'];
    
    if (!GeneralRisksBaseModel::getCountFromUii($details['UII'])) {
      $errorResult['error'] = 1;
      $errorResult['message'] = sprintf("000630-%s: risksReport for UII '%s' not found.  riskAreas must be added %s at a time. Use the add operation before using the delete operation again.",
              GeneralReportDeploymentReport::$errorType, $details['UII'], static::RISK_MAX);
    }

    if (!$errorResult['error']) {
      $qmarks = implode(',', array_fill(0, count($params), '?'));
      $sql    = "CALL $procedure($qmarks)";
      $sth    = $pdo->prepare($sql);    
      $sth->execute(array_values($params));
      $result = $sth->fetch(\PDO::FETCH_ASSOC);
      $sth->closeCursor();

      // Normal results are expected to be a single numeric value, but some errors
      // are returned in multiple columns, so we must use FETCH_ASSOC instead of
      // FETCH_COLUMN and do some gymnastics to extract normal results
      if (is_array($result) && count($result) === 1) {
        $result = current($result);
      }

      $errorResult = self::processDatabaseErrors($obj, $result, $procedure, $sth);
    }

    if($errorResult['error']) {
      return $errorResult['message'];
    } else {
      // reset the obj variable
      unset($obj['Risks'], $obj['comment']);
      
      return $errorResult['data'];
    }
  }
  
 /**
  * Will add 13 risks or update one risk
  * 
  * @param type $obj
  * @param type $details
  * @param type $pdo
  * @param type $procedure
  * @param type $add
  * @return type
  */
  static function performAddUpdateOperation(&$obj, $details, $pdo, $procedure, $add = true) {
    // begin with no error
    $errorResult['error'] = 0;
       
    $params = static::prepareProcedure($obj, $details);    
    
    if (isset($params['ErrorsAndWarnings']['warnings'])) {
      unset($params['ErrorsAndWarnings']);
    }
    
    switch ($add) {
      // This will updated a risk
      case false:
        unset($params[GeneralRisksDetails::$identifier]);
        
        if (GeneralRisksBaseModel::getIdFromRequest($details['UII'], $obj['riskArea']) === false) {
          $errorResult['error'] = 1;
          $errorResult['message'] = sprintf("000627-%s: risksReport not found for UII '%s' and riskArea '%s'", 
                  GeneralReportRisksReport::$errorType, $details['UII'], $obj['riskArea']);
        }
        break;
      // this will add a new risk
      case true:
        unset($params[GeneralRisksDetails::$identifier]);
        break;
    }
    
    if (!$errorResult['error']) {
      $qmarks = implode(',', array_fill(0, count($params), '?'));
      $sql    = "CALL $procedure($qmarks)";
      $sth    = $pdo->prepare($sql);    
      $sth->execute(array_values($params));
      $result = $sth->fetch(\PDO::FETCH_ASSOC);
      $sth->closeCursor();

      // Normal results are expected to be a single numeric value, but some errors
      // are returned in multiple columns, so we must use FETCH_ASSOC instead of
      // FETCH_COLUMN and do some gymnastics to extract normal results
      if (is_array($result) && count($result) === 1) {
        $result = current($result);
      }

      $errorResult = self::processDatabaseErrors($obj, $result, $procedure, $sth);
    }

    if($errorResult['error']) {
      return $errorResult['message'];
    } else {
      // If we get here, it's a normal result -- hooray!
      // This updates the $obj array with the database data which is displayed
      // in the report.
      static::fillObjectWithDBValues($obj, $pdo, $errorResult['data']);

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
    $fields   =  static::prepareData($obj);
    
    // The $params variable relies on the order of GeneralRisksDetails::$fieldList ordering.
    // It should not be changed or you will have to change the stored procedure ordering.
    $params = array_fill_keys(array_keys(GeneralRisksDetails::$fieldList), '');
    static::fillUnsetValues($obj, $details, $params);
    $params['UII']              = $details['UII'];
    
    foreach ($fields as $field => $val) {
      // these should not be sent to any stored procedures any more at least!
      if (in_array($field, array('operation'))) {
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
      
      // better to send entire object with keys!!!
      $params[$field] = $obj[$field];
    }
    
    $user                   = Utility\UtilityFunctions::getUser();
    
   $params['updatedBy']    = $user->email;
   $params['timePeriodID'] = Utility\UtilityFunctions::getLatestSubmittedTimePeriodForAgency(Utility\UtilityFunctions::getAgency());
    
    unset($params['updatedDate']);
    
    return $params;
  }

  protected static function prepareData(&$obj)
  {
    // modifies special fields such as dates and arrays 
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
    
    return $obj;
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
          if (preg_match('/^.*GeneralRisksReports.*$/', $result) === 1 && isset($obj['UII']) && !empty($obj['UII'])) {
            $result = "Deletion failed for UII {$obj['UII']}.";
          } elseif (strstr($result, 'deletion failed') && isset($obj[static::$agencyIdentifier[0]]) && !empty($obj[static::$agencyIdentifier[0]])) {
            $result .= " for ";
            for ($i = 0; $i < count(static::$agencyIdentifier); $i++) {
              if (isset($obj[static::$agencyIdentifier[$i]])) {
                $result .= static::$agencyIdentifier[$i] . " '{$obj[static::$agencyIdentifier[$i]]}'";
              }
            }
          }
          return [
            'error'=>1,
            'message'=> vsprintf("000617-%s: %s", array(GeneralReportRisksReport::$errorType, $result)),
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

  /**
   * Sorts the object for display per current schema
   * 
   * @param type $obj
   */
  public static function sortData(&$obj) {
    static $order;
    
    
    if (strtolower($obj['isActive']) === 'no' && $obj['riskImpact'] == 0 && $obj['riskProbability'] == 0) {
      $obj['riskImpact'] = '';
      $obj['riskProbability'] = '';
    }
    
    $obj = array_filter($obj, function($value) {
      return strlen($value) > 0;
    });
    
    if (!isset($order)) {
      $fields = static::getFieldsAsKeys();
      $order = array_combine($fields, range(1, count($fields)));
    }
    
    uksort($obj, function($a, $b) use($order) {
      return ($order[$a] <=> $order[$b]);
    });
  }
  
  /**
   * Returns a list of keys that can be used in order from the data model
   * 
   * @param bollean $skipOnGet do not return fields with skipOnGet of true
   * @return array
   */
  public static function getFieldsAsKeys($skipOnGet = true) {
    static $fields;
    
    if (!isset($fields[(string)$skipOnGet])) {
      if ($skipOnGet === true) {
        $fields[(string)$skipOnGet] = array_keys(array_filter(GeneralRisksDetails::$fieldList, function($field) {
          return !isset($field['skipOnGet']) || $field['skipOnGet'] === false;
        }));
      } else {
        $fields[(string)$skipOnGet] = array_keys(GeneralRisksDetails::$fieldList);
      }
    }
    
    return $fields[(string)$skipOnGet];
  }
}
