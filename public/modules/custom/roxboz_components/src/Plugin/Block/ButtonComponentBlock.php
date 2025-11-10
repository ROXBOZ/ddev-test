<?php

namespace Drupal\custom_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Button Component' Block.
 *
 * @Block(
 *   id = "button_component_block",
 *   admin_label = @Translation("Button Component"),
 *   category = @Translation("custom Components"),
 * )
 */
class ButtonComponentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'heading' => $this->t('Button Components Demo'),
      'description' => $this->t('This is a description of the button components.'),
      'image' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['heading'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Heading'),
      '#description' => $this->t('Enter the heading text for the button component.'),
      '#default_value' => $this->configuration['heading'],
      '#required' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t('Enter the description text for the button component.'),
      '#default_value' => $this->configuration['description'],
      '#required' => FALSE,
    ];

    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Image'),
      '#description' => $this->t('Upload an image for the button component.'),
      '#default_value' => $this->configuration['image'] ? [$this->configuration['image']] : NULL,
      '#upload_location' => 'public://button-component/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg gif svg'],
        'file_validate_size' => [5242880], // 5MB
      ],
      '#required' => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['heading'] = $form_state->getValue('heading');
    $this->configuration['description'] = $form_state->getValue('description');
    
    // Handle image upload.
    $image = $form_state->getValue('image');
    if (!empty($image[0])) {
      $this->configuration['image'] = $image[0];
      // Mark file as permanent.
      \Drupal::service('file.usage')->add(
        \Drupal::entityTypeManager()->getStorage('file')->load($image[0]),
        'custom_components',
        'block',
        1
      );
    }
    else {
      $this->configuration['image'] = NULL;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#theme' => 'button_component_block',
      '#heading' => $this->configuration['heading'],
      '#description' => $this->configuration['description'],
      '#image' => NULL,
    ];

    // Render the image if available.
    if (!empty($this->configuration['image'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($this->configuration['image']);
      if ($file) {
        $uri = $file->uri->value;
        $build['#image'] = [
          '#theme' => 'image',
          '#uri' => \Drupal::service('file_url_generator')->generateAbsoluteString($uri),
          '#alt' => $this->configuration['heading'],
        ];
      }
    }

    return $build;
  }

}
