# File Report: core/Mail/Mailer.php

## Purpose
Email delivery coordinator.

## Overview
Handles the logic of sending `Mailable` objects. It uses a configured `MailTransport` (e.g., `LogTransport`) to perform the actual delivery.

## File Location
`core/Mail/Mailer.php`

## Namespace
`Core\Mail`

## Classes
- `class Mailer`

## Properties
- `array $to`, `$cc`, `$bcc`: Recipient lists.

## Methods
- `to(array|string $address): static`: Adds recipients to the "To" list.
- `cc(array|string $address): static`: Adds recipients to the "CC" list.
- `bcc(array|string $address): static`: Adds recipients to the "BCC" list.
- `send(Mailable $mailable): bool`: The final delivery method. It resolves the active transport from `config/mail.php` and calls its `send()` method.

## Dependencies
- `Core\Contracts\MailTransport` (Uses)
- `Core\Mail\Mailable` (Uses)

## Source References
- `core/Mail/Mailer.php:1-154`
