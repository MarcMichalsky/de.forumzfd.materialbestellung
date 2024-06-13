<?php

/**
 * Collection of upgrade steps.
 */
class CRM_Materialbestellung_Upgrader extends CRM_Extension_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Execute once extension is installed
   */
  public function install() {
    $this->executeSqlFile('sql/createMaterialTable.sql');
    $this->executeCustomDataFile('xml/Rechnungsadresse.xml');
  }

  /**
   * Create activity type and option group if not exists
   */
  public function postInstall() {
    CRM_Materialbestellung_Utils::createActivityTypesFromJson();
    CRM_Materialbestellung_Utils::createOptionGroupsFromJson();
  }

  /**
   * Disable activity type and option groups on uninstall.
   * Decided not to remove data so activity types and option groups can not be removed either.
   *
   */
  public function uninstall() {
    CRM_Materialbestellung_Utils::disableActivityTypesFromJson();
    CRM_Materialbestellung_Utils::disableOptionGroupsFromJson();
  }

  /**
   * Upgrade to add column can_be_ordered and remove subtitle and short_description
   */
  public function upgrade_1010() {
    // add column can_be_ordered if required
    $tableName = 'forumzfd_material';
    if (!CRM_Core_DAO::checkFieldExists($tableName, 'can_be_ordered')) {
      $addQuery = 'ALTER TABLE ' . $tableName . ' ADD can_be_ordered TINYINT(3) UNSIGNED DEFAULT 0';
      CRM_Core_DAO::executeQuery($addQuery);
    }
    // remove subtitle and short_description
    if (CRM_Core_DAO::checkFieldExists($tableName, 'subtitle')) {
      $removeQuery = 'ALTER TABLE ' . $tableName . ' DROP COLUMN subtitle';
      CRM_Core_DAO::executeQuery($removeQuery);
    }
    if (CRM_Core_DAO::checkFieldExists($tableName, 'short_description')) {
      $removeQuery = 'ALTER TABLE ' . $tableName . ' DROP COLUMN short_description';
      CRM_Core_DAO::executeQuery($removeQuery);
    }
    return TRUE;
  }

  /**
   * Add custom group for rechnungsadresse if not exists
   */
  public function upgrade_1020() {
    if (!CRM_Core_DAO::checkTableExists('civicrm_value_rechnungsadresse_material_bestellung')) {
      $this->executeCustomDataFile('xml/Rechnungsadresse.xml');
    }
    return TRUE;
  }

  /**
   * Example: Run a simple query when a module is enabled.
   *
  public function enable() {
    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a simple query when a module is disabled.
   *
  public function disable() {
    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  }


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4202() {
    $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

    $this->addTask(ts('Process first step'), 'processPart1', $arg1, $arg2);
    $this->addTask(ts('Process second step'), 'processPart2', $arg3, $arg4);
    $this->addTask(ts('Process second step'), 'processPart3', $arg5);
    return TRUE;
  }
  public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  public function processPart3($arg5) { sleep(10); return TRUE; }
  // */


  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4203() {
    $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

    $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
    $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
    for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
      $endId = $startId + self::BATCH_SIZE - 1;
      $title = ts('Upgrade Batch (%1 => %2)', array(
        1 => $startId,
        2 => $endId,
      ));
      $sql = '
        UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
        WHERE id BETWEEN %1 and %2
      ';
      $params = array(
        1 => array($startId, 'Integer'),
        2 => array($endId, 'Integer'),
      );
      $this->addTask($title, 'executeSql', $sql, $params);
    }
    return TRUE;
  } // */

}
