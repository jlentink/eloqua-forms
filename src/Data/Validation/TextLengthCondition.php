<?php

namespace EloquaForms\Data\Validation;

class TextLengthCondition extends Validation {

  /**
   * @param string $value
   * @return bool
   */
  protected function _validate($value) {
    $value = strlen($value);
    if ($value >= $this->_completeObject->condition->minimum && $value <= $this->_completeObject->condition->maximum) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get the minimum length.
   *
   * @return int
   *   The minimum length.
   */
  public function getMinimum() {
    return $this->_completeObject->condition->minimum;
  }

  /**
   * Get the maximum length.
   *
   * @return int
   *   The maximum length.
   */
  public function getMaximum() {
    return $this->_completeObject->condition->maximum;
  }

}
