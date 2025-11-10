# custom Theme Setup

## Files Created

### Theme Structure

- `/public/themes/custom/custom/` - Custom theme folder
- `custom.info.yml` - Theme config
- `custom.libraries.yml` - CSS/JS libraries
- `custom.theme` - PHP functions

### Styles

- `css/style.css` - Main styles (layout, page structure)
- `css/header.css` - Header styles
- `css/footer.css` - Footer styles
- `css/typography.css` - Typography styles
- `css/components/button.css` - Button component
- `css/components/event-calendar.css` - Event calendar styles

### JavaScript

- `js/custom.js` - Theme JavaScript

### Templates

- `templates/page.html.twig` - Page template
- `templates/calendar/views-view-unformatted--event-calendar.html.twig` - Calendar grid layout
- `templates/views-view-fields--event-calendar.html.twig` - Individual event display

## Event Calendar

The event calendar displays training courses in a 5-column weekday layout:

### Features

- **Weekly Grid Layout** - Monday to Friday columns
- **Time-based Sorting** - Events sorted by starting time within each day
- **Date Filtering** - Only shows events currently active (between start and end date)
- **Age Groups** - Displays "Kids" (<13) or "Teens" (13+) based on minimum age
- **Beginner Friendly** - Shows indicator for classes open to beginners
- **BEM Naming** - CSS follows BEM convention (`.calendar`, `.event`, modifiers)

### Fields Used

- `field_day` - Weekday (monday-friday)
- `field_starting_time` - Start date/time
- `field_ending_time` - End date/time
- `field_title` - Event name
- `field_trainer` - Trainer name
- `field_for_kids` - Boolean for kids/teens
- `field_minimum_age` - Minimum age
- `field_maximum_age` - Maximum age
- `field_open_to_beginners` - Boolean for beginner friendly

## CSS Variables

- `--primary`, `--secondary` - Brand colors

## Auto Workflow

- Save CSS/Twig/PHP → Auto cache clear → Browser auto-refresh
- Prettier on save (Twig, CSS, JS, JSON)
- Twig formatter using Prettier v2.8.8 + twig-melody plugin

## Extensions Installed

- Twig Language 2 - Twig syntax highlighting
- Run on Save - Auto-run commands on file save
- Prettier - Code formatter (compatible with Twig)

## Quick Commands

```bash
# Clear Drupal cache
ddev drush cache:rebuild

# Enable theme
ddev drush theme:enable custom

# Format Twig files
npx prettier --write "**/*.twig"
```

## Site URL

https://ddev-test.ddev.site
