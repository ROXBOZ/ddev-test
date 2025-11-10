<?php

namespace Drupal\event_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilderInterface;

/**
 * Provides a block that displays the Event Add Form.
 *
 * @Block(
 *   id = "event_add_block",
 *   admin_label = @Translation("Add Event Form"),
 *   category = @Translation("Custom"),
 * )
 */
class EventAddBlock extends BlockBase implements ContainerFactoryPluginInterface
{

    /**
     * The form builder service.
     *
     * @var \Drupal\Core\Form\FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * Constructor - receives dependencies.
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        FormBuilderInterface $form_builder
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->formBuilder = $form_builder;
    }

    /**
     * Tells Drupal which services we need.
     */
    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition
    ) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('form_builder')
        );
    }

    /**
     * Build the block content - return our form.
     */
    public function build()
    {
        return $this->formBuilder->getForm('Drupal\event_form\Form\EventAddForm');
    }

    /**
     * Don't cache this block (forms need fresh CSRF tokens).
     */
    public function getCacheMaxAge()
    {
        return 0;
    }
}
