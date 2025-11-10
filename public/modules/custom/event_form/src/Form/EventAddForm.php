<?php

namespace Drupal\event_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\block_content\Entity\BlockContent;

/**
 * Form to add new events to the calendar.
 */
class EventAddForm extends FormBase
{

    /**
     * Returns a unique ID for this form.
     */
    public function getFormId()
    {
        return 'event_add_form';
    }

    /**
     * Build the form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['description'] = [
            '#markup' => '<p><strong>Add a new course to the calendar</strong></p>',
        ];

        // Event Title - a text input field
        $form['title'] = [
            '#type' => 'select',
            '#title' => $this->t('Event Title'),
            '#options' => [
                'Kickboxen' => $this->t('Kickboxen'),
                'Wendo' => $this->t('Wendo'),
                'Thaiboxen' => $this->t('Thaiboxen'),
                'Kondi' => $this->t('Kondi'),
                'Thai Chi' => $this->t('Thai Chi'),
            ],
            '#required' => TRUE,
            '#description' => $this->t('Select a course type'),
        ];

        // Day of the Week - a dropdown
        $form['day'] = [
            '#type' => 'select',
            '#title' => $this->t('Day of Week'),
            '#options' => [
                'monday' => $this->t('Monday'),
                'tuesday' => $this->t('Tuesday'),
                'wednesday' => $this->t('Wednesday'),
                'thursday' => $this->t('Thursday'),
                'friday' => $this->t('Friday'),
            ],
            '#required' => TRUE,
        ];

        // Starting Date & Time
        // Default: January 1st of current year at 15:00
        $form['starting_time'] = [
            '#type' => 'datetime',
            '#title' => $this->t('Starting Date & Time'),
            '#required' => TRUE,
            '#default_value' => new \DateTime(date('Y') . '-01-01 15:00:00'),
        ];

        // Ending Date & Time
        // Default: December 31st of current year at 18:00
        $form['ending_time'] = [
            '#type' => 'datetime',
            '#title' => $this->t('Ending Date & Time'),
            '#required' => TRUE,
            '#default_value' => new \DateTime(date('Y') . '-12-31 18:00:00'),
        ];

        // Trainer Name
        $form['trainer'] = [
            '#type' => 'select',
            '#title' => $this->t('Trainer Name'),
            '#options' => [
                'dean' => $this->t('Dean'),
                'kim' => $this->t('Kim'),
                'angela' => $this->t('Angela'),
                'claudia' => $this->t('Claudia'),
                'ruth' => $this->t('Ruth'),
            ],
            '#required' => TRUE,
        ];


        // For Kids checkbox
        $form['for_kids'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('This is a kids/teens course'),
            '#default_value' => FALSE,
        ];


        // Minimum Age - only visible if "for_kids" is checked
        $form['minimum_age'] = [
            '#type' => 'number',
            '#title' => $this->t('Minimum Age'),
            '#min' => 1,
            '#max' => 17,
            '#default_value' => 6,
            '#states' => [
                'visible' => [
                    ':input[name="for_kids"]' => ['checked' => TRUE],
                ],
                'required' => [
                    ':input[name="for_kids"]' => ['checked' => TRUE],
                ],
            ],
        ];

        // Maximum Age - only visible if "for_kids" is checked
        $form['maximum_age'] = [
            '#type' => 'number',
            '#title' => $this->t('Maximum Age'),
            '#min' => 2,
            '#max' => 18,
            '#default_value' => 17,
            '#states' => [
                'visible' => [
                    ':input[name="for_kids"]' => ['checked' => TRUE],
                ],
                'required' => [
                    ':input[name="for_kids"]' => ['checked' => TRUE],
                ],
            ],
        ];

        // Open to Beginners checkbox
        $form['open_to_beginners'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Open to beginners'),
            '#default_value' => TRUE,
        ];

        // Submit button
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Add Event to Calendar'),
            '#button_type' => 'primary',
        ];

        return $form;
    }

    /**
     * Validate the form before submitting.
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // Only validate ages if "for_kids" is checked
        $for_kids = $form_state->getValue('for_kids');

        if ($for_kids) {
            $min_age = $form_state->getValue('minimum_age');
            $max_age = $form_state->getValue('maximum_age');

            // Check if both ages are provided
            if ($min_age && $max_age && $min_age > $max_age) {
                $form_state->setErrorByName(
                    'minimum_age',
                    $this->t('Minimum age cannot be greater than maximum age.')
                );
            }
        }

        // Always validate dates
        $start = $form_state->getValue('starting_time');
        $end = $form_state->getValue('ending_time');

        if ($start && $end && $end < $start) {
            $form_state->setErrorByName(
                'ending_time',
                $this->t('Ending time must be after starting time.')
            );
        }
    }

    /**
     * Handle form submission - save the event.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();

        try {
            // Prepare block content data
            $block_data = [
                'type' => 'event',
                'info' => $values['title'] . ' - ' . $values['day'],

                // Map form fields to block content fields
                'field_title' => $values['title'],
                'field_day' => $values['day'],
                'field_starting_time' => $values['starting_time']->format('Y-m-d\TH:i:s'),
                'field_ending_time' => $values['ending_time']->format('Y-m-d\TH:i:s'),
                'field_trainer' => $values['trainer'],
                'field_for_kids' => $values['for_kids'],
                'field_open_to_beginners' => $values['open_to_beginners'],
            ];

            // Only add age fields if "for_kids" is checked
            if ($values['for_kids']) {
                $block_data['field_minimum_age'] = $values['minimum_age'];
                $block_data['field_maximum_age'] = $values['maximum_age'];
            }

            // Create a new block content entity
            $block = BlockContent::create($block_data);
            $block->save();

            $this->messenger()->addMessage(
                $this->t('Event "@title" has been added!', ['@title' => $values['title']])
            );
        } catch (\Exception $e) {
            $this->messenger()->addError(
                $this->t('Error adding event. Please try again.')
            );
            \Drupal::logger('event_form')->error($e->getMessage());
        }
    }
}
