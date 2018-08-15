<?php

/**
 * FzfdMaterial.Delete API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_fzfd_material_Delete_spec(&$spec) {
  $spec['id'] = array(
    'name' => 'id',
    'title' => 'id',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  );
}

/**
 * FzfdMaterial.Delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_fzfd_material_Delete($params) {
  return civicrm_api3_create_success(CRM_Materialbestellung_BAO_Material::deleteWithId($params['id']), $params, 'FzfdMaterial', 'Delete');
}
