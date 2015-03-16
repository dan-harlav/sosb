<?php
/**
 * @file
 * Class WdCommerceLineItem
 */

class WdCommerceLineItemWrapper extends WdEntityWrapper {

  /**
   * Wrap a Commerce Line Item.
   *
   * @param stdClass|int $line_item
   */
  public function __construct($line_item) {
    if (is_numeric($line_item)) {
      $line_item = commerce_line_item_load($line_item);
    }
    parent::__construct('commerce_line_item', $line_item);
  }

  /**
   * Retrieves the commerce product on this line item.
   *
   * @return WdCommerceProductWrapper
   */
  public function getProduct() {
    return new WdCommerceProductWrapper($this->get('commerce_product'));
  }

  /**
   * Retrieves the commerce total.
   *
   * @return array
   */
  public function getTotal() {
    return $this->get('commerce_total');
  }

  /**
   * Retrieves the commerce unit price
   *
   * @return array
   */
  public function getUnitPrice() {
    return $this->get('commerce_unit_price');
  }

  /**
   * Retrieves the quantity.
   *
   * @return int
   */
  public function getQuantity() {
    return $this->get('quantity');
  }

}