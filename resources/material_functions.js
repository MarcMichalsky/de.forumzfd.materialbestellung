// function to enable material
function enOrDisableMaterial(enOrDisable, parentId) {
  console.log('enOrDis is ' + enOrDisable + 'met parentId ' + parentId);
  var materials = parentId.split("material-");
  if ($materials[1]) {
    CRM.api3('FzfdMaterial', 'create', {"id": materials[1], "is_active":enOrDisable})
      .done(function(result) {
        if(result['is_error'] === 1) {
          if (enOrDisable === 1) {
            CRM.alert('Material could not be enabled', 'NOT Enabled', 'error');
          } else {
            CRM.alert('Material could not be disabled', 'NOT Disabled', 'error');
          }
        } else {
          if (enOrDisable === 1) {
            CRM.alert('Material enabled', 'Enabled', 'success');
          } else {
            CRM.alert('Material disabled', 'Disabled', 'success');
          }
        }
      });
  }
}
// function to delete material
function deleteMaterial(parentId) {
  var materials = parentId.split("material-");
  if ($materials[1]) {
    CRM.api3('FzfdMaterial', 'delete', {"id": materials[1]})
      .done(function(result) {
        if(result['is_error'] === 1) {
          CRM.alert('Material could not be deleted: ' + result['error_message'], 'NOT Deleted', 'error');
        } else {
          CRM.alert('Material deleted', 'Deleted', 'success');
        }
      });
  }
}
