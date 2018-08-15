<?php

/**
 * FzfdMaterial.Order API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_fzfd_material_Order_spec(&$spec) {
  $spec['material_id'] = array(
    'name' => 'material_id',
    'title' => 'material_id',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  );
  $spec['contact_id'] = array(
    'name' => 'contact_id',
    'title' => 'contact_id',
    'type' => CRM_Utils_Type::T_INT,
  );
  $spec['quantity'] = array(
    'name' => 'quantity',
    'title' => 'quantity',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  );
  $spec['address'] = array(
    'name' => 'address',
    'title' => 'address (containing shipping and billing)',
    'description' => 'array with shipping and billing address data',
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['source'] = array(
    'name' => 'source',
    'title' => 'source',
    'type' => CRM_Utils_Type::T_STRING,
  );
}

/**
 * FzfdMaterial.Order API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 */
function civicrm_api3_fzfd_material_Order($params) {
  $activity = CRM_Materialbestellung_BAO_Material::addOrder($params);
  $returnValues = array();
  if ($activity) {
    $returnValues = array(
      'is_error' => '0',
      'version' => '3',
      'count' => '1',
    );
  }
  // return doi_id and doi_token if in params
  if (isset($params['doi_id'])) {
    $returnValues['doi_id'] = $params['doi_id'];
  }
  if (isset($params['doi_token'])) {
    $returnValues['doi_token'] = $params['doi_token'];
  }
  return $returnValues;
}
