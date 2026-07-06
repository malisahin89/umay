# File Report: core/Contracts/MailTransport.php

## Purpose
Interface for mail transport implementations.

## Overview
Defines how a `Mailable` object should be delivered to recipients. This allows the framework to support multiple delivery methods (e.g., Log, SMTP, API) by simply switching the transport class in the config.

## File Location
`core/Contracts/MailTransport.php`

## Namespace
`Core\Contracts`

## Interfaces
- `interface MailTransport`

## Methods
- `send(Mailable $mailable, array $to, array $cc = [], array $bcc = []): bool`: Delivers the mail to the specified addresses.

## Dependencies
- `Core\Mail\Mailable` (Uses)

## Source References
- `core/Contracts/MailTransport.php:1-33`
