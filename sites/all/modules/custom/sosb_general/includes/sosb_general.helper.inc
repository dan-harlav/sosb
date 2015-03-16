<?php

/**
 * Checks if a string is a valid variable name.
 *
 * @param string $variable_name
 *   The string to be checked.
 *
 * @return bool
 *   TRUE if the string is a valid variable name.
 */
function sosb_general_is_valid_variable_name($variable_name) {
  if (preg_match('/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $variable_name)) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Computes the carthesian product for a set of arrays.
 */
function sosb_general_generate_carthesian_product($arrays = array()) {
  $result = array();
  foreach ($arrays['base'] as $type1 => $value1) {
    foreach ($arrays['rank'] as $type2 => $value2) {
      if ($value2 == 'FA') {
        $result[] = array(
          'base' => $value1,
          'rank' => $value2,
          'rating' => '',
        );
      }
      else {
        foreach ($arrays['rating'] as $type3 => $value3) {
          $result[] = array(
            'base' => $value1,
            'rank' => $value2,
            'rating' => $value3,
          );
        }
      }
    }
  }

  return $result;
}
