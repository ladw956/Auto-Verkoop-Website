# Auto Verkoop Website

Dutch MBO school project by Jaylee Wigley, built with PHP and PDO.

The application demonstrates:

- account registration and login;
- car listings;
- adding and editing listings;
- buying a car with an account balance;
- administrator balance management;
- main-administrator user-role management.

## Fixes made for the public portfolio copy

The uploaded school archive was not fully runnable as supplied. The PHP code expected the `saldo` and `is_mainAdmin` columns, but they were missing from `autoverkoop.sql`. The SQL seed passwords were also plain text even though login uses `password_verify()`.

This public copy fixes those schema mismatches, uses proper password hashes, removes the public password-hash helper, and reads database settings from environment variables.

## Local MySQL setup

Import `autoverkoop.sql`, then set these environment variables if your local values differ:

- `DB_DRIVER=mysql`
- `DB_HOST=localhost`
- `DB_PORT=3306`
- `DB_NAME=autoverkoop`
- `DB_USER=root`
- `DB_PASSWORD=...`

Demo accounts created by the SQL file:

- `admin@demo.local` / `admin-demo`
- `user@demo.local` / `user-demo`

## Render demo

`render.yaml` deploys the project as a small Docker web service using SQLite, so no separate database service is required for the portfolio demo. The demo database is recreated when the free service restarts or spins down. This is intentional: it keeps the school demo self-contained and prevents public demo data from becoming permanent.

The same demo accounts are available on Render.

## Security scope

This is a school/demo project, not a production marketplace. It should not be used for real purchases or real personal data without additional security hardening such as CSRF protection, stronger authorization review, rate limiting, validation, and production-grade database handling.

## License

No open-source license has been selected yet. Add a license file before describing the repository as formally open-source licensed.
