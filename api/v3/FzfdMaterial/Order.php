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
  $spec['prefix_id'] = array(
    'name' => 'prefix_id',
    'title' => 'prefix_id',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['first_name'] = array(
    'name' => 'first_name',
    'title' => 'first_name',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['last_name'] = array(
    'name' => 'last_name',
    'title' => 'last_name',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['email'] = array(
    'name' => 'email',
    'title' => 'email',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['street_address'] = array(
    'name' => 'street_address',
    'title' => 'street_address',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['postal_code'] = array(
    'name' => 'postal_code',
    'title' => 'postal_code',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['city'] = array(
    'name' => 'city',
    'title' => 'city',
    'type' => CRM_Utils_Type::T_STRING,
  );
  $spec['country_id'] = array(
    'name' => 'country_id',
    'title' => 'country_id',
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
 * @throws API_Exception
 */
function civicrm_api3_fzfd_material_Order($params) {
  return civicrm_api3_create_success(CRM_Materialbestellung_BAO_Material::addOrder($params), $params, 'FzfdMaterial', 'Order');
}
