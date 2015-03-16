<?php
/**
 * @file
 * Class WdCommerceOrderWrapper
 */

class WdCommerceOrderWrapper extends WdEntityWrapper {

  /**
   * Wrap a Commerce Order
   *
   * @param stdClass|int $order
   */
  public function __construct($order) {
    if (is_numeric($order)) {
      $order = commerce_order_load($order);
    }
    parent::__construct('commerce_order', $order);
  }

  /**
   * Retrieve the commerce line items.
   *
   * @return array
   */
  public function getLineItems() {
    return $this->get('commerce_line_items');
  }

}
