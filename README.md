# TP-PHP-MVC — quick notes

Added features:

- Password reset support (nov 2025)
- Minimal CMS Pages support (nov 2025)

Password reset:

<!-- DB managed externally (you use pgAdmin) - migrations removed from repo -->

- Model: `www/Models/PasswordReset.php`
- Service: `www/Services/PasswordResetService.php`
- Controller: `www/Controllers/Auth.php` updated + `www/Controllers/EmailVerification.php` sendRestPwdMail
- Views: `www/Views/forgot-password.php`, `www/Views/reset-password.php`

Pages mini-CMS:

<!-- DB managed externally (you use pgAdmin) - migrations removed from repo -->

- Model: `www/Models/Page.php`
- Service: `www/Services/PageService.php`
- Controller (admin): `www/Controllers/AdminPages.php`
- Controller (public): `www/Controllers/Page.php`
- Views: `www/Views/admin/pages.php`, `www/Views/admin/page-form.php`, `www/Views/page.php`

Routes added for admin pages and reset/password features. Public pages can be visited at `http://localhost:8080/{slug}` (the app will try to resolve the slug to a page in DB if no explicit route is found).

Quick test (requires DB configured via pgAdmin / your Postgres instance):

```powershell
php tests/test_page_service.php
```
