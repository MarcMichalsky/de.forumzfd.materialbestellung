<?php
/**
 * Class for extension specific utility functions
 *
 * @author Erik Hommel <hommel@ee-atwork.nl>
 * @date 27 July 2017
 * @license AGPL-3.0
 */

class CRM_Materialbestellung_Utils {

  /**
   * Function to find the resources path
   *
   * @throws Exception when resources folder not found or not a folder
   * @return string
   */
  private static function getResourcesPath() {
    // Get the directory of the extension based on the name.
    $container = CRM_Extension_System::singleton()->getFullContainer();
    $resourcesPath = $container->getPath('de.forumzfd.materialbestellung').'/resources/';
    if (!is_dir($resourcesPath) || !file_exists($resourcesPath)) {
      throw new Exception(ts(ts('Could not find the folder ').$resourcesPath
        .ts(' which is required for extension de.forumzfd.materialbestellung in ').__METHOD__
        .ts('.It does not exist or is not a folder, contact your system administrator')));
    }
    return $resourcesPath;
  }

  /**
   * Function to create the activity types from a json file
   *
   * @throws Exception
   */
  public static function createActivityTypesFromJson() {
    $resourcesPath = self::getResourcesPath();
    $jsonFile = $resourcesPath.'activity_types.json';
    if (!file_exists($jsonFile)) {
      throw new Exception(ts(ts('Could not load activity_types configuration file for extension in ').__METHOD__
        .ts(', contact your system administrator!')));
    }
    $activityTypesJson = file_get_contents($jsonFile);
    $activityTypes = json_decode($activityTypesJson, true);
    foreach ($activityTypes as $name => $activityTypeParams) {
      if (!self::activityTypeExists($activityTypeParams['name'])) {
        self::createActivityType($activityTypeParams);
      } else {
        // make sure they are active!
        $activityTypeOptionGroupId = civicrm_api3('OptionGroup', 'getvalue', array(
          'name' => 'activity_type',
          'return' => 'id',
        ));
        try {
          if ($activityTypeOptionGroupId) {
            $query = "UPDATE civicrm_option_value SET is_active = %1 WHERE option_group_id = %2 AND NAME = %3";
            CRM_Core_DAO::executeQuery($query, array(
              1 => array(1, 'Integer',),
              2 => array($activityTypeOptionGroupId, 'Integer'),
              3 => array($name, 'String'),
            ));
          }
        } catch (Exception $ex) {
        }
      }
    }
  }

  /**
   * Function to disable the activity types from a json file
   *
   * @throws Exception
   */
  public static function disableActivityTypesFromJson() {
    $resourcesPath = self::getResourcesPath();
    $jsonFile = $resourcesPath.'activity_types.json';
    if (file_exists($jsonFile)) {
      $activityTypesJson = file_get_contents($jsonFile);
      $activityTypes = json_decode($activityTypesJson, true);
      foreach ($activityTypes as $name => $activityTypeParams) {
        if (self::activityTypeExists($name)) {
          $activityTypeOptionGroupId = civicrm_api3('OptionGroup', 'getvalue', array(
            'name' => 'activity_type',
            'return' => 'id',
          ));
          try {
            if ($activityTypeOptionGroupId) {
              $query = "UPDATE civicrm_option_value SET is_active = %1 WHERE option_group_id = %2 AND NAME = %3";
              CRM_Core_DAO::executeQuery($query, array(
                1 => array(0, 'Integer',),
                2 => array($activityTypeOptionGroupId, 'Integer'),
                3 => array($name, 'String'),
              ));
            }
          } catch (Exception $ex) {
          }
        }
      }
    }
  }

  /**
   * Function to disable the option groups from a json file
   *
   * @throws Exception
   */
  public static function disableOptionGroupsFromJson() {
    $resourcesPath = self::getResourcesPath();
    $jsonFile = $resourcesPath.'option_groups.json';
    if (file_exists($jsonFile)) {
      $optionGroupsJson = file_get_contents($jsonFile);
      $optionGroups = json_decode($optionGroupsJson, true);
      foreach ($optionGroups as $name => $optionGroupParams) {
        if (self::activityTypeExists($name)) {
          try {
            $query = "UPDATE civicrm_option_group SET is_active = %1 WHERE name = %2";
            CRM_Core_DAO::executeQuery($query, array(
              1 => array(0, 'Integer',),
              2 => array($name, 'String'),
            ));
          } catch (Exception $ex) {
          }
        }
      }
    }
  }

  /**
   * Function to create the option groups and values from a json file
   *
   * @throws Exception
   */
  public static function createOptionGroupsFromJson() {
    $resourcesPath = self::getResourcesPath();
    $jsonFile = $resourcesPath.'option_groups.json';
    if (!file_exists($jsonFile)) {
      throw new Exception(ts(ts('Could not load option_groups configuration file for extension in ').__METHOD__
        .ts(', contact your system administrator!')));
    }
    $optionGroupsJson = file_get_contents($jsonFile);
    $optionGroups = json_decode($optionGroupsJson, true);
    foreach ($optionGroups as $name => $optionGroupParams) {
      if (!self::optionGroupExists($optionGroupParams['name'])) {
        self::createOptionGroup($optionGroupParams);
      } else {
        // make sure they are active!
        try {
          $query = "UPDATE civicrm_option_group SET is_active = %1 WHERE name = %2";
          CRM_Core_DAO::executeQuery($query, array(
            1 => array(1, 'Integer',),
            2 => array($name, 'String'),
          ));
        } catch (Exception $ex) {
        }

      }
    }
  }

