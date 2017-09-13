<?php

class CRM_Materialbestellung_Page_MaterialActions extends CRM_Core_Page {

  public function run() {
    $materialId = CRM_Utils_Request::exportValues()['mid'];
    $action = CRM_Utils_Request::exportValues()['action'];
    // process disable, enable or delete
    switch ($action) {
      case "disable":
        civicrm_api3('FzfdMaterial', 'create', array(
          'id' => $materialId,
          'is_active' => 0,
        ));
        CRM_Core_Session::setStatus(ts('Material successfully disabled'), ts('Material Disabled'), 'success');
        break;
      case "enable":
        civicrm_api3('FzfdMaterial', 'create', array(
          'id' => $materialId,
          'is_active' => 1,
        ));
        CRM_Core_Session::setStatus(ts('Material successfully enabled'), ts('Material Enabled'), 'success');
        break;
    }
    $contextUrl = CRM_Utils_System::url('civicrm/fzfdmaterial/page/material', '&reset=1&action=browse', true);
    CRM_Utils_System::redirect($contextUrl);
  }
}
