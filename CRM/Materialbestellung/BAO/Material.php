<?php
/**
 * Class BAO Material
 *
 * @author Erik Hommel <hommel@ee-atwork.nl>
 * @date 25 July 2017
 * @license AGPL-3.0
 */

class CRM_Materialbestellung_BAO_Material extends CRM_Materialbestellung_DAO_Material {

  /**
   * Method to get values
   *
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function getValues($params) {
    $result = array();
    $material = new CRM_Materialbestellung_BAO_Material();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $material->$key = $value;
        }
      }
    }
    $material->find();
    while ($material->fetch()) {
      $row = array();
      self::storeValues($material, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }

  /**
   * Method to add or update material
   *
   * @param array $params
   * @return array $result
   * @access public
   * @throws Exception when params is empty
   * @static
   */
  public static function add($params) {
    $result = array();
    if (empty($params)) {
      throw new Exception('Params can not be empty when adding or updating a material in ' . __METHOD__);
    }
    $material = new CRM_Materialbestellung_BAO_Material();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $material->$key = $value;
      }
    }
    // always is_active to 1 if add mode (no id)
    if (!isset($params['id'])) {
      $material->is_active = 1;
    }
    $material->save();
    self::storeValues($material, $row);
    $result[$row['id']] = $row;
    return $result;
  }

  /**
   * Method to delete a material by id
   *
   * @param int $materialId
   * @return array $result
   * @throws Exception when materialId is empty
   */
  public static function deleteWithId($materialId) {
    // can not be deleted if there are still activities related to this material
    if (self::hasRelatedActivities($materialId) == TRUE) {
      throw new Exception('Material can not be deleted because there are still activities that are related to this material.');
    }
    if (empty($materialId)) {
      throw new Exception('material id can not be empty when attempting to delete a material in ' . __METHOD__);
    }
    $material = new CRM_Materialbestellung_BAO_Material();
    $material->id = $materialId;
    $material->delete();
    $result[$materialId] = array('id' => $materialId);
    return $result;
  }

  /**
   * Method to check if material still has activities related to it
   *
   * @param $materialId
   * @return bool
   */
  public static function hasRelatedActivities($materialId) {
    try {
      $config = CRM_Materialbestellung_Config::singleton();
      $count = civicrm_api3('Activity', 'getcount', array(
        'source_record_id' => $materialId,
        'activity_type_id' => $config->getMaterialBestellungActivityType('value'),
      ));
      if ($count > 0) {
        return TRUE;
      }
    }
    catch (CiviCRM_API3_Exception $ex) {
    }
    return FALSE;
  }

  /**
   * Method to check if params are valid
   *
   * @param $params
   * @throws Exception
   * @return bool
   */
  private static function validParams($params) {
    $noEmpties = array('material_id', 'quantity');
    foreach ($noEmpties as $noEmpty) {
      if (!isset($params[$noEmpty]) || empty($params[$noEmpty])) {
        throw new Exception('Parameter ' . $noEmpty . ' is required and can not be empty');
      }
    }
    // requires address['shipping']
    if (!isset($params['address']['shipping'])) {
      throw new Exception("Missing parameter address['shipping'] in " . __METHOD__);
    }
    // either contact_id or email has to be there!
    if (!isset($params['address']['shipping']['contact_id']) && !isset($params['address']['shipping']['email'])) {
      if (!isset($params['address']['shipping']['first_name']) || !isset($params['address']['shipping']['last_name'])) {
        throw new Exception('Either contact_id, email or first_name + last_name has to be present');
      }
    }
    // return error if material can not be ordered
    if (self::canBeOrdered($params['material_id']) == FALSE) {
      throw new Exception('Material can not be ordered at the moment');
    }
    return TRUE;
  }

  /**
   * Method to check if material can be ordered
   *
   * @param $materialId
   * @return bool
   */
  private static function canBeOrdered($materialId) {
    try {
      $canBeOrdered = civicrm_api3('FzfdMaterial', 'getvalue', array(
        'id' => $materialId,
        'return' => 'can_be_ordered',
      ));
      if ($canBeOrdered == TRUE) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    catch (CiviCRM_API3_Exception $ex) {
      return FALSE;
    }
  }

  /**
   * Method to add an order for the material
   *
   * @param $params
   * @return array
   * @throws Exception when required parameter is not present or empty
   */
  public static function addOrder($params) {
    if (self::validParams($params) == TRUE) {
      // process target contact
      $forumContact = new CRM_Apiprocessing_Contact();
      // put addresses data in array
      $contactParams = self::prepareContactParams($params['address']['shipping']);
      $individualId = $forumContact->processIncomingIndividual($contactParams);
      $config = CRM_Materialbestellung_Config::singleton();
      // get material with id, error if not exists
      try {
        $material = civicrm_api3('FzfdMaterial', 'getsingle', array('id' => $params['material_id']));
      }
      catch (CiviCRM_API3_Exception $ex) {
        throw new Exception('Could not find material with id ' . $params['material_id'] . ' in ' . __METHOD__);
      }
      // create activity data for material order
      $activityData = array(
        'activity_type_id' => $config->getMaterialBestellungActivityType('value'),
        'status_id' => $config->getScheduledActivityStatusId(),
        'subject' => 'Bestellung für ' . $params['quantity'] . ' Material ' . $material['title'] . ' mit preis ' . $material['price'],
        'target_id' => $individualId,
        'source_record_id' => $params['material_id'],
        'details' => CRM_Apiprocessing_Utils::renderTemplate('CRM/Materialbestellung/MaterialBestellungDetails.tpl', $params),
      );
      $optionals = array('location', 'campaign_id');
      foreach ($optionals as $optional) {
        if (isset($params[$optional]) && !empty($params[$optional])) {
          $activityData[$optional] = $params[$optional];
        }
      }
      // add custom data to activity data for billing address
      if (isset($params['address']['billing'])) {
        self::addActivityCustomData($params['address']['billing'], $activityData);
      }
      else {
        CRM_Core_Error::debug_log_message(ts('Could not find array address and element billing in parameters in ')
          . __METHOD__ . ts(', no billing address added to order activity'));
      }
      $activity = self::addOrderActivity($activityData);
      return $activity;
    }
    return array(
      'is_error' => '1',
      'version' => '3',
      'count' => '0',
      'error_message' => ts('Unknown error in de.forumzfde.materialbestellung ' . __METHOD__),
    );
  }

  /**
   * Method to prepare the contact data
   *
   * @param $data
   * @return array
   */
  private static function prepareContactParams($data) {
    $result = array();
    if (isset($data['contact_id']) && !empty($data['contact_id'])) {
      $result['individual_id'] = $data['contact_id'];
    }
    if (isset($data['first_name']) && !empty($data['first_name'])) {
      $result['first_name'] = $data['first_name'];
    }
    if (isset($data['last_name']) && !empty($data['last_name'])) {
      $result['last_name'] = $data['last_name'];
    }
    if (isset($data['email']) && !empty($data['email'])) {
      $result['email'] = $data['email'];
    }
    $result['individual_addresses'] = self::prepareAddressParams($data);
    return $result;
  }

  /**
   * Method to add custom fields for billing address to activity data
   *
   * @param $billingAddress
   * @param $activityData
   */
  private static function addActivityCustomData($billingAddress, &$activityData) {
    if (!empty($billingAddress)) {
      $config = CRM_Materialbestellung_Config::singleton();
      if (isset($billingAddress['prefix_id']) && !empty($billingAddress['prefix_id'])) {
        // get prefix label
        try {
          $activityData['custom_' . $config->getPrefixIdCustomFieldId()] = civicrm_api3('OptionValue', 'getvalue', array(
            'option_group_id' => 'individual_prefix',
            'value' => $billingAddress['prefix_id'],
            'return' => 'label',
          ));
        }
        catch (CiviCRM_API3_Exception $ex) {
          CRM_Core_Error::debug_log_message(ts('Could not find prefix name for id ') . $billingAddress['prefix_id']
            . ts(' in ') . __METHOD__);
        }
      }
      if (isset($billingAddress['formal_title']) && !empty($billingAddress['formal_title'])) {
        $activityData['custom_' . $config->getFormalTitleCustomFieldId()] = $billingAddress['formal_title'];
      }
      if (isset($billingAddress['first_name']) && !empty($billingAddress['first_name'])) {
        $activityData['custom_' . $config->getFirstNameCustomFieldId()] = $billingAddress['first_name'];
      }
      if (isset($billingAddress['last_name']) && !empty($billingAddress['last_name'])) {
        $activityData['custom_' . $config->getLastNameCustomFieldId()] = $billingAddress['last_name'];
      }
      if (isset($billingAddress['email']) && !empty($billingAddress['email'])) {
        $activityData['custom_' . $config->getEmailCustomFieldId()] = $billingAddress['email'];
      }
      if (isset($billingAddress['street_address']) && !empty($billingAddress['street_address'])) {
        $activityData['custom_' . $config->getStreetAddressCustomFieldId()] = $billingAddress['street_address'];
      }
      if (isset($billingAddress['supplemental_address']) && !empty($billingAddress['supplemental_address'])) {
        $activityData['custom_' . $config->getSupplementalAddressCustomFieldId()] = $billingAddress['supplemental_address'];
      }
      if (isset($billingAddress['postal_code']) && !empty($billingAddress['postal_code'])) {
        $activityData['custom_' . $config->getPostalCodeCustomFieldId()] = $billingAddress['postal_code'];
      }
      if (isset($billingAddress['city']) && !empty($billingAddress['city'])) {
        $activityData['custom_' . $config->getCityCustomFieldId()] = $billingAddress['city'];
      }
      if (isset($billingAddress['country_iso']) && !empty($billingAddress['country_iso'])) {
        // retrieve country label with iso
        try {
          $activityData['custom_' . $config->getCountryCustomFieldId()] = civicrm_api3('Country', 'getvalue', array(
            'return' => "name",
            'iso_code' => $billingAddress['country_iso'],
          ));
        }
        catch (CiviCRM_API3_Exception $ex) {
          CRM_Core_Error::debug_log_message(ts('Could not find country name for iso code ') . $billingAddress['country_iso']
            . ts(' in ') . __METHOD__);
        }
      }
    }
  }

  /**
   * Method to prepare the address params
   *
   * @param array $params
   * @return array $address
   */
  private static function prepareAddressParams($params) {
    $address = array();
    // use shipping part of address
    if (isset($params['street_address']) && !empty($params['street_address'])) {
      $address['street_address'] = $params['street_address'];
    }
    if (isset($params['supplemental_address']) && !empty($params['supplemental_address'])) {
      $address['supplemental_address_1'] = $params['supplemental_address'];
    }
    if (isset($params['city']) && !empty($params['city'])) {
      $address['city'] = $params['city'];
    }
    if (isset($params['postal_code']) && !empty($params['postal_code'])) {
      $address['postal_code'] = $params['postal_code'];
    }
    if (isset($params['country_iso']) && !empty($params['country_iso'])) {
      $address['country_iso'] = $params['country_iso'];
    }
    return $address;
  }

  /**
   * Method to add material order activity
   *
   * @param $orderParams
   * @return array
   */
  private static function addOrderActivity($orderParams) {
    try {
      $activity = civicrm_api3('Activity', 'create', $orderParams);
      return $activity['values'];
    }
    catch (CiviCRM_API3_Exception $ex) {
      return array();
    }
  }

}
