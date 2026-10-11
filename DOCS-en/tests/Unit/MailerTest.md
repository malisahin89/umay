# File Report: tests/Unit/MailerTest.php

## Purpose
Unit tests for the mailer and its log transport.

## Overview
Verifies `Core\Mail\Mailer`: fluent recipient methods (`to`, `cc`, `bcc`) including array and name-pair forms, that `LogTransport` implements the transport contract, that sending via the log transport writes a log entry, and that CRLF in recipient values is stripped (header-injection defense).

## File Location
`tests/Unit/MailerTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class MailerTest extends Tests\TestCase`

## Subject Under Test
- `Core\Mail\Mailer`, `Core\Mail\Transport\LogTransport`

## Test Methods
- `test_to_returns_mailer_instance` — `:47`
- `test_to_accepts_array_of_recipients` — `:52`
- `test_to_with_name_stores_array_pair` — `:58`
- `test_cc_is_chainable` — `:64`
- `test_bcc_accepts_array` — `:70`
- `test_log_transport_implements_contract` — `:78`
- `test_send_uses_log_transport_and_writes_log` — `:83`
- `test_recipient_crlf_is_stripped` — `:101`

## Cross References
- **Tests:** `Core\Mail\Mailer` (see `DOCS/core/Mail/Mailer.md`), `Core\Mail\Transport\LogTransport` (see `DOCS/core/Mail/Transport/LogTransport.md`)

## Source References
- `tests/Unit/MailerTest.php:1-114`
