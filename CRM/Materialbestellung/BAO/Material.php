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
    // todo check civirules to see how to save wysisyg for description
    $result = array();
    if (empty($params)) {
      throw new Exception('Params can not be empty when adding or updating a material in '.__METHOD__);
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
      throw new Exception('material id can not be empty when attempting to delete a material in '.__METHOD__);
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
   * Method to add an order for the material
   *
   * @param $params
   * @return array
   * @throws Exception when required parameter is not present or empty
   */
  public static function addOrder($params) {
    $noEmpties = array('material_id', 'quantity',);
    foreach ($noEmpties as $noEmpty) {
      if (!isset($params[$noEmpty]) || empty($params[$noEmpty])) {
        throw new Exception('Parameter '.$noEmpty.' is required and can not be empty');
      }
    }
    // either contact_id or email has to be there!
    if (!isset($params['contact_id']) && !isset($params['email'])) {
      if (!isset($params['first_name']) || !isset($params['last_name'])) {
        throw new Exception('Either contact_id, email or first_name + last_name has to be present');
      }
    }
    // process target contact
    $forumContact = new CRM_Apiprocessing_Contact();
    // put address data in array
    self::prepareAddressParams($params);
    $individualId = $forumContact->processIncomingIndividual($params);

    $config = CRM_Materialbestellung_Config::singleton();
    // get material with id, error if not exists
    try {
      $material = civicrm_api3('FzfdMaterial', 'getsingle', array('id' => $params['material_id'],));
    }
    catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not find material with id '.$params['material_id'].' in '.__METHOD__);
    }
    // create activity for material order
    $orderParams = array(
      'activity_type_id' => $config->getMaterialBestellungActivityType('value'),
      'status_id' => $config->getScheduledActivityStatusId(),
      'subject' => 'Bestellung für '.$params['quantity'].' Material '.
        $material['title'].' mit preis '.$material['price'],
      'target_id' => $individualId,
      'source_record_id' => $params['material_id'],
      'details' => CRM_Apiprocessing_Utils::renderTemplate('CRM/Materialbestellung/MaterialBestellungDetails.tpl', $params),
    );
    $optionals = array('location', 'campaign_id');
    foreach ($optionals as $optional) {
      if (isset($params[$optional]) && !empty($params[$optional])) {
        $orderParams[$optional] = $params[$optional];
      }
    }
    $activity = self::addOrderActivity($orderParams);
    return $activity;
  }

  /**
   * Method to prepare the address params
   *
   * @param $params
   * @return array
   */
  private static function prepareAddressParams(&$params) {
    $addressParams = array();
    if (isset($params['street_address']) && !empty($params['street_address'])) {
      $addressParams['street_address'] = $params['street_address'];
    }
    if (isset($params['city']) && !empty($params['city'])) {
      $addressParams['city'] = $params['city'];
    }
    if (isset($params['postal_code']) && !empty($params['postal_code'])) {
      $addressParams['postal_code'] = $params['postal_code'];
    }
    if (isset($params['country_iso']) && !empty($params['country_iso'])) {
      $addressParams['country_iso'] = $params['country_iso'];
    }
    if (!empty($addressParams)) {
      $params['individual_addresses'][] = $addressParams;
    }
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
    } catch (CiviCRM_API3_Exception $ex) {
      return array();
    }
  }
}