<?php

namespace Drupal\islandora_fits\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Module administration form.
 */
class Admin extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'islandora_fits_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form_state->loadInclude('islandora_fits', 'inc', 'includes/admin.form');
    if ($form_state->getTriggeringElement()) {
      // Textfield AJAX callback.
      if ($form_state->getTriggeringElement() == 'islandora_fits_path_textfield') {
        $fits_path = $form_state->getUserInput();
      }
    }
    else {
      $fits_path = $this->config('islandora_fits.settings')->get('islandora_fits_executable_path');
    }
    $conf_message = islandora_fits_get_path_message($fits_path);
    $form['islandora_fits_do_derivatives'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Run Islandora FITS derivatives locally'),
      '#name' => 'islandora_fits_do_derivatives',
      '#description' => $this->t('If checked (default), this server will use FITS to create a technical metadata datastream (the datastream id of which is defined below). Requires a local installation of the FITS tool.'),
      '#return_value' => 1,
      '#default_value' => $this->config('islandora_fits.settings')->get('islandora_fits_do_derivatives'),
    ];
    $form['islandora_fits_wrapper'] = [
      '#prefix' => '<div id="islandora_fits_wrapper">',
      '#suffix' => '</div>',
    ];
    $form['islandora_fits_wrapper']['islandora_fits_executable_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('System path to FITS processor'),
      '#name' => 'islandora_fits_path_textfield',
      '#description' => $this->t('The complete path and filename of the File Information Tool Set (FITS) binary, or the command to run if in the path.'),
      '#default_value' => $fits_path,
      '#ajax' => [
        'callback' => 'islandora_fits_path_ajax',
        'wrapper' => 'islandora_fits_wrapper',
      ],
      '#states' => [
        'visible' => [
          ':input[name="islandora_fits_do_derivatives"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];
    $form['islandora_fits_wrapper']['islandora_fits_path_check'] = [
      '#type' => 'item',
      '#markup' => $conf_message,
      '#states' => [
        'visible' => [
          ':input[name="islandora_fits_do_derivatives"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];
    // Add form options for what the datastream is called.
    $form['islandora_fits_techmd_dsid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Technical metadata datastream ID'),
      '#description' => $this->t("The DSID to use when creating or displaying an object's technical metadata."),
      '#default_value' => $this->config('islandora_fits.settings')->get('islandora_fits_techmd_dsid'),
    ];
    $form['buttons']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Configuration'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Only validate the FITS path if doing derivatives.
    if ($form_state->getValue('islandora_fits_do_derivatives')) {
      $errors = islandora_fits_path_check($form_state->getValue('islandora_fits_executable_path'));
      if (!empty($errors)) {
        $form_state->setErrorByName('islandora_fits_executable_path', "FITS path is not valid. Provide a valid path or disable FITS derivatives.");
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('islandora_fits.settings');

    $config->set('islandora_fits_executable_path', $form_state->getValue('islandora_fits_executable_path'));
    $config->set('islandora_fits_techmd_dsid', $form_state->getValue('islandora_fits_techmd_dsid'));
    $config->set('islandora_fits_do_derivatives', $form_state->getValue('islandora_fits_do_derivatives'));

    $config->save();
  }

}
