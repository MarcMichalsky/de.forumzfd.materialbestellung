<?php
/**
 * Basic Page Material to list all materials
 *
 * @author Erik Hommel <hommel@ee-atwork.nl>
 * @date 27 July 2017
 * @license AGPL-3.0
 */

class CRM_Materialbestellung_Page_Material extends CRM_Core_Page_Basic {

  public $useLivePageJS = TRUE;

  /**
   * The action links that we need to display for the browse screen.
   *
   * @var array
   */
  static $_links = NULL;

  /**
   * Get BAO Name.
   *
   * @return string
   *   Classname of BAO.
   */
  public function getBAOName() {
    return 'CRM_Materialbestellung_BAO_Material';
  }

  /**
   * Get action Links.
   *
   * @return array
   *   (reference) of action links
   */
  public function &links() {
    if (!(self::$_links)) {
      self::$_links = array(
        CRM_Core_Action::VIEW => array(
          'name' => ts('View'),
          'url' => 'civicrm/fzfdmaterial/form/material',
          'qs' => 'action=view&mid=%%id%%&reset=1',
          'title' => ts('View').' Material',
        ),
        CRM_Core_Action::UPDATE => array(
          'name' => ts('Edit'),
          'url' => 'civicrm/fzfdmaterial/form/material',
          'qs' => 'action=update&mid=%%id%%&reset=1',
          'title' => ts('Edit').' Material',
        ),
        CRM_Core_Action::DISABLE => array(
          'name' => ts('Disable'),
          'ref' => 'crm-enable-disable',
          'url' => 'civicrm/materialbestellung/page/processmaterialactions',
          'qs' => 'action=disable&mid=%%id%%&reset=1',
          'title' => 'Disable Material',
        ),
        CRM_Core_Action::ENABLE => array(
          'name' => ts('Enable'),
          'ref' => 'crm-enable-disable',
          'url' => 'civicrm/materialbestellung/page/processmaterialactions',
          'qs' => 'action=enable&mid=%%id%%&reset=1',
          'title' => 'Enable Material',
        ),
      );
    }
    return self::$_links;
  }

  /**
   * Get name of edit form.
   *
   * @return string
   *   Classname of edit form.
   */
  public function editForm() {
    return 'CRM_Materialbestellung_Form_Material';
  }

  /**
   * Get edit form name.
   *
   * @return string
   *   name of this page.
   */
  public function editName() {
    return 'ForumZFD Material';
  }

  /**
   * Get user context.
   *
   * @param null $mode
   *
   * @return string
   *   user context.
   */
  public function userContext($mode = NULL) {
    return CRM_Utils_System::url('civicrm/fzfdmaterial/page/material', 'reset=1&action=browse', true);
  }

}
