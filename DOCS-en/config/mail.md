# File Report: config/mail.php

## Purpose
Mail system configuration.

## Overview
Defines a driver-based mail system. The framework provides a `LogTransport` by default, which writes emails to `storage/logs`. Other transports can be added by implementing `Core\Contracts\MailTransport`.

## File Location
`config/mail.php`

## Configuration
- `default`: Active mailer from `MAIL_MAILER` (default: 'log').
- `mailers`:
    - `log`: Uses `Core\Mail\Transport\LogTransport`.
- `from`:
    - `address`: From `MAIL_FROM_ADDRESS` (default: 'noreply@localhost').
    - `name`: From `MAIL_FROM_NAME` (default: 'Umay').

## Source References
- `config/mail.php:1-58`