  /**
   * Function to create option group
   *
   * @param $params
   * @throws Exception when error from api create
   */
  public static function createOptionGroup($params) {
    if (!isset($params['name']) || empty($params['name'])) {
      throw new Exception(ts('Could not create option group in ').__METHOD__
        .ts(', parameters do not contain mandatory element name or name is empty. Contact your system administrator'));
    }
    if (!isset($params['is_active'])) {
      $params['is_active'] = 1;
    }
    if (!isset($params['is_reserved'])) {
      $params['is_reserved'] = 0;
    }
    if (!isset($params['title'])) {
      $params['title'] = ucfirst($params['name']);
    }
    if (isset($params['option_values'])) {
      $optionValues = $params['option_values'];
      unset($params['option_values']);
    }
    try {
      $optionGroup = civicrm_api3('OptionGroup', 'Create', $params);
      if (isset($optionValues) && !empty($optionValues)) {
        self::addOptionValues($optionGroup['id'], $optionValues);
      }
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not create or update option_group with name')
          .$params['name'].' in '.__METHOD__.ts(', error from API OptionGroup Create: ') . $ex->getMessage());
    }
  }

  /**
   * Function to add option values to option group
   *
   * @param $optionGroupId
   * @param $optionValues
   */
  public static function addOptionValues($optionGroupId, $optionValues) {
    foreach ($optionValues as $optionValueParams) {
      if (isset($optionValueParams['name']) && !empty($optionValueParams['name'])) {
        $optionValueParams['option_group_id'] = $optionGroupId;
        if (!isset($optionValueParams['is_active'])) {
          $optionValueParams['is_active'] = 1;
        }
        if (!isset($optionValueParams['is_reserved'])) {
          $optionValueParams['is_reserved'] = 0;
        }
        try {
          civicrm_api3('OptionValue', 'Create', $optionValueParams);
        } catch (CiviCRM_API3_Exception $ex) {
        }
      }
    }
  }

  /**
   * Function to check if option group exists on name
   *
   * @param $optionGroupName
   * @return bool
   */
  public static function optionGroupExists($optionGroupName) {
    try {
      $count = civicrm_api3('OptionGroup', 'getcount', array(
        'name' => $optionGroupName,
      ));
      if ($count == 1) {
        return TRUE;
      } else {
        return FALSE;
      }
    }
    catch (CiviCRM_API3_Exception $ex) {
      return FALSE;
    }
  }

  /**
   * Function to create activity type
   *
   * @param $params
   * @return array
   * @throws Exception when error from api create
   */
  public static function createActivityType($params) {
    if (!isset($params['name']) || empty($params['name'])) {
      throw new Exception(ts('Could not create activity type in ').__METHOD__
        .ts(', parameters do not contain mandatory element name or name is empty. Contact your system administrator'));
    }
    $params['option_group_id'] = 'activity_type';
    if (!isset($params['is_active'])) {
      $params['is_active'] = 1;
    }
    if (!isset($params['is_reserved'])) {
      $params['is_reserved'] = 0;
    }
    try {
      return civicrm_api3('OptionValue', 'Create', $params);
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception(ts('Could not create activity type with name ').$params['name'].' in '.__METHOD__
          .ts(' error from API OptionValue Create: ').$ex->getMessage());
    }
  }

  /**
   * Function to check if an activity type exists on name
   *
   * @param $activityTypeName
   * @return bool
   */
  public static function activityTypeExists($activityTypeName) {
    try {
      $count = civicrm_api3('OptionValue', 'getcount', array(
        'option_group_id' => 'activity_type',
        'name' => $activityTypeName,
      ));
      if ($count == 1) {
        return TRUE;
      } else {
        return FALSE;
      }
    }
    catch (CiviCRM_API3_Exception $ex) {
      return FALSE;
    }
  }

  /**
   * Function to get the material category with the id
   *
   * @param $materialCategoryId
   * @return array|bool
   */
  public static function getMaterialCategoryWithId($materialCategoryId) {
    if (!empty($materialCategoryId)) {
      try {
        return civicrm_api3('OptionValue', 'getvalue', array(
          'option_group_id' => 'fzfd_material_category',
          'is_active' => 1,
          'value' => $materialCategoryId,
          'return' => 'label'
        ));
      }
      catch (CiviCRM_API3_Exception $ex) {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  /**
   * Function to get the language with the id
   *
   * @param $languageId
   * @return array|bool
   */
  public static function getLanguageWithId($languageId) {
    if (!empty($languageId)) {
      try {
        return civicrm_api3('OptionValue', 'getvalue', array(
          'option_group_id' => 'languages',
          'is_active' => 1,
          'name' => $languageId,
          'return' => 'label'
        ));
      }
      catch (CiviCRM_API3_Exception $ex) {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }
}