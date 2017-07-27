<?php
/**
 * Class for extension specific Config
 *
 * @author Erik Hommel <hommel@ee-atwork.nl>
 * @date 27 July 2017
 * @license AGPL-3.0
 */

class CRM_Materialbestellung_Config {


  static private $_singleton = NULL;

  private $_materialBestellungActivityType = array();
  private $_materialCategoryOptionGroup = array();
  private $_languageOptionGroup = array();

  /**
   * CRM_Materialbestellung_Config constructor.
   */
  function __construct() {
    try {
      $this->_materialCategoryOptionGroup = civicrm_api3('OptionGroup', 'getsingle', array(
        'name' => 'fzfd_material_category',
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not find required option group for material category in ').__METHOD__
        .ts(', contact your system administrator'));
    }
    try {
      $this->_languageOptionGroup = civicrm_api3('OptionGroup', 'getsingle', array(
        'name' => 'languages',
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not find required option group for languages in ').__METHOD__
        .ts(', contact your system administrator'));
    }
    try {
      $this->_materialBestellungActivityType = civicrm_api3('OptionValue', 'getsingle', array(
        'option_group_id' => 'activity_type',
        'name' => 'fzfd_material_bestellung',
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not find required activity type for material bestellung in ').__METHOD__
        .ts(', contact your system administrator'));
    }
  }

  /**
   * Getter for material bestellung activity type
   *
   * @param null $key
   * @return array|mixed
   */
  public function getMaterialBestellungActivityType($key = NULL) {
    if (!empty($key) && isset($this->_materialBestellungActivityType[$key])) {
      return $this->_materialBestellungActivityType[$key];
    } else {
      return $this->_materialBestellungActivityType;
    }
  }

  /**
   * Getter for material category option group
   *
   * @param null $key
   * @return array|mixed
   */
  public function getMaterialCategoryOptionGroup($key = NULL) {
    if (!empty($key) && isset($this->_materialCategoryOptionGroup[$key])) {
      return $this->_materialCategoryOptionGroup[$key];
    } else {
      return $this->_materialCategoryOptionGroup;
    }
  }

  /**
   * Getter for languages option group
   *
   * @param null $key
   * @return array|mixed
   */
  public function getLanguagesOptionGroup($key = NULL) {
    if (!empty($key) && isset($this->_languageOptionGroup[$key])) {
      return $this->_languageOptionGroup[$key];
    } else {
      return $this->_languageOptionGroup;
    }
  }

  /**
   * Method to return singleton object
   *
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Materialbestellung_Config();
    }
    return self::$_singleton;
  }

}