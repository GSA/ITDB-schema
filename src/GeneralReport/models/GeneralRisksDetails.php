<?php

namespace Pittsburgh\Controllers\ITDB2;

class GeneralRisksDetails extends GeneralRisksBaseModel {

  static $deleteIdProcedure = '';
  static $deleteAllProcedure = 'uspGeneralDataRisksReportDelete';
  static $updateProcedure = 'uspGeneralDataRisksReportInsert';
  static $addProcedure = 'uspGeneralDataRisksReportInsert';
  static $identifier = 'risksReportID';
  static $table = 'generalDataRisksReport';
  static $tablePrefix = 'gr';

  /** @var $fieldList the ordering of $fieldList should not change
   * The stored procedures rely on the ordering
   */
  static $fieldList = array(
      'risksReportID' => array(
      'dbName' => 'risksReportID',
      'type' => 'int',
      'skipOnGet' => true,  
    ),
    'UII' => array(
      'dbName' => 'CurrentUII',
      'maxLength' => '31',
    ),
    'riskArea' => array(
      'dbName' => 'riskArea',
      'type' => 'intRange',
      'params' => array(
          'min' => 1,
          'max' => 13
          )
    ),
    'isActive' => array(
      'dbName' => 'isActive',
      'type' => 'enum',
      'params' => array(
        'values' => array('Yes', 'No')
      )
    ), 
    'riskProbability' => array(
      'dbName' => 'riskProbability',
      'type' => 'intRange',
      'params' => array(
          'min' => 1,
          'max' => 5
          )
    ),
    'riskImpact' => array(
      'dbName' => 'riskImpact',
      'type' => 'intRange',
      'params' => array(
          'min' => 1,
          'max' => 5
          )
    ),
    'mitigationPlan' => array(
      'dbName' => 'mitigationPlan',
      'type' => 'string',
      'maxLength' => '60000'
    ),
    'updatedDate' => array(
        'dbName' => 'updatedDate',
        'type' => 'date',
        'skipOnGet' => true
    ),
    'updatedBy' => array(
        'dbName' => 'createdBy',
        'type' => 'string',
        'maxLength' => 255,
        'skipOnGet' => true
    ),
    'timePeriodID' => array(
        'dbName' => 'timePeriodID',
        'type' => 'int',
        'skipOnGet' => true
    )
  );
  
}
