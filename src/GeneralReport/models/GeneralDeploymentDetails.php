<?php
namespace Pittsburgh\Controllers\ITDB2;

class GeneralDeploymentDetails extends GeneralDeploymentBaseModel
{

  static $deleteIdProcedure  = '';
  static $deleteAllProcedure = '';
  static $updateProcedure    = 'spGeneralDataDeploymentReportsInsert';
  static $addProcedure       = 'spGeneralDataDeploymentReportsInsert';
  static $identifier         = 'deploymentReportsID';
  static $table              = 'generalDataDeploymentReports';
  static $tablePrefix        = 'gr';
  /** @var $fieldList the ordering of $fieldList should not change
   *The stored procedures rely on the ordering
   * See GeneralContractsBaseModel::prepareProcedure
   */ 
  static $fieldList = array(
    'deploymentReportsID' => array(
      'dbName' => 'deploymentReportsID',
      'type' => 'int',
      'skipOnGet' => true,  // show on gets and name it projectActivityID
    ),
    'UII' => array(
      'dbName' => 'CurrentUII',
      'maxLength' => '31',
    ),
    'projectActivityID' => array(
      'dbName' => 'projectActivityID',
      'type' => 'int',
      'maxLength' => '10'
    ),
    'agencyProjectActivityID' => array(
      'dbName' => 'agencyProjectActivityID',
      'type' => 'string',
      'maxLength' => '500'
    ), 
    'reportsReleases' => array(
      'dbName' => 'reportsReleases',
      'type' => 'enum',
      'params' => array(
        'values' => array('Yes', 'No')
      )
    ),
    'multipleReleases' => array(
      'dbName' => 'multipleReleases',
      'type' => 'enum',
      'params' => array(
        'values' => array('Yes', 'No')
      )
    ),
    'releasesToProd1' => array(
      'dbName' => 'releasesToProd1',
      'type' => 'date'
    ),
    'releasesToProd2' => array(
      'dbName' => 'releasesToProd2',
      'type' => 'date'
    ),
    'releasesToProd3' => array(
      'dbName' => 'releasesToProd3',
      'type' => 'date'
    ),
    'releasesToTest1' => array(
      'dbName' => 'releasesToTest1',
      'type' => 'date'
    ),
    'releasesToTest2' => array(
      'dbName' => 'releasesToTest2',
      'type' => 'date'
    ),
    'releasesToTest3' => array(
      'dbName' => 'releasesToTest3',
      'type' => 'date'
    ),
    'rollbackPercentage' => array(
      'dbName' => 'rollbackPercentage',
      'type' => 'percentage'
    ),
    'releaseCadence' => array(
      'dbName' => 'releaseCadence',
      'type' => 'float(11,10)'
    ),
    'userEngagement' => array(
      'dbName' => 'userEngagement',
      'type' => 'intRange',
      'params' => array(
          'min' => 1,
          'max' => 4
          )
    ),
    'releaseReview' => array(
      'dbName' => 'releaseReview',
      'type' => 'string',
      'maxLength' => '60000'
    ),
    'timePeriodID' => array(
        'dbName' => 'timePeriodID',
        'type' => 'int',
        'skipOnGet' => true
    )
  );
}
