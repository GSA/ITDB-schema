<?php
namespace Pittsburgh\Controllers\ITDB2;

class GeneralDataReportsDetails extends GeneralContractsBaseModel
{

  static $deleteIdProcedure  = 'uspGeneralDataReportsDeleteById';
  static $deleteAllProcedure = 'uspGeneralDataReportsDeleteByUii';
  static $updateProcedure    = 'uspGeneralDataReportsUpdate';
  static $addProcedure       = 'uspGeneralDataReportsInsert';
  static $identifier         = 'contractID';
  static $table              = 'generalDataReports';
  static $tablePrefix        = 'gr';
  /** @var $fieldList the ordering of $fieldList should not change
   *The stored procedures rely on the ordering
   * See GeneralContractsBaseModel::prepareProcedure
   */ 
  static $fieldList = array(
    'contractID' => array(
      'dbName' => 'id',
      'type' => 'int',
      'skipOnGet' => false,  // per Ryan, show on gets and name it contractID, make sure the I and the D are uppercase
    ),
    'UII' => array(
      'dbName' => 'currentUII',
      'maxLength' => '31',
    ),
    'agencyContractID' => array(
      'dbName' => 'agencyContractID',
      'type' => 'string',
      'maxLength' => '250'
    ),
    'PIID' => array(
      'dbName' => 'PIID',
      'type' => 'string',
      'maxLength' => '500'
    ),
    'referencePIID' => array(
      'dbName' => 'referencePIID',
      'type' => 'string',
      'maxLength' => '500'
    ),
    'reportsContracts' => array(
      'dbName' => 'reportsContracts',
      'type' => 'enum',
      'params' => array(
        'values' => array('Yes', 'No')
      )
    ),
    'timePeriodID' => array(
      'skipOnGet' => true,
      'type' => 'int',
    ),
    'updatedBy' => array(
      'skipOnGet' => true,
      'type' => 'date',
    ),
  );
}
