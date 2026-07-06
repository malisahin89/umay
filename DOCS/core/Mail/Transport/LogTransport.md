# File Report: core/Mail/Transport/LogTransport.php

## Purpose
Mail transport implementation for logging.

## Overview
Instead of sending real emails, this transport writes the contents of a `Mailable` to the application log files. It is the default transport for development environments.

## File Location
`core/Mail/Transport/LogTransport.php`

## Namespace
`Core\Mail\Transport`

## Classes
- `class LogTransport implements MailTransport`

## Methods
- `send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool`: Formats the email as a log entry and writes it using `Core\Logger`.

## Dependencies
- `Core\Contracts\MailTransport` (Implements)
- `Core\Mail\Mailable` (Uses)
- `Core\Logger` (Uses)

## Source References
- `core/Mail/Transport/LogTransport.php:1-50`
