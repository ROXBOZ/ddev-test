# Roxboz Theme Setup

## Files Created

- `/public/themes/custom/roxboz/` - Custom theme folder
- `roxboz.info.yml` - Theme config
- `roxboz.libraries.yml` - CSS/JS libraries
- `roxboz.theme` - PHP functions
- `css/style.css` - Main styles
- `css/components/button.css` - Button component
- `js/roxboz.js` - Theme JavaScript
- `templates/page.html.twig` - Page template

## CSS Variables

- `--primary`, `--secondary`, `--accent`
- `--background`, `--text`, `--border`, `--spacing`

## Auto Workflow

- Save CSS/Twig → Auto cache clear → Browser auto-refresh
- Prettier/Twig formatter on save (all file types)

## Extensions Installed

- Twig Language 2 - Twig syntax highlighting & formatting
- Run on Save - Auto-run commands on file save
- Browser Refresh - Auto browser refresh
- Prettier - Code formatter

## Quick Commands

```bash
ddev drush cache:rebuild
ddev drush theme:enable roxboz
```

## Site URL

https://ddev-test.ddev.site
