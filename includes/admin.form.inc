<?php

/**
 * @file
 * Admin form helpers for the Islandora fits module.
 */

/**
 * Ajax handler for admin form.
 */
function islandora_fits_path_ajax($form, $form_state) {
  return $form['islandora_fits_wrapper'];
}

/**
 * Gets a user-friendly message to show whether the path is valid.
 */
function islandora_fits_get_path_message($fits_path) {
  $errors = islandora_fits_path_check($fits_path);
  if (empty($errors)) {
    // @FIXME
// url() expects a route name or an external URI.
// $confirmation_message = '<img src="' . url('misc/watchdog-ok.png') . '"/> ' . t('Executable found at @fitspath', array('@fitspath' => $fits_path));

  }
  else {
    // @FIXME
// url() expects a route name or an external URI.
// $confirmation_message = '<img src="' . url('misc/watchdog-error.png') . '"/> ' . implode('<br/>', $errors);

  }
  return $confirmation_message;
}

/**
 * Checks fits path for an executable.
 *
 * @param string $path
 *   Path to a desired executable, or executable string.
 *
 * @return array
 *   Errors encountered executing the file. If it returns an empty array,
 *   then the file was executable.
 */
function islandora_fits_path_check($path) {
  module_load_include('inc', 'islandora', 'includes/utilities');
  $allowed_paths = [
    'fits.sh',
    'fits',
  ];
  $errors = [];
  if (!in_array($path, $allowed_paths)) {
    // Check whether the given file exists.
    if (!is_file($path)) {
      $errors[] = t('The specified file path %file does not exist.', ['%file' => $path]);
    }
    // If it exists, check if it's executable.
    elseif (!is_executable($path)) {
      $errors[] = t('The specified file path %file is not executable.', ['%file' => $path]);
    }
  }
  // Ensure that if this is a Windows server, $file isn't pointing to a
  // shell script (that combo may cause the page to hang).
  if ((islandora_deployed_on_windows()) && (strpos($path, ".sh") == FALSE)) {
    $errors[] = t('Islandora appears to be running on Windows, but the path given ends in .sh.');
  }
  // If we haven't had errors so far, try executing it with the -v option.
  if (empty($errors)) {
    $command = $path . ' -v';
    exec($command, $output, $return_value);
    if ($return_value !== 0) {
      $errors[] = t('The command %file is not a valid FITS executable.', ['%file' => $path]);
    }
  }
  return $errors;
}
