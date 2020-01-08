<?php
namespace Pittsburgh\Controllers\ITDB2;

class GeneralRisksDetailsValidator extends  GeneralRisksBaseValidator
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
