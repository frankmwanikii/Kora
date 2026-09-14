# KORA Admin Dashboard

Modern, responsive CMS for the KORA website. Edit copy, images, FAQs, galleries, and site settings without touching PHP templates.

## First-time setup

1. Open `/Admin_dashboard/setup.php` in the browser.
2. Create an administrator account.
3. Sign in at `/Admin_dashboard/`.

If an admin already exists, go straight to `/Admin_dashboard/`.

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
- SQLite database and sessions live in `Admin_dashboard/data/` (not web-accessible).
