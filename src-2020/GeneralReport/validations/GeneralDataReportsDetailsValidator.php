<?php
namespace Pittsburgh\Controllers\ITDB2;

class GeneralDataReportsDetailsValidator extends  GeneralContractBaseValidator
{
  static $UII;
  static $ignore = array('operation', 'comment');

  /**
   * Implement prototype to validate the structure of a section.
   *
   * @param array $obj - section data
   */
  protected function performStructuralValidations(&$obj)
  {
  }

  /**
   * Implement prototype to perform validations for both 'add' and 'update' operations.
   *
   * @param array $obj - section data
   */
  protected function performSharedValidations(&$obj)
  {
  }

  /**
   * Implement prototype to perform validations for 'add' operations.
   *
   * @param array $obj - section data
   */
  protected function performAddValidations(&$obj)
  {
    
    /* TODO: develop.fy21 we should allow multiple entries into generalDataReports  */
    /*
    echo _err(GeneralReport::$reportPrimaryID, 1);
    if (isset(GeneralReport::$reportPrimaryID) && !empty(GeneralReport::$reportPrimaryID)) {
      $this->errorObj[] = "000610-CON: A Contracts Report with UII $obj[UII] already exists. You may not use an add operation for this UII.";
    }*/
    
    if(empty($obj['reportsContracts'])) {
        $this->errorObj[] = "000871-CON: reportsContracts is a required field when adding a contract.";
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
}
