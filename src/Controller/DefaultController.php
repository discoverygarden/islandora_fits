<?php /**
 * @file
 * Contains \Drupal\islandora_fits\Controller\DefaultController.
 */

namespace Drupal\islandora_fits\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Default controller for the islandora_fits module.
 */
class DefaultController extends ControllerBase {

  public function islandora_fits_metadata_access($object, AccountInterface $account) {
    return islandora_datastream_access('view technical metadata', $object[\Drupal::config('islandora_fits.settings')->get('islandora_fits_techmd_dsid')]);
  }

  public function islandora_fits_metadata_display($object) {
    module_load_include('inc', 'islandora', 'includes/breadcrumb');
    drupal_set_breadcrumb(islandora_get_breadcrumbs($object));
    // @FIXME
    // theme() has been renamed to _theme() and should NEVER be called directly.
    // Calling _theme() directly can alter the expected output and potentially
    // introduce security issues (see https://www.drupal.org/node/2195739). You
    // should use renderable arrays instead.
    //
    //
    // @see https://www.drupal.org/node/2195739
    // $output = theme('islandora_fits_metadata_display', array('islandora_object' => $object));

    return $output;
  }

}
