<?php
/**
 * @file
 * Class WdUserWrapper
 */

class WdUserWrapper extends WdEntityWrapper {


  /**
   * Wrap a user object.
   *
   * @param stdClass|int $account
   */
  public function __construct($account) {
    if (is_numeric($account)) {
      $account = user_load($account);
    }
    parent::__construct('user', $account);
  }

  /**
   * Create a user.
   *
   * @param array $values
   * @param string $language
   *
   * @return WdUserWrapper
   */
  public static function create($values = array(), $language = LANGUAGE_NONE) {
    $values += array('entity_type' => 'user', 'bundle' => 'user');
    $entity_wrapper = parent::create($values, $language);
    return new WdUserWrapper($entity_wrapper->value());
  }

  /**
   * Retrieve the user UID.
   *
   * @return int
   */
  public function getUid() {
    return $this->get('uid');
  }

  /**
   * Retrieve the user name.
   *
   * @return string
   */
  public function getName() {
    return $this->get('name');
  }

  /**
   * Set user name.
   *
   * @param string $name
   *
   * @return $this
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * Retrieve the user email address.
   *
   * @return string
   */
  public function getMail() {
    return $this->get('mail');
  }

  /**
   * Set user email address.
   *
   * @param string $address
   *
   * @return $this
   */
  public function setMail($address) {
    $this->set('mail', $address);
    return $this;
  }

  /**
   * Retrieve the user created time.
   *
   * @return int
   */
  public function getCreatedTime() {
    return $this->get('created');
  }

  /**
   * Retrieve the user last login time.
   *
   * @return int
   */
  public function getLastLogin() {
    return $this->get('last_login');
  }

  /**
   * Retrieve the user last access time.
   *
   * @return int
   */
  public function getLastAccess() {
    return $this->get('last_access');
  }

  /**
   * Retrieve the user status
   *
   * @return int
   */
  public function getStatus() {
    return $this->get('status');
  }

  /**
   * Set user status.
   *
   * @param int $status
   *
   * @return $this
   */
  public function setStatus($status) {
    $this->set('status', $status);
    return $this;
  }

  /**
   * Retrieve the user picture.
   *
   * @return stdClass
   */
  public function getPicture() {
    $picture = $this->entity->value()->picture;
    if (!is_object($picture)) {
      $picture = file_load($picture);
    }
    return $picture;
  }

  // @todo: This doesn't work.
  /*public function setPicture($image) {
    $image = (array) $image;
    $this->entity->value()->picture = $image['fid'];
    return $this;
  }*/

  /**
   * Retrieve the user signature.
   *
   * @return array
   */
  public function getSignature() {
    return $this->entity->value()->signature;
  }

  /**
   * Set user signature.
   *
   * @param array $signature
   * @return $this
   */
  public function setSignature($signature) {
    $this->entity->value()->signature = $signature;
    return $this;
  }

  /**
   * Retrieve the user theme.
   *
   * @return string
   */
  public function getTheme() {
    return $this->get('theme');
  }

  /**
   * Set user theme.
   *
   * @param string $theme
   *
   * @return $this
   */
  public function setTheme($theme) {
    $this->set('theme', $theme);
    return $this;
  }

  /**
   * Retrieve the user initial email address.
   *
   * @return string
   */
  public function getInitialEmail() {
    return $this->entity->value()->init;
  }

  /**
   * Set user initial email address.
   *
   * @param string $value
   *
   * @return $this
   */
  public function setInitialEmail($value) {
    $this->entity->value()->init = $value;
    return $this;
  }

}