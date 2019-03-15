<?php

namespace Drupal\islandora_fits\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

use AbstractObject;

/**
 * Default controller for the islandora_fits module.
 */
class DefaultController extends ControllerBase {

  /**
   * Access callback for viewing technical metadata.
   */
  public static function metadataAccess($object, AccountInterface $account) {
    $object = islandora_object_load($object);
    $perm = islandora_fits_metadata_access($object);
    return AccessResult::allowedIf($perm)
      ->addCacheableDependency($object)
      ->cachePerPermissions();
  }

  /**
   * Technical metadata display.
   */
  public static function metadataDisplay(AbstractObject $object) {
    return islandora_fits_metadata_display($object);
  }

}
