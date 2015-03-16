<?php
/**
 * @file
 * Class WdEntityWrapper
 */

class WdEntityWrapper {

  /**
   * Wrapped entity
   *
   * @var EntityMetadataWrapper
   */
  protected $entity;

  /**
   * Wrapped entity type
   *
   * @var string
   */
  protected $entity_type;

  /**
   * Wrap an entity.
   *
   * @param $entity_type
   *   Entity type
   *
   * @param stdClass|int $entity
   *   Entity to wrap. Will load entity if ID is passed.
   */
  public function __construct($entity_type, $entity) {
    if (is_numeric($entity)) {
      $entity = entity_load_single($entity_type, $entity);
    }
    $this->entity = entity_metadata_wrapper($entity_type, $entity);
    $this->entity_type = $entity_type;
  }

  /**
   * Create a new entity. Does not save entity before returning.
   *
   * @param $entity_type
   * @param $bundle
   * @param array $values
   * @param string $language
   *
   * @return WdEntityWrapper
   */
  public static function create($values = array(), $language = LANGUAGE_NONE) {
    $values += array('bundle' => $values['bundle'], 'language' => $language);
    $entity = entity_create($values['entity_type'], $values);
    return new WdEntityWrapper($values['entity_type'], $entity);
  }

  /**
   * Retrieve wrapped entity bundle
   *
   * @return string
   */
  public function getBundle() {
    return $this->entity->getBundle();
  }

  /**
   * Retrieve an entity value by name.
   *
   * @param $name
   *
   * @return mixed
   */
  public function get($name) {
    return $this->entity->{$name}->value();
  }

  /**
   * Set an entity value by name.
   *
   * @param $name
   * @param $value
   *
   * @return $this
   */
  public function set($name, $value) {
    $this->entity->{$name} = $value;
    return $this;
  }

  /**
   * Save the wrapped entity.
   *
   * @return $this
   */
  public function save() {
    $this->entity->save();
    return $this;
  }

  /**
   * Retrieve the EntityMetadataWrapper object.
   *
   * @return EntityMetadataWrapper
   */
  public function getEntityMetadataWrapper() {
    return $this->entity;
  }

  /**
   * Retrieve the wrapped entity.
   *
   * @return mixed
   */
  public function value() {
    return $this->entity->value();
  }

  /**
   * Retrieve the language.
   *
   * @return string
   */
  public function getLanguage() {
    return $this->entity->language->value();
  }

  /**
   * Retrieve the entity ID
   *
   * @return int
   */
  public function getId() {
    return $this->getIdentifier();
  }

  /**
   * Retrieve the entity ID
   *
   * @return int
   */
  public function getIdentifier() {
    return $this->entity->getIdentifier();
  }

  /**
   * Utility function to save filtered text fields and clear safe_value from them.
   *
   * @param $field_name
   * @param $value
   * @return $this
   */
  protected function setFilteredTextField($field_name, $value) {
    // We need to clear safe_value in order to have it regenerated
    // within the same page load.
    $field_value = $this->entity->{$field_name}->value();
    $field_value['value'] = $value;
    if (!isset($field_value['format'])) {
      $field_value['format'] = filter_default_format();
    }
    unset($field_value['safe_value']);
    $this->entity->{$field_name} = $field_value;
    return $this;
  }

  /**
   * @param $entity_type
   * @return string
   */
  protected function getBaseClassName($entity_type) {
    $camelized_type = str_replace(' ', '', ucwords(str_replace('_', ' ', $entity_type)));
    $class = 'Wd' . $camelized_type . 'Wrapper';
    if (!class_exists($class)) {
      $class = 'WdEntityWrapper';
    }
    return $class;
  }

}
