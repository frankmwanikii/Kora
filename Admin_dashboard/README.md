# KORA Admin Dashboard

Modern, responsive CMS for the KORA website. Edit copy, images, FAQs, galleries, and site settings without touching PHP templates.

## First-time setup

1. Open `/setup.php` or `/Admin_dashboard/setup.php` in the browser.
2. Enter MySQL host, database name, username, and password (the installer can create the database).
3. Create your administrator account.
4. Sign in at `/Admin_dashboard/`.

If the site is already installed, those URLs send you to the login page.

The public website still works from `data/cms/site.json` even before setup. The dashboard needs MySQL.

## What you can manage

| Area | URL |
|------|-----|
| Dashboard | `/Admin_dashboard/dashboard.php` |
| Page sections (hero, about, FAQ, products, work samples, footer, …) | `/Admin_dashboard/pages.php` |
| Media gallery (browse / upload / delete uploads) | `/Admin_dashboard/gallery.php` |
| Emails (create / list / delete mailboxes via cPanel API) | `/Admin_dashboard/emails.php` |
| Contact, social, brand settings | `/Admin_dashboard/settings.php` |

Edits export to `data/cms/site.json`, which the public site reads live.

## Notes

- Stock images under `assets/images/` are visible in the gallery but only **uploads** (`assets/images/uploads/…`) can be deleted.
- Upload paths are relative to `assets/images/` (e.g. `uploads/2026/photo.webp`).
- MySQL credentials live in `data/db.local.php` (not committed). Copy `data/db.local.php.example` if you need to recreate them.
- PHP sessions still live in `Admin_dashboard/data/sessions/` (not web-accessible).
