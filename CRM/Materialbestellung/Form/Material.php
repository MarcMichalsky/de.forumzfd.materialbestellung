<?php

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Materialbestellung_Form_Material extends CRM_Core_Form {

  private $_languagesList = array();
  private $_materialCategoryList = array();

  /**
   * Method to build the form
   */
  public function buildQuickForm() {

    $this->add('text', 'title', ts('Title'), TRUE);
    $this->add('wysiwyg', 'description', ts('Description'), array('rows' => 4, 'cols' => 80), FALSE);
    $this->addMoney('price', ts('Price'), TRUE);
    $this->add('select', 'material_category_id', ts('Category'), $this->_materialCategoryList,TRUE);
    $this->add('text', 'short_description', ts('Short Description'), FALSE);
    $this->add('text', 'subtitle', ts('Subtitle'), FALSE);
    $this->add('text', 'creation_year', ts('Creation Year'), FALSE);
    $this->add('select', 'language_id', ts('Language'), $this->_languagesList,TRUE);
    $this->add('text', 'number_of_pages', ts('Number of Pages'), FALSE);
    $this->add('text', 'download_link', ts('Download Link'), FALSE);
    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => true,),
      array('type' => 'cancel', 'name' => ts('Cancel'),),));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * Method to prepare form
   */
  public function preProcess() {
    $this->setLanguagesList();
    $this->setMaterialCategoryList();
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
        'options' => array('limit' => 0,),
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
        'options' => array('limit' => 0,),
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
    $values = $this->exportValues();
    $options = $this->getColorOptions();
    CRM_Core_Session::setStatus(ts('You picked color "%1"', array(
      1 => $options[$values['favorite_color']],
    )));
    parent::postProcess();
  }

  public function getColorOptions() {
    $options = array(
      '' => ts('- select -'),
      '#f00' => ts('Red'),
      '#0f0' => ts('Green'),
      '#00f' => ts('Blue'),
      '#f0f' => ts('Purple'),
    );
    foreach (array('1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e') as $f) {
      $options["#{$f}{$f}{$f}"] = ts('Grey (%1)', array(1 => $f));
    }
    return $options;
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

}
