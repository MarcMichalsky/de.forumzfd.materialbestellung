<?php
/**
 * Class DAO Material
 *
 * @author Erik Hommel <hommel@ee-atwork.nl>
 * @date 24 July 2017
 * @license AGPL-3.0
 */

class CRM_Materialbestellung_DAO_Material extends CRM_Core_DAO {

  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = NULL;
  static $_export = NULL;
  static $_fieldKeys = NULL;

  /**
   * empty definition for virtual function
   */
  public static function getTableName() {
    return 'forumzfd_material';
  }
  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  public static function &fields() {
    if (!(self::$_fields)) {
      self::$_fields = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => TRUE,
        ) ,
        'title' => array(
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
        ),
        'description' => array(
          'name' => 'description',
          'type' => CRM_Utils_Type::T_TEXT,
        ),
        'is_active' => array(
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ),
        'price' => array(
          'name' => 'price',
          'type' => CRM_Utils_Type::T_MONEY,
        ),
        'material_category_id' => array(
          'name' => 'material_category_id',
          'type' => CRM_Utils_Type::T_STRING,
        ),
        'can_be_ordered' => array(
          'name' => 'can_be_ordered',
          'type' => CRM_Utils_Type::T_INT,
        ),
        'creation_year' => array(
          'name' => 'creation_year',
          'type' => CRM_Utils_Type::T_STRING,
        ),
        'language_id' => array(
          'name' => 'language_id',
          'type' => CRM_Utils_Type::T_STRING,
        ),
        'number_of_pages' => array(
          'name' => 'number_of_pages',
          'type' => CRM_Utils_Type::T_INT,
        ),
        'download_link' => array(
          'name' => 'download_link',
          'type' => CRM_Utils_Type::T_STRING,
        ),
      );
    }
    return self::$_fields;
  }
  /**
   * Returns an array containing, for each field, the array key used for that
   * field in self::$_fields.
   *
   * @access public
   * @return array
   */
  public static function &fieldKeys() {
    if (!(self::$_fieldKeys)) {
      self::$_fieldKeys = array(
        'id' => 'id',
        'title' => 'title',
        'description' => 'description',
        'is_active' => 'is_active',
        'price' => 'price',
        'material_category_id' => 'material_category_id',
        'can_be_ordered' => 'can_be_ordered',
        'creation_year' => 'creation_year',
        'language_id' => 'language_id',
        'number_of_pages' => 'number_of_pages',
        'download_link' => 'download_link',
      );
    }
    return self::$_fieldKeys;
  }
  /**
   * returns the list of fields that can be exported
   *
   * @access public
   * return array
   * @static
   */
  public static function &export($prefix = FALSE) {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = self::fields();
      foreach ($fields as $name => $field) {
        if (CRM_Utils_Array::value('export', $field)) {
          if ($prefix) {
            self::$_export['activity'] = & $fields[$name];
          }
          else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }

}
