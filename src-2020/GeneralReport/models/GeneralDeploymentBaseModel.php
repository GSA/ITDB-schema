<?php

namespace Pittsburgh\Controllers\ITDB2;

require_once("Pittsburgh/Controllers/ITDB2/GeneralReport.php");
require_once("Pittsburgh/Lib/Utility/UtilityFunctions.php");

use Pittsburgh\lib\Utility as Utility;

class GeneralDeploymentBaseModel
{

  static $fieldList       = array();
  static $ignore          = array('operation', 'comment');
  static $identifier      = 'id';
  static $table           = 'generalDataDeploymentReports';
  static $agencyIdentifier = array('projectActivityID');
  static $validator;
  static $primaryID;

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
        $procedure = GeneralDeploymentDetails::$addProcedure;
        $results   = self::performAddUpdateOperation($obj, $details, $pdo, $procedure, true);
        break;
      case 'update':
        $procedure = GeneralDeploymentDetails::$updateProcedure;
        $results   = self::performAddUpdateOperation($obj, $details, $pdo, $procedure, false);
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
    return isset(GeneralReportDeploymentReport::$models[$section]) ? GeneralReportDeploymentReport::$models[$section] : false;
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
    $mult = ['Activities'];
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
                  } else { // if we are NOT in a repeating part of XML, or ONE contract is in the ADD
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
   * This is useful when updating a contracts
   *
   * @param array $obj - section data
   *
   */
  public static function fillUnsetValues($obj, $details, &$params) {
    $id = static::getIdFromRequest($details['UII'], $obj['projectActivityID'] ?? null, $obj['agencyProjectActivityID'] ?? null);
    if ($id !== null) {
      $values = static::get(BaseReport::getPDO(), $id);
      foreach ($values as $key => $val) {
        if (!in_array($key, array('releasesToProd', 'releasesToTest')) && strlen($val) > 0 && !isset($obj[$key]) && !isset($details[$key])) {
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
  protected static function fillObjectWithDBValues(&$obj, $pdo, $id) {
    $skip = array('operation', 'metricActuals', 'activities');
    // ITD-1888 These values should not be returned as part of the response
    $remove = array('id');
    $id = $id;
    $dbVals = static::get($pdo, $id);
    
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
    // see  GeneralDeploymentDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralDeploymentDetails::$fieldList[GeneralDeploymentDetails::$identifier]['dbName'];
    
    $args[] = (int)$id; 
    $where['id'] = sprintf(' %s = ?', $identifier);

    list($sth, $args) = static::generalReportAgencySql(GeneralDeploymentDetails::$tablePrefix, static::getFields(true), $where, $args);
    $sth->execute($args);
    $result = array();
    if ($sth->rowCount()) {
      $result = $sth->fetch(\PDO::FETCH_ASSOC);
      foreach ($result as $field => $val) {
        if (isset(GeneralDeploymentDetails::$fieldList[$field]) && isset(GeneralDeploymentDetails::$fieldList[$field]['type']) && GeneralDeploymentDetails::$fieldList[$field]['type'] == 'array') {
          $result[$field] = explode('|', $val);
        }
      }
      static::processReleaseDates($result, 'releasesToProd');
      static::processReleaseDates($result, 'releasesToTest');
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
    $tablePrefix = GeneralDeploymentDetails::$tablePrefix;
    $where['id'] = "$tablePrefix.currentUII = ?";
    $args[] = $uii;
    list($sth, $args) = static::generalReportAgencySql($tablePrefix, static::getFields(true), $where, $args);
    $sth->execute($args);
    $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
   
    return $result;
  }
  
  /**
   * Will return rows found from the generalDataDeploymentReports by searching with
   * UII, investmentProjectActivity.investmentProjectActivityId and investmentProjectActivity.agencyActivityId
   * 
   * @param string $uii CurrentUII
   * @param int $investmentProjectActivityId investmentProjectActivity.investmentProjectActivityId
   * @param string $agencyActivityId investmentProjectActivity.agencyActivityId
   * @return bool|int false or count of rows found
   */
  public static function investmentActivityExists($uii, $investmentProjectActivityId = null, $agencyActivityId = null, $count = true) {
    $tablePrefix = 'bc';
    $used = 0;
    
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "$tablePrefix.CurrentUII = ?";
      $args[] = $uii;
    }
    
    // Add investmentProjectActivityId OR agencyActivityId to the where clause
    if (!empty($investmentProjectActivityId) && !empty($agencyActivityId)) {
      $where['investmentProjectActivityId'] = "(ipa.investmentProjectActivityId = ? AND ipa.agencyActivityId = ?)";
      $args[] = $investmentProjectActivityId;
      $args[] = $agencyActivityId;
      
      static::$agencyIdentifier[1] = 'agencyProjectActivityId';
    }
    else if (!empty($investmentProjectActivityId)) {
      // Add investmentProjectActivityId to the where clause
      $where['investmentProjectActivityId'] = "ipa.investmentProjectActivityId = ?";
      $args[] = $investmentProjectActivityId;
    }
    
    // Add agencyActivityId) to the where clause
    if (!empty($agencyActivityId)) {
      $where['agencyActivityId'] = "ipa.agencyActivityId = ?";
      $args[] = $agencyActivityId;
      
      static::$agencyIdentifier[1] = 'agencyProjectActivityId';
    }
    
    $rowId = false;
    // required to search by UII and agencyContractID or contractID
    if (isset($where['currentUII']) && count($args) >= 2) {
      if ($count === true) {
        $fields = 'COUNT(*)';
      } else if ($count === 1) {
        $fields = 'ipa.investmentProjectActivityId';
        $used = 1;
      } else if ($count === 2) {
        $fields = 'ipa.agencyActivityId';
        $used = 2;
      }
      list($sth, $args) = static::generalReportBusinessCaseSIRSql($fields, $where, $args);
      $sth->execute($args);
      if ($sth->rowCount() > 0) {
        if ($count === true) {
          $rows = $sth->fetchAll(\PDO::FETCH_NUM);
          if (is_array($rows)) {
            $rowId = array_sum(array_column($rows, 0));
            if (!$rowId) {
              $rowId = false;
            }
          }
        } else {
          $rowId = $sth->fetchColumn(0);
        }
      }
    }

    $return_val = [$rowId, $used];
    
    return $return_val;
  }
  
  /**
   * Returns a count of UIIs found matching, should be 1 or false
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
      list($sth, $args) = static::generalReportUIISql("COUNT(*) as count", $where, $args);
      $sth->execute($args);
      $rowIds = $sth->rowCount() > 0 ? $sth->fetchColumn(0) : false;
    }

    return $rowIds;
  }

  /**
   * Will return generalDataDeploymentReports.deploymentReportsID found from the  
   * generalDataDeploymentReports by searching with UII, 
   * investmentProjectActivity.investmentProjectActivityId and
   * investmentProjectActivity.agencyActivityId
   * 
   * @param string $uii CurrentUII
   * @param int $investmentProjectActivityId investmentProjectActivity.investmentProjectActivityId
   * @param string $agencyActivityId investmentProjectActivity.agencyActivityId
   * @return bool|int generalDataDeploymentReports.deploymentReportsID or count of rows found
   */
  public static function getIdFromRequest($uii, $investmentProjectActivityId = null, $agencyActivityId = null) {
    $tablePrefix = GeneralDeploymentDetails::$tablePrefix;
    
    // see  GeneralDeploymentDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralDeploymentDetails::$fieldList[GeneralDeploymentDetails::$identifier]['dbName'];
    
    // Add currentUII to the where clause
    if (!empty($uii)) {
      $where['currentUII'] = "$tablePrefix.currentUII = ?";
      $args[] = $uii;
    }
    
    // Add investmentProjectActivityId OR agencyActivityId to the where clause
    if (!empty($investmentProjectActivityId) && !empty($agencyActivityId)) {
      $where['investmentProjectActivityId'] = "(ipa.investmentProjectActivityId = ? AND ipa.agencyActivityId = ?)";
      $args[] = $investmentProjectActivityId;
      $args[] = $agencyActivityId;
      
      static::$agencyIdentifier[1] = 'agencyProjectActivityId';
    } else if (!empty($investmentProjectActivityId)) {
      // Add investmentProjectActivityId to the where clause
      $where['investmentProjectActivityId'] = "ipa.investmentProjectActivityId = ?";
      $args[] = $investmentProjectActivityId;
    } else if (!empty($agencyActivityId)) {
      // Add agencyActivityId) to the where clause
      $where['agencyActivityId'] = "ipa.agencyActivityId = ?";
      $args[] = $agencyActivityId;
      
      static::$agencyIdentifier[1] = 'agencyProjectActivityId';
    }
    
    $rowId = false;
    // required to search by UII and agencyContractID or contractID
    if (isset($where['currentUII']) && count($args) >= 2) {
      list($sth, $args) = static::generalReportAgencySql($tablePrefix, "$tablePrefix.$identifier", $where, $args);
      $sth->execute($args);
      $rowId = $sth->rowCount() > 0 ? $sth->fetchColumn(0) : false;
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
    $tablePrefix = GeneralDeploymentDetails::$tablePrefix;
    
    // see  GeneralDeploymentDetails::$fieldList and add dbName that can be used for selecting the id
    $identifier = GeneralDeploymentDetails::$fieldList[GeneralDeploymentDetails::$identifier]['dbName'];
    
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
   * Returns report PDOStatement containing the model table, agency check with a join to
   * investmentProjectActivity for business requirement
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
    
    $investmentProjectActivityTable = sprintf('investmentProjectActivity as ipa ON %s.projectActivityID = ipa.investmentProjectActivityId', $tablePrefix);
    
    $sql = "SELECT %s FROM %s as %s INNER JOIN %s INNER JOIN %s WHERE %s";
    $params = array($fields, static::$table, $tablePrefix, $investmentProjectActivityTable, $itPortfolioTable, implode(' AND ', $where));

    return static::finalizePDO($sql, $limit, null, $params, $args);
  }
  
  /**
   * Returns report PDOStatement containing itPortfolio table, the agency check for model class with a join to
   * investmentProjectActivity for business requirement
   * 
   * @param string $fields fields with table prefix
   * @param array $where an array of containing each part of the where clause
   * @param array $args an array containing the arguments used in a PDOStatement::execute call
   * @param int $limit an integer of the limit
   * @return array
   */
  private static function generalReportUIISql($fields, $where, $args, $limit = false) {
    $tablePrefix = 'itp';
    $table = 'itPortfolio';
    
    list($itPortfolioTable, $where, $args) = Utility\UtilityFunctions::getInvestmentJoinT1T5($tablePrefix, $where, $args);
    
    $sql = "SELECT %s FROM %s as %s WHERE %s";
    $params = array($fields, $table, $tablePrefix, implode(' AND ', $where));
    
    return static::finalizePDO($sql, $limit, null, $params, $args);
  }
  
  /**
   * Returns report PDOStatement containing businessCase table, the agency check for model class with a join to
   * investmentProjectActivity for business requirement
   * 
   * @param string $fields fields with table prefix
   * @param array $where an array of containing each part of the where clause
   * @param array $args an array containing the arguments used in a PDOStatement::execute call
   * @param int $limit an integer of the limit
   * @return array
   */
  private static function generalReportBusinessCaseSIRSql($fields, $where, $args, $limit = false) {
    $tablePrefix = 'bc';
    $table = 'businessCase';
    
    list($itPortfolioTable, $where, $args) = Utility\UtilityFunctions::getInvestmentJoinT1T5($tablePrefix, $where, $args, false);
    
    $investmentProjectActivityTable = 'investmentProjectActivity as ipa USING(businessCaseId)';
    
    $sql = "SELECT %s FROM %s as %s INNER JOIN %s INNER JOIN %s WHERE %s UNION %s";
    $params = array($fields, $table, $tablePrefix, $investmentProjectActivityTable, $itPortfolioTable, implode(' AND ', $where));
    if (is_int($limit)) {
      $sql .= " LIMIT %s";
      $params[] = $limit;
    }
    $where['currentUII'] = 'sir.UII = ?';
    $params[] = static::generalReportSIRSql($fields, $where, $args, $limit);

    return static::finalizePDO($sql, null, null, $params, array_merge($args, $args));
  }
  
  /**
   * Returns just SQL for use in the generalReportBusinessCaseSIRSql method
   * It is joined using the UtilityFunctions::getSIRITPJoin
   * 
   * @param string $fields fields with table prefix
   * @param array $where an array of containing each part of the where clause
   * @param array $args an array containing the arguments used in a PDOStatement::execute call
   * @param int $limit an integer of the limit
   * @return string SQL for UII and the standardInvestmentReport table with SIRITPJoin
   */
  private static function generalReportSIRSql($fields, $where, $args, $limit = false) {
    $tablePrefix = 'sir';
    $table = 'standardInvestmentReport';
    
    list($itPortfolioTable, $where, $args) = Utility\UtilityFunctions::getSIRITPJoin($tablePrefix, $where, $args);
    
    $investmentProjectActivityTable = 'investmentProjectActivity as ipa USING(StandardInvestmentReportId)';
    
    $sql = "SELECT %s FROM %s as %s INNER JOIN %s INNER JOIN %s WHERE %s";
    $params = array($fields, $table, $tablePrefix, $investmentProjectActivityTable, $itPortfolioTable, implode(' AND ', $where));
    if (is_int($limit)) {
      $sql .= " LIMIT %s";
      $params[] = $limit;
    }
    $sql = vsprintf($sql, $params);
    $pdo = Utility\UtilityFunctions::getPDO();
    
    return $sql;
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

    foreach (GeneralDeploymentDetails::$fieldList as $field => $val) {
      if (empty($val['skipOnGet'])) {
        if (!$first) {
          $query .= ",";
        }
        $first = false;
        if ($tblPrefix === true) {
          $query .= GeneralDeploymentDetails::$tablePrefix . ".";
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
    $models = array_flip(GeneralReportDeploymentReport::$models);

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
        if (array_key_exists($obj['UII'], GeneralReportDeploymentReport::$identifiers['xml'][$type])) {
          return "Detected duplicate UII: {$obj['UII']}";
        } else {
          GeneralReportDeploymentReport::$identifiers['xml'][$type][$obj['UII']] = 1;
        }
      }

      // Check for duplicate record IDs in the XML
      if ($type != 'details' && isset($obj[static::$identifier]) && $obj[static::$identifier]) {
        if (in_array($obj[static::$identifier], GeneralReportDeploymentReport::$identifiers['xml'][$type])) {
          return "Detected duplicate " . static::$identifier . ": {$obj[static::$identifier]}";
        } else {
          GeneralReportDeploymentReport::$identifiers['xml'][$type][] = $obj[static::$identifier];
        }
      }

      // Check for duplicate agency IDs in the XML
      if ($type != 'details' && isset($obj[static::$agencyIdentifier[0]]) && $obj[static::$agencyIdentifier[0]]) {
        // Agency IDs are prefixed with SIR ID in the XML identifiers digest to create a unique index
        $index = GeneralReportDeploymentReport::$reportUII . '|' . $obj[static::$agencyIdentifier[0]];
        if (in_array($index, GeneralReportDeploymentReport::$identifiers['xml'][$type])) {
          return "Detected duplicate " . static::$agencyIdentifier[0] . ": {$obj[static::$agencyIdentifier[0]]}";
        } else {
          GeneralReportDeploymentReport::$identifiers['xml'][$type][] = $index;
        }
      }
    }

    // If the submission specifies a record ID, make sure it really belongs to this investment
    if ($type != 'details' && isset($obj[static::$identifier]) && $obj[static::$identifier]) {
      // Record IDs are prefixed with SIR ID in the DB identifiers digest to verify the SIR association
      $id = GeneralReportDeploymentReport::$reportUII . '|' . $obj[static::$identifier];
      if (!array_key_exists($id, GeneralReportDeploymentReport::$identifiers['db'][$type])) {
        return "A record with " . static::$identifier. " {$obj[static::$identifier]} was not found for this investment.";
      }
    }

    // Otherwise if an update submission specifies an agency ID, set the related record ID
    elseif ($type != 'details' && isset($obj[static::$agencyIdentifier[0]]) && $obj[static::$agencyIdentifier[0]]) {
      // Agency IDs are prefixed with SIR ID in the DB identifiers digest to verify the SIR association
      $aid = GeneralReportDeploymentReport::$reportUII . '|' . $obj[static::$agencyIdentifier[0]];
      if (in_array($aid, GeneralReportDeploymentReport::$identifiers['db'][$type])) {
        $id = array_search($aid, GeneralReportDeploymentReport::$identifiers['db'][$type]);
        // Strip the SIR ID prefix before setting the record ID
        $obj[static::$identifier] = str_replace(GeneralReportDeploymentReport::$reportUII . '|', '', $id);
      }
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
    
    $deploymentReportsID = GeneralDeploymentBaseModel::getIdFromRequest($details['UII'], 
            $obj['projectActivityID'] ?? null, $obj['agencyProjectActivityID'] ?? null);
    
    $params = static::prepareProcedure($obj, $details);    
    
    if (isset($params['ErrorsAndWarnings']['warnings'])) {
      unset($params['ErrorsAndWarnings']);
    }
    
    if ($deploymentReportsID !== false) {
      $params = array('deploymentReportsID' => $deploymentReportsID) + $params;
      $add = false;
    } else {
      $params = array('deploymentReportsID' => null) + $params;
      $add = true;
    }
    
    switch ($add) {
      // This will updated a contract
      case false:
        $params['createdBy'] = null;
      break;
      // this will add a new contract
      case true:
        if (!isset($obj['projectActivityID'])) {
          $ca = 1;
          $ai = 'projectActivityID';
        } else if (!isset($obj['agencyProjectActivityID'])) {
          $ca = 2;
          $ai = 'agencyProjectActivityID';
        } else {
          $ca = true;
        }
        
        $am = GeneralDeploymentBaseModel::investmentActivityExists($details['UII'], 
                $obj['projectActivityID'] ?? null, $obj['agencyProjectActivityID'] ?? null, $ca);
        if ($am === false) {
          $errorResult['error'] = 1;
          $errorResult['message'] = sprintf("000630-%s: investment activity not found when trying to add using: %s of '%s'", 
                  GeneralReportDeploymentReport::$errorType, static::$agencyIdentifier[0], 
                  $obj['projectActivityID'] ?? null);
          if (isset(static::$agencyIdentifier[1])) {
            $errorResult['message'] .= " and %s of '%s'";
            $errorResult['message'] = sprintf($errorResult['message'], static::$agencyIdentifier[1], $obj['agencyProjectActivityID'] ?? null);
          }
        }
        if (isset($ai)) {
          $params[$ai] = $am;
        }
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

      // TODO: develop.fy21 new function for checking database results and returning them
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
    
    // The $params variable relies on the order of GeneralDeploymentDetails::$fieldList ordering.
    // It should not be changed or you will have to change the stored procedure ordering.
    $params = array_fill_keys(array_keys(GeneralDeploymentDetails::$fieldList), '');
    static::fillUnsetValues($obj, $details, $params);
    $params['UII']              = $details['UII'];
    
    $releasesToDate = function($subtype, $obj, &$params) {
      if (!is_array($obj[$subtype]['date'])) {
        $dateObj = array($obj[$subtype]['date']);
      } else {
        $dateObj = $obj[$subtype]['date'];
      }
      
      for ($i = 1; $i <= count($dateObj) ; $i++) {
        if (isset($dateObj[$i-1])) {
          $ofmt = 'Y-m-d';
          if (strpos($dateObj[$i-1], '/') !== false) {
            $fmt = 'm/d/Y';
            $jlim = '/';
          } else if (strpos($dateObj[$i-1], '-') !== false) {
            $fmt = 'Y-m-d';
            $jlim = '-';
          }
          static::releaseDateFormat($fmt, $ofmt, $jlim, $params[$subtype.$i], $dateObj[$i-1]);
        }
      }
    };
    
    if (isset($obj['releasesToProd'])) {
      $releasesToDate('releasesToProd', $obj, $params);
    }
    if (isset($obj['releasesToTest'])) {
     $releasesToDate('releasesToTest', $obj, $params);
    }
    
    foreach ($fields as $field => $val) {
      // these should not be sent to any stored procedures any more at least!
      if (in_array($field, array('operation', 'releasesToProd', 'releasesToTest'))) {
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
        if($field == 'rollbackPercentage' && $obj[$field] == '0'){
            $obj[$field] = '0';
        }
        else{
            $obj[$field] = NULL;
        }
      }
      
      // better to send entire object with keys!!!
      $params[$field] = $obj[$field];
    }
    
    $params['timePeriodID'] = Utility\UtilityFunctions::getLatestSubmittedTimePeriodForAgency(Utility\UtilityFunctions::getAgency());
    
    $user                   = Utility\UtilityFunctions::getUser();
    $params['updatedBy']    = $user->email;
    $params['createdBy']    = $user->email;
    
    return $params;
  }

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
          if (preg_match('/^.*GeneralDataReports.*$/', $result) === 1 && isset($obj['UII']) && !empty($obj['UII'])) {
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
            'message'=> vsprintf("000617-%s: %s", array(GeneralReportDeploymentReport::$errorType, $result)),
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
   * Formats date and adds it to the output object
   * 
   * @param array $obj $obj for output
   * @param string $key one of 'releasesToProd' or 'releasesToTest'
   * @param null|array $dbVals row from the database
   */
  static function processReleaseDates(&$obj, $key, $dbVals = null) {
    if (in_array($key, array('releasesToProd', 'releasesToTest'))) {
      $fmt = 'Y-m-d';
      $ofmt = 'm/d/Y';
      $jlim = '-';
      $obj[$key] = array('date' => array());
      for ($i = 1; $i <= 3; $i++) {
        if ($dbVals !== null && isset($dbVals[$key . $i]) && $dbVals[$key . $i] !== '0000-00-00') {
          static::releaseDateFormat($fmt, $ofmt, $jlim, $obj[$key]['date'][], $dbVals[$key . $i]);
        } else if (array_key_exists($key . $i, $obj)) {
          if ((int)$obj[$key.$i] > 0) {
            static::releaseDateFormat($fmt, $ofmt, $jlim, $obj[$key]['date'][], $obj[$key . $i]);
          }
          unset($obj[$key . $i]);
        }
      }
    }
  }
  
  /**
   * Takes a input format, an output format and a placeholder value and a date
   * The date gets checked and output in the output format.
   * 
   * @param string $ifmt input format that can be used by the date function
   * @param strin $ofmt output format that can be used by the date function
   * @param string $jlim the delimiter from the input format
   * @param string $objDate a reference to the placeholder value
   * @param string $dateVal the date value
   */
  private static function releaseDateFormat($ifmt, $ofmt, $jlim, &$objDate, $dateVal) {
    $date = array_map('intval', explode($jlim, $dateVal));
    $i = array_flip(explode($jlim, $ifmt));
    if (count($date) === 3 && checkdate($date[$i['m']], $date[$i['d']], $date[$i['Y']])) {
      $time = mktime(0, 0, 0, $date[$i['m']], $date[$i['d']], $date[$i['Y']]);
      $objDate = date($ofmt, $time);
    } else {
      $objDate = '';
    }
  }

  /**
   * Sorts the object for display per current schema
   * 
   * @param type $obj
   */
  public static function sortData(&$obj) {
       uksort($obj, function($a, $b) {
        $order = array(
            'UII' => 0,
            'projectActivityID' => 1,
            'agencyProjectActivityID' => 2,
            'reportsReleases' => 3,
            'multipleReleases' => 4,
            'releasesToProd' => 5,
            'releasesToTest' => 6,
            'rollbackPercentage' => 7,
            'releaseCadence' => 8,
            'userEngagement' => 9,
            'releaseReview' => 10
        );
        return ($order[$a] <=> $order[$b]);
      });
  }
}
