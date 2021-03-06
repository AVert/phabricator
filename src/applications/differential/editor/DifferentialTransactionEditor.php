<?php

final class DifferentialTransactionEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhabricatorTransactions::TYPE_EDGE;
    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDIT_POLICY;

/*

    $types[] = DifferentialTransaction::TYPE_INLINE;
    $types[] = DifferentialTransaction::TYPE_UPDATE;
    $types[] = DifferentialTransaction::TYPE_ACTION;
*/

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
        return $object->getViewPolicy();
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
        return $object->getEditPolicy();
    }

    return parent::getCustomTransactionOldValue($object, $xaction);
  }

  protected function getCustomTransactionNewValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
        return $xaction->getNewValue();
    }

    return parent::getCustomTransactionNewValue($object, $xaction);
  }

  protected function applyCustomInternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
        $object->setViewPolicy($xaction->getNewValue());
        return;
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
        $object->setEditPolicy($xaction->getNewValue());
        return;
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
      case PhabricatorTransactions::TYPE_EDGE:
        // TODO: When removing reviewers, we may be able to move the revision
        // to "Accepted".
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
        return;
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
      case PhabricatorTransactions::TYPE_EDGE:
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function validateTransaction(
    PhabricatorLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    switch ($type) {
    }

    return $errors;
  }

  protected function requireCapabilities(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
    }

    return parent::requireCapabilities($object, $xaction);
  }

  protected function supportsSearch() {
    return true;
  }

  protected function extractFilePHIDsFromCustomTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
    }

    return parent::extractFilePHIDsFromCustomTransaction($object, $xaction);
  }

}
