<?php

/**
 * FzfdMaterial.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_fzfd_material_Create_spec(&$spec) {
  $spec['id'] = array(
    'name' => 'id',
    'title' => 'id',
    'type' => CRM_Utils_Type::T_INT
  );
  $spec['title'] = array(
    'name' => 'title',
    'title' => 'title',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_STRING
  );
  $spec['description'] = array(
    'name' => 'description',
    'title' => 'description',
    'type' => CRM_Utils_Type::T_TEXT,
  );
  $spec['is_active'] = array(
    'name' => 'is_active',
    'title' => 'is_active',
    'type' => CRM_Utils_Type::T_BOOLEAN,
  );
  $spec['price'] = array(
    'name' => 'price',
    'title' => 'price',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_MONEY,
  );
  $spec['material_category_id'] = array(
    'name' => 'material_category_id',
    'title' => 'material_category_id',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['short_description'] = array(
    'name' => 'short_description',
    'title' => 'short_description',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['subtitle'] = array(
    'name' => 'subtitle',
    'title' => 'subtitle',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['creation_year'] = array(
    'name' => 'creation_year',
    'title' => 'creation_year',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['language_id'] = array(
    'name' => 'language_id',
    'title' => 'language_id',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['number_of_pages'] = array(
    'name' => 'number_of_pages',
    'title' => 'number_of_pages',
    'type' => CRM_Utils_Type::T_INT,
  );
  $spec['download_link'] = array(
    'name' => 'download_link',
    'title' => 'download_link',
    'type' => CRM_Utils_Type::T_URL,
  );
}

/**
 * FzfdMaterial.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_fzfd_material_Create($params) {
  return civicrm_api3_create_success(CRM_Materialbestellung_BAO_Material::add($params), $params, 'FzfdMaterial', 'Create');
}
