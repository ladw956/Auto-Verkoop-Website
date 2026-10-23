# Auto Verkoop Website

Dutch MBO school project by **Jaylee Wigley**, built with PHP and PDO.

This repository is publicly available as part of my portfolio and for educational/source-code review.

The application demonstrates:

- account registration and login;
- car listings;
- adding and editing listings;
- buying a car with an account balance;
- administrator balance management;
- main-administrator user-role management.

## Portfolio restoration

The original school archive was no longer fully runnable as supplied.

The PHP application expected the `saldo` and `is_mainAdmin` database columns, but these columns were missing from the supplied `autoverkoop.sql` schema. The original SQL seed accounts also contained plain-text passwords while the application login system uses `password_verify()`.

For the portfolio version, these inconsistencies were corrected while preserving the original school project and its functionality.

The portfolio copy:

- restores the missing database fields;
- uses correctly hashed demonstration passwords;
- removes the old public password-hash helper;
- reads MySQL database settings from environment variables;
- preserves the original PHP/PDO application structure.

## Local MySQL setup

Import `autoverkoop.sql`, then configure these environment variables if your local values differ:

- `DB_HOST=localhost`
- `DB_PORT=3306`
- `DB_NAME=autoverkoop`
- `DB_USER=root`
- `DB_PASSWORD=...`

Demo accounts created by the SQL file:

- `admin@demo.local` / `admin-demo`
- `user@demo.local` / `user-demo`

## Official live demonstration

An official hosted portfolio demonstration is maintained by **Jaylee Wigley**.

Hosting, Docker, disposable-demo-database, and Render configuration for the official environment are maintained in a separate private deployment repository and are not included in this public source repository.

The official demo exists only to demonstrate the original application's functionality as part of my portfolio.

## Security scope

This is a school/demo project, not a production marketplace. It should not be used for real purchases, real financial transactions, or real personal information without substantial additional production hardening.

## Copyright and usage

Copyright © Jaylee Wigley. All rights reserved.

This repository is publicly accessible for portfolio presentation, educational review, and source-code inspection.

No permission is granted to host or deploy a public copy of the application, redistribute the application or its source code, publish modified versions, commercially exploit the project, or present the project or substantial parts of it as someone else's work.

No open-source license has been granted.
