<?php

namespace Drupal\roxboz_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Button Component' Block.
 *
 * @Block(
 *   id = "button_component_block",
 *   admin_label = @Translation("Button Component"),
 *   category = @Translation("Roxboz Components"),
 * )
 */
class ButtonComponentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'heading' => $this->t('Button Components Demo'),
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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['heading'] = $form_state->getValue('heading');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'button_component_block',
      '#heading' => $this->configuration['heading'],
    ];
  }

}
