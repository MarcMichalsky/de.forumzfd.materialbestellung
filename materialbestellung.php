<?php

use CRM_Materialbestellung_ExtensionUtil as E;

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
    'label' => E::ts('Materialbestellung: access Materialbestellung'),
    'description' => E::ts('Grants the necessary permissions to manage Materialbestellungen'),
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
  _materialbestellung_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function materialbestellung_civicrm_enable() {
  _materialbestellung_civix_civicrm_enable();
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
