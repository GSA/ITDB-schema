<?php

namespace Pittsburgh\Controllers\ITDB2\GeneralReport;

class GRUtils {
  
  /**
   * Sets the user risks
   * 
   * @param array $ura array of user set risks 1 - 13
   * @return string of needed risks 
   */
  static function needsRisks($ura) {
    $riskAreas = str_repeat(0, 13);
    
    for ($i = 0; $i < 13; $i++) {
      if (array_search($i + 1, $ura) !== false) {
        $riskAreas[$i] = 1;
      }
    }
    
    $nura = array();
    for ($i = 0; $i < 13; $i++) {
      if ($riskAreas[$i] === '0') {
        $nura[] = ($i + 1);
      }
    }
    
    return $nura;
  }
}
