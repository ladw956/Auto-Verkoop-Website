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
- reads database configuration from environment variables;
- preserves the original PHP/PDO application structure.

## Local development

The project was originally developed using PHP and MySQL.

For local development, import `autoverkoop.sql` into a MySQL/MariaDB database and configure the required database connection values for your own development environment.

The repository is intended primarily for reviewing the source code and the original school project.

## Official live demonstration

An official hosted portfolio demonstration is maintained by **Jaylee Wigley**.

The hosted demonstration uses a disposable demonstration database. Information entered into the public demo should therefore not be considered permanent.

The official demo exists only to demonstrate the original application's functionality as part of my portfolio.

Hosting and deployment configuration for the official environment is maintained separately and is not part of the public source repository.

## Demonstration accounts

The official portfolio demonstration contains dedicated demonstration accounts so visitors do not need to register with real personal information.

Demo Administrator:

`admin@demo.local`

Password:

`admin-demo`

Demo User:

`user@demo.local`

Password:

`user-demo`

These accounts exist only for the portfolio demonstration and must not be used for real information or transactions.

## Security scope

This is a school/demo project, not a production marketplace.

It should not be used for real purchases, real financial transactions, or real personal information without substantial additional production hardening.

Areas that would require further work for a production service include stronger authorization review, CSRF protection, rate limiting, additional input validation, production database management, monitoring, abuse protection, and broader security testing.

## Copyright and usage

Copyright © Jaylee Wigley. All rights reserved.

This repository is publicly accessible for portfolio presentation, educational review, and source-code inspection.

No permission is granted to:

- host or deploy a public copy of the application;
- redistribute the application or its source code;
- publish modified versions;
- use the project as the basis of another hosted service;
- commercially exploit the project;
- present the project or substantial parts of it as someone else's work.

No open-source license has been granted.

The official hosted version and any authorized deployment remain under the control of **Jaylee Wigley**.
