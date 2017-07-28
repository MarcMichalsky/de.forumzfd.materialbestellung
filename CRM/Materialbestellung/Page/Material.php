<?php
/**
 * Page Material to list all materials
 *
 * @author Erik Hommel <hommel@ee-atwork.nl>
 * @date 27 July 2017
 * @license AGPL-3.0
 */

class CRM_Materialbestellung_Page_Material extends CRM_Core_Page {

  public function run() {
    $this->setPageConfiguration();
    $this->initializePager();
    $materialRows = $this->getMaterial();
    $this->assign('materialRows', $materialRows);
    parent::run();
  }

  /**
   * Method to get the material data
   *
   * @return array
   */
  private function getMaterial() {
    $materialRows = array();
    try {
      $material = civicrm_api3('FzfdMaterial', 'get', array());
      if (isset($material['values'])) {
        foreach ($material['values'] as $materialId => $materialData) {
          $materialRow = $materialData;
          if (isset($materialRow['material_category_id'])) {
            $materialRow['material_category'] = CRM_Materialbestellung_Utils::getMaterialCategoryWithId($materialRow['material_category_id']);
          }
          if (isset($materialRow['language_id'])) {
            $materialRow['language'] = CRM_Materialbestellung_Utils::getLanguageWithId($materialRow['language_id']);
          }
          $materialRow['actions'] = $this->getRowActions($materialId);
          $materialRows[$materialId] = $materialRow;
        }
        return $materialRows;
      } else {
        return $materialRows;
      }
    }
    catch (CiviCRM_API3_Exception $ex) {
      return $materialRows;
    }
  }

  /**
   * Method to get the page actions and the related url
   *
   * @param $materialId
   * @return array
   */
  private function getRowActions($materialId) {
    $actions = array();
    $viewUrl = CRM_Utils_System::url('civicrm/fzfdmaterial/form/material', 'action=view&id='.$materialId, true);
    $editUrl = CRM_Utils_System::url('civicrm/fzfdmaterial/form/material', 'action=edit&id='.$materialId, true);
    $deleteUrl = CRM_Utils_System::url('civicrm/fzfdmaterial/form/material', 'action=delete&id='.$materialId, true);
    $actions[] = '<a class="action-item" title="'.ts("View").' Material" href="'.$viewUrl.'">'.ts("View").'</a>';
    $actions[] = '<a class="action-item" title="'.ts("Edit").' Material" href="'.$editUrl.'">'.ts("Edit").'</a>';
    $actions[] = '<a class="action-item" title="'.ts("Delete").' Material" href="'.$deleteUrl.'">'.ts("Delete").'</a>';
    return $actions;
  }

  /**
   * Method to set the page configuration
   *
   * @access protected
   */
  protected function setPageConfiguration() {
    CRM_Utils_System::setTitle(ts("ForumZFD Material"));
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/fzfdmaterial/page/material', 'reset=1', true));
    $this->assign('addUrl', CRM_Utils_System::url('civicrm/fzfdmaterial/form/material', 'action=add&reset=1', true));

  }

  /**
   * Method to initialize pager
   *
   * @access protected
   */
  protected function initializePager() {
    $params           = array(
      'total' => CRM_Core_DAO::singleValueQuery("SELECT COUNT(*) FROM forumzfd_material"),
      'rowCount' => CRM_Utils_Pager::ROWCOUNT,
      'status' => ts('ForumZFD Material %%StatusMessage%%'),
      'buttonBottom' => 'PagerBottomButton',
      'buttonTop' => 'PagerTopButton',
      'pageID' => $this->get(CRM_Utils_Pager::PAGE_ID),
    );
    $this->_pager = new CRM_Utils_Pager($params);
    $this->assign_by_ref('pager', $this->_pager);
  }

}
