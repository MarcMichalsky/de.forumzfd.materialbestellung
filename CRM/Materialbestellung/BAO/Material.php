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
      throw new Exception('Params can not be empty when adding or updating a material in '.__METHOD__);
    }
    $material = new CRM_Materialbestellung_BAO_Material();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $material->$key = $value;
      }
    }
    $material->save();
    self::storeValues($material, $result);
    return $result;
  }

  /**
   * Method to delete a material by id
   *
   * @param int $materialId
   * @throws Exception when materialId is empty
   */
  public static function deleteWithId($materialId) {
    if (empty($materialId)) {
      throw new Exception('material id can not be empty when attempting to delete a material in '.__METHOD__);
    }
    $material = new CRM_Materialbestellung_BAO_Material();
    $material->id = $materialId;
    $material->delete();
  }

}