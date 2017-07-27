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
   * Method to set the page configuration
   *
   * @access protected
   */
  protected function setPageConfiguration() {
    CRM_Utils_System::setTitle(ts("ForumZFD Material"));
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/fzfdmaterial/page/material', 'reset=1', true));
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
