<?php

namespace Drupal\event_delete\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\block_content\Entity\BlockContent;

/**
 * Confirmation form for deleting an event.
 */
class EventDeleteConfirmForm extends ConfirmFormBase
{

    /**
     * The event to delete.
     *
     * @var \Drupal\block_content\Entity\BlockContent
     */
    protected $event;

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'event_delete_confirm_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, BlockContent $block_content = NULL)
    {
        $this->event = $block_content;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion()
    {
        $title = $this->event->get('field_title')->value;
        $day = $this->event->get('field_day')->value;
        return $this->t('Are you sure you want to delete the event "@title" on @day?', [
            '@title' => $title,
            '@day' => ucfirst($day),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->t('This action cannot be undone.');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText()
    {
        return $this->t('Delete');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return $this->t('Cancel');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelUrl()
    {
        return new Url('<front>');
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Get event details for the success message
        $title = $this->event->get('field_title')->value;
        $day = $this->event->get('field_day')->value;

        // Actually delete the event
        $this->event->delete();

        // Show success message
        $this->messenger()->addStatus($this->t('The event "@title" on @day has been deleted.', [
            '@title' => $title,
            '@day' => ucfirst($day),
        ]));

        // Redirect to homepage
        $form_state->setRedirectUrl($this->getCancelUrl());
    }
}
