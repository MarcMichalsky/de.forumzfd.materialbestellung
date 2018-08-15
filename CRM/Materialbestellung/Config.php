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
  private $_scheduledActivityStatusId = NULL;
  private $_prefixIdCustomFieldId = NULL;
  private $_formalTitleCustomFieldId = NULL;
  private $_firstNameCustomFieldId = NULL;
  private $_lastNameCustomFieldId = NULL;
  private $_emailCustomFieldId = NULL;
  private $_streetAddressCustomFieldId = NULL;
  private $_supplementalAddressCustomFieldId  = NULL;
  private $_postalCodeCustomFieldId = NULL;
  private $_cityCustomFieldId = NULL;
  private $_countryCustomFieldId = NULL;
  private $_rechnungsAdresseCustomGroupName = NULL;

  /**
   * CRM_Materialbestellung_Config constructor.
   */
  public function __construct() {
    $this->_rechnungsAdresseCustomGroupName = "fzfd_material_rechnungsadresse";
    $this->setCustomFields();
    try {
      $this->_materialCategoryOptionGroup = civicrm_api3('OptionGroup', 'getsingle', array(
        'name' => 'fzfd_material_category',
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not find required option group for material category in ') . __METHOD__
        . ts(', contact your system administrator'));
    }
    try {
      $this->_languageOptionGroup = civicrm_api3('OptionGroup', 'getsingle', array(
        'name' => 'languages',
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not find required option group for languages in ') . __METHOD__
        . ts(', contact your system administrator'));
    }
    try {
      $this->_materialBestellungActivityType = civicrm_api3('OptionValue', 'getsingle', array(
        'option_group_id' => 'activity_type',
        'name' => 'fzfd_material_bestellung',
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not find required activity type for material bestellung in ') . __METHOD__
        . ts(', contact your system administrator'));
    }
    try {
      $this->_scheduledActivityStatusId = civicrm_api3('OptionValue', 'getvalue', array(
        'option_group_id' => 'activity_status',
        'name' => 'Scheduled',
        'return' => 'value',
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception((ts('Could not find required activity status Scheduled in ' . __METHOD__
        . ', contact your system administrator')));
    }
  }

  /**
   * Getter for prefix id custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getPrefixIdCustomFieldId() {
    return $this->_prefixIdCustomFieldId;
  }

  /**
   * Getter for formal title custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getFormalTitleCustomFieldId() {
    return $this->_formalTitleCustomFieldId;
  }

  /**
   * Getter for first name custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getFirstNameCustomFieldId() {
    return $this->_firstNameCustomFieldId;
  }

  /**
   * Getter for last name custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getLastNameCustomFieldId() {
    return $this->_lastNameCustomFieldId;
  }

  /**
   * Getter for email custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getEmailCustomFieldId() {
    return $this->_emailCustomFieldId;
  }

  /**
   * Getter for street address custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getStreetAddressCustomFieldId() {
    return $this->_streetAddressCustomFieldId;
  }

  /**
   * Getter for supplemental address custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getSupplementalAddressCustomFieldId() {
    return $this->_supplementalAddressCustomFieldId;
  }

  /**
   * Getter for postal code custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getPostalCodeCustomFieldId() {
    return $this->_postalCodeCustomFieldId;
  }

  /**
   * Getter for city custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getCityCustomFieldId() {
    return $this->_cityCustomFieldId;
  }

  /**
   * Getter for country custom field rechnungsadresse
   *
   * @return array|null
   */
  public function getCountryCustomFieldId() {
    return $this->_countryCustomFieldId;
  }

  /**
   * Getter for scheduled activity status id
   *
   * @return array|null
   */
  public function getScheduledActivityStatusId() {
    return $this->_scheduledActivityStatusId;
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
    }
    else {
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
    }
    else {
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
    }
    else {
      return $this->_languageOptionGroup;
    }
  }

  /**
   * Method to set the custom field properties for rechnungsadresse
   */
  private function setCustomFields() {
    try {
      $apiResult = civicrm_api3('CustomField', 'get', array(
        'custom_group_id' => $this->_rechnungsAdresseCustomGroupName,
      ));
      foreach ($apiResult['values'] as $customFieldId => $customField) {
        switch ($customField['name']) {
          case "fzfd_ra_prefix":
            $this->_prefixIdCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_formal_title":
            $this->_formalTitleCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_first_name":
            $this->_firstNameCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_last_name":
            $this->_lastNameCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_email":
            $this->_emailCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_street_address":
            $this->_streetAddressCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_supplemental_address":
            $this->_supplementalAddressCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_postcode":
            $this->_postalCodeCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_city":
            $this->_cityCustomFieldId = $customFieldId;
            break;

          case "fzfd_ra_country":
            $this->_countryCustomFieldId = $customFieldId;
            break;
        }
      }
    }
    catch (CiviCRM_API3_Exception $ex) {
      CRM_Core_Error::debug_log_message(ts('Could not find custom fields for rechnungsadresse in ') . __METHOD__
        . ts(', no rechnungsadresse added to materialbestellung activity'));
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
