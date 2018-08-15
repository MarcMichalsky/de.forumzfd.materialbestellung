<?php

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Materialbestellung_Form_Material extends CRM_Core_Form {

  private $_materialId = NULL;
  private $_languagesList = array();
  private $_materialCategoryList = array();

  /**
   * Method to build the form
   */
  public function buildQuickForm() {
    // no action if delete, enable or disable
    if ($this->_action == CRM_Core_Action::DELETE || $this->_action == CRM_Core_Action::DISABLE || $this->_action == CRM_Core_Action::ENABLE) {
      return;
    }
    //readonly elements when view action
    if ($this->_action == CRM_Core_Action::VIEW) {
      $this->addViewElements();
    }
    else {
      $this->addElements();
    }
    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE),
      array('type' => 'cancel', 'name' => ts('Cancel')),
      ));
    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * Method to add form elements for view
   */
  private function addViewElements() {
    $this->add('text', 'title', ts('Title'), array('readonly' => 'readonly'), FALSE);
    $this->add('wysiwyg', 'description', ts('Description'), array(
      'readonly' => 'readonly',
      'rows' => 4,
      'cols' => 80,
        ), FALSE);
    $this->addMoney('price', ts('Price'), FALSE, array('readonly' => 'readonly'), FALSE);
    $this->add('text', 'material_category_id', ts('Category'), array('readonly' => 'readonly'));
    $this->add('text', 'creation_year', ts('Creation Year'), array('readonly' => 'readonly'), FALSE);
    $this->addYesNo('can_be_ordered', ts('Can be ordered?'), FALSE, FALSE, array('readonly' => 'readonly'));
    $this->add('text', 'language_id', ts('Language'), array('readonly' => 'readonly'), FALSE);
    $this->add('text', 'number_of_pages', ts('Number of Pages'), array('readonly' => 'readonly'), FALSE);
    $this->add('text', 'download_link', ts('Download Link'), array('readonly' => 'readonly'), FALSE);
  }

  /**
   * Method to add form elements (for update and add)
   */
  private function addElements() {
    $this->add('text', 'title', ts('Title'), array(), TRUE);
    $this->add('wysiwyg', 'description', ts('Description'), array('rows' => 4, 'cols' => 80), FALSE);
    $this->addMoney('price', ts('Price'), TRUE, array(), FALSE);
    $this->add('select', 'material_category_id', ts('Category'), $this->_materialCategoryList, TRUE);
    $this->addYesNo('can_be_ordered', ts('Can be ordered?'), TRUE, TRUE);
    $this->add('text', 'creation_year', ts('Creation Year'), array(), TRUE);
    $this->add('select', 'language_id', ts('Language'), $this->_languagesList, TRUE);
    $this->add('text', 'number_of_pages', ts('Number of Pages'), FALSE);
    $this->add('text', 'download_link', ts('Download Link'), FALSE);
  }

  /**
   * Method to prepare form
   */
  public function preProcess() {
    // retrieve material id from request
    $requestValues = CRM_Utils_Request::exportValues();
    if (isset($requestValues['mid']) && !empty($requestValues['mid'])) {
      $this->_materialId = $requestValues['mid'];
    }
    $this->setLanguagesList();
    $this->setMaterialCategoryList();
  }

  /**
   * Method to set the default values in view and update mode
   * @return array
   */
  public function setDefaultValues() {
    $defaults = array();
    $defaults['material_id'] = $this->_materialId;
    if ($this->_action == CRM_Core_Action::UPDATE || $this->_action == CRM_Core_Action::VIEW) {
      try {
        $material = civicrm_api3('FzfdMaterial', 'getsingle', array('id' => $this->_materialId));
        foreach ($material as $key => $value) {
          $defaults[$key] = $value;
        }
        // show labels for language and material_category when view mode
        if ($this->_action == CRM_Core_Action::VIEW) {
          $defaults['language_id'] = CRM_Materialbestellung_Utils::getLanguageWithId($material['language_id']);
          $defaults['material_category_id'] = CRM_Materialbestellung_Utils::getMaterialCategoryWithId($material['material_category_id']);
        }
      }
      catch (CiviCRM_API3_Exception $ex) {
      }
    }
    return $defaults;
  }

  /**
   * Method to get the material category select list
   */
  private function setMaterialCategoryList() {
    $this->_materialCategoryList[0] = '- select -';
    try {
      $materialCategories = civicrm_api3('OptionValue', 'get', array(
        'option_group_id' => 'fzfd_material_category',
        'is_active' => 1,
        'options' => array('limit' => 0),
      ));
      foreach ($materialCategories['values'] as $materialCategory) {
        $this->_materialCategoryList[$materialCategory['value']] = $materialCategory['label'];
      }
      asort($this->_materialCategoryList);
    }
    catch (CiviCRM_API3_Exception $ex) {
    }
  }

  /**
   * Method to get the languages select list
   */
  private function setLanguagesList() {
    $this->_languagesList[0] = '- select -';
    try {
      $languages = civicrm_api3('OptionValue', 'get', array(
        'option_group_id' => 'languages',
        'is_active' => 1,
        'options' => array('limit' => 0),
      ));
      foreach ($languages['values'] as $language) {
        $this->_languagesList[$language['name']] = $language['label'];
      }
      asort($this->_languagesList);
    }
    catch (CiviCRM_API3_Exception $ex) {
    }
  }

  /**
   * Method to process the form once it is submitted
   */
  public function postProcess() {
    switch ($this->_action) {
      case CRM_Core_Action::DELETE:
        $this->deleteMaterialById();
        CRM_Core_Session::setStatus(' Material in database ' . ts('deleted'), ' Material ' . ts('deleted'), 'success');
        CRM_Core_Session::singleton()->pushUserContext(CRM_Utils_System::url('civicrm/fzfdmaterial/page/material', 'reset=1&action=browse', TRUE));
        break;

      case CRM_Core_Action::DISABLE:
        $this->disableMaterialById();
        CRM_Core_Session::setStatus(' Material in database ' . ts('disabled'), ' Material ' . ts('disabled'), 'success');
        CRM_Core_Session::singleton()->pushUserContext(CRM_Utils_System::url('civicrm/fzfdmaterial/page/material', 'reset=1&action=browse', TRUE));
        break;

      case CRM_Core_Action::ENABLE:
        $this->enableMaterialById();
        CRM_Core_Session::setStatus(' Material in database ' . ts('enabled'), ' Material ' . ts('enabled'), 'success');
        CRM_Core_Session::singleton()->pushUserContext(CRM_Utils_System::url('civicrm/fzfdmaterial/page/material', 'reset=1&action=browse', TRUE));
        break;

      default:
        $values = $this->exportValues();
        $ignores = array('entryURL', 'qfKey');
        foreach ($values as $key => $data) {
          if (substr($key, 0, 1) != "_" && !in_array($key, $ignores)) {
            $params[$key] = $data;
          }
        }
        if ($this->_action != CRM_Core_Action::ADD) {
          $params['id'] = $this->_materialId;
        }
        try {
          civicrm_api3('FzfdMaterial', 'create', $params);
          CRM_Core_Session::setStatus(' Material in database ' . ts('saved'), ' Material ' . ts('Saved'), 'success');
        }
        catch (CiviCRM_API3_Exception $ex) {
          CRM_Core_Session::setStatus(ts('Error saving') . ' Material in database', ts('NOT Saved') . ' Material', 'error');
          CRM_Core_Error::debug_log_message('Could not save material in ' . __METHOD__ . 'error from API FzfdMaterial create: ' . $ex->getMessage());
        }
        parent::postProcess();
        break;
    }
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  /**
   * Overridden parent method to set validation rules
   */
  public function addRules() {
    if ($this->_action == CRM_Core_Action::ADD || $this->_action == CRM_Core_Action::UPDATE) {
      $this->addFormRule(array('CRM_Materialbestellung_Form_Material', 'validateCreationYear'));
      $this->addFormRule(array('CRM_Materialbestellung_Form_Material', 'validateNumberOfPages'));
      $this->addFormRule(array('CRM_Materialbestellung_Form_Material', 'validateDownloadLink'));
    }
  }

  /**
   * Method to validate creation year
   *
   * @param $fields
   * @return bool|array
   */
  public static function validateCreationYear($fields) {
    if (isset($fields['creation_year']) && !empty($fields['creation_year'])) {
      if (!is_numeric($fields['creation_year'])) {
        $errors['creation_year'] = ts('Creation year can only hold 4 numbers (yyyy, for example 2012)');
        return $errors;
      }
      $testYear = (int) $fields['creation_year'];
      $maxDate = new DateTime();
      $interval = new DateInterval('P5Y');
      $maxDate->add($interval);
      $maxYear = (int) $maxDate->format('Y');
      if ($testYear < 1900 || $testYear > $maxYear) {
        $errors['creation_year'] = ts('Creation year can only be between 1900 and 5 years after today');
        return $errors;
      }
    }
    return TRUE;
  }

  /**
   * Method to validate number of pages
   *
   * @param $fields
   * @return bool|array
   */
  public static function validateNumberOfPages($fields) {
    if (isset($fields['number_of_pages']) && !empty($fields['number_of_pages'])) {
      if (!is_numeric($fields['number_of_pages'])) {
        $errors['number_of_pages'] = ts('Number of pages can only numbers!');
        return $errors;
      }
    }
    return TRUE;
  }

  /**
   * Method to validate download link
   *
   * @param $fields
   * @return bool|array
   */
  public static function validateDownloadLink($fields) {
    if (isset($fields['download_link']) && !empty($fields['download_link'])) {
      if (!filter_var($fields['download_link'], FILTER_VALIDATE_URL)) {
        $errors['download_link'] = ts('Download link has to be a valid URL! (for example http://www.example.org');
        return $errors;
      }
    }
    return TRUE;
  }

  /**
   * Method to delete id and return to page
   *
   */
  private function deleteMaterialById() {
    try {
      civicrm_api3('FzfdMaterial', 'delete', array('id' => $this->_materialId));
      CRM_Core_Session::setStatus(ts('Deleted Material with id ' . $this->_materialId, 'Material deleted', 'success'));
    }
    catch (CiviCRM_API3_Exception $ex) {
      CRM_Core_Session::setStatus(ts('Could not delete Material with id ' . $this->_materialId, 'Material NOT deleted', 'error'));
    }
    // todo redirect in new 4.7 compliant way!
    CRM_Utils_System::civiExit();
  }

  /**
   * Method to disable material and return to page
   */
  private function disableMaterialById() {
    try {
      civicrm_api3('FzfdMaterial', 'create', array(
        'id' => $this->_materialId,
        'is_active' => 0,
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
    }
    CRM_Utils_System::civiExit();
  }

  /**
   * Method to ensable material and return to page
   */
  private function enableMaterialById() {
    try {
      civicrm_api3('FzfdMaterial', 'create', array(
        'id' => $this->_materialId,
        'is_active' => 1,
      ));
    }
    catch (CiviCRM_API3_Exception $ex) {
    }
  }

}
