<?php

require_once 'materialbestellung.civix.php';

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function materialbestellung_civicrm_navigationMenu(&$menu) {
  CRM_Materialbestellung_Utils::insertNavigationMenu($menu, "", array(
    'label' => ts('Material'),
    'name' => 'fzfd_material',
    'url' => 'civicrm/fzfdmaterial/page/material?reset=1&action=browse',
    'permission' => 'access materialbestellung',
    'operator' => 'AND',
    'icon' => 'fa fa-cube',
    'separator' => 0,
  ));
  _materialbestellung_civix_navigationMenu($menu);
}

/**
 * Add custom permissions
 */
function materialbestellung_civicrm_permission(&$permissions) {
  $permissions['access materialbestellung'] = [
    'Materialbestellung: access Materialbestellung',
    'Grants the necessary permissions to manage Materialbestellungen',
  ];
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function materialbestellung_civicrm_config(&$config) {
  _materialbestellung_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function materialbestellung_civicrm_install() {
  // check if required extensions are installed
  _materialbestellung_required_extensions_installed();
  _materialbestellung_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function materialbestellung_civicrm_postInstall() {
  _materialbestellung_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function materialbestellung_civicrm_uninstall() {
  _materialbestellung_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function materialbestellung_civicrm_enable() {
  // check if required extensions are installed
  _materialbestellung_required_extensions_installed();
  _materialbestellung_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function materialbestellung_civicrm_disable() {
  _materialbestellung_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function materialbestellung_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _materialbestellung_civix_civicrm_upgrade($op, $queue);
}

/**
 * Function to check if the required extensions are installed
 *
 * @throws Exception
 */
function _materialbestellung_required_extensions_installed() {
  $required = array(
    'de.forumzfd.apiprocessing' => FALSE,
  );
  $installedExtensions = civicrm_api3('Extension', 'get', array(
    'options' => array('limit' => 0),
    ));
  foreach ($installedExtensions['values'] as $installedExtension) {
    if (isset($required[$installedExtension['key']]) && $installedExtension['status'] == 'installed') {
      $required[$installedExtension['key']] = TRUE;
    }
  }
  foreach ($required as $requiredExtension => $installed) {
    if (!$installed) {
      throw new Exception('Required extension ' . $requiredExtension . ' is not installed, can not install or enable 
      de.forumzfd.materialbestellung. Please install the extension and then retry installing or enabling 
      de.forumzfd.materialbestellung');
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function materialbestellung_civicrm_navigationMenu(&$menu) {
  _materialbestellung_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'de.forumzfd.materialbestellung')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _materialbestellung_civix_navigationMenu($menu);
} // */

/**
 * Implements hook_civicrm_entityTypes().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function materialbestellung_civicrm_entityTypes(&$entityTypes) {
  _materialbestellung_civix_civicrm_entityTypes($entityTypes);
}
