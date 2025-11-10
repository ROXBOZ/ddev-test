<?php

namespace Drupal\custom_calendar\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for the calendar page.
 */
class CalendarController extends ControllerBase {

  /**
   * Displays the weekly calendar view.
   */
  public function weekView() {
    // Get current week dates.
    $current_week = $this->getCurrentWeekDates();
    
    // Get events for the current week.
    $events = $this->getWeekEvents($current_week['start'], $current_week['end']);
    
    return [
      '#theme' => 'calendar_week_view',
      '#week_dates' => $current_week,
      '#events' => $events,
      '#attached' => [
        'library' => [
          'custom_calendar/calendar-styling',
        ],
      ],
    ];
  }

  /**
   * Get dates for the current week (Monday to Friday).
   */
  private function getCurrentWeekDates() {
    $today = new \DateTime();
    
    // Get Monday of current week.
    $monday = clone $today;
    $monday->modify('monday this week');
    
    $dates = [];
    for ($i = 0; $i < 5; $i++) {
      $date = clone $monday;
      $date->modify("+$i days");
      $dates[] = [
        'date' => $date,
        'day_name' => $date->format('l'),
        'day_number' => $date->format('j'),
        'month' => $date->format('M'),
        'full_date' => $date->format('Y-m-d'),
      ];
    }
    
    return [
      'start' => $monday->format('Y-m-d'),
      'end' => $dates[4]['full_date'],
      'dates' => $dates,
    ];
  }

  /**
   * Get events for the specified date range.
   */
  private function getWeekEvents($start_date, $end_date) {
    // TODO: Query events from the database
    // For now, return empty array
    // We'll implement this after creating the Event content type
    return [];
  }

}
