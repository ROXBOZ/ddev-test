<?php

namespace Drupal\event_delete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\block_content\Entity\BlockContent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Controller for deleting events.
 */
class EventDeleteController extends ControllerBase
{

    /**
     * Delete an event directly.
     *
     * @param \Drupal\block_content\Entity\BlockContent $block_content
     *   The block content entity to delete (auto-loaded by Drupal).
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *   Redirect back to the calendar page.
     */
    public function delete(BlockContent $block_content)
    {
        // Security check: Make sure this is actually an event
        if ($block_content->bundle() !== 'event') {
            $this->messenger()->addError($this->t('Invalid event.'));
            return new RedirectResponse(Url::fromRoute('<front>')->toString());
        }

        // Get event details for the success message
        $title = $block_content->get('field_title')->value;
        $day = $block_content->get('field_day')->value;

        // Delete the event
        $block_content->delete();

        // Show success message
        $this->messenger()->addStatus($this->t('The event "@title" on @day has been deleted.', [
            '@title' => $title,
            '@day' => ucfirst($day),
        ]));

        // Redirect back to homepage/calendar
        return new RedirectResponse(Url::fromRoute('<front>')->toString());
    }
}
