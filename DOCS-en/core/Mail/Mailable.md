# File Report: core/Mail/Mailable.php

## Purpose
Base class for all mailable messages.

## Overview
Provides a structure for defining the content, recipients, and attachments of an email. Mailables are processed by a `Mailer` and sent via a `MailTransport`.

## File Location
`core/Mail/Mailable.php`

## Namespace
`Core\Mail`

## Classes
- `class Mailable`

## Properties
- `array $attachments`: List of file attachments.
- `array $headers`: Custom HTTP headers for the email.

## Methods
- `getAttachments(): array`: Returns the registered attachments.
- `getExtraHeaders(): array`: Returns the custom headers.
- `getFrom(): string`: Returns the sender's email address.
- `getFromName(): string`: Returns the sender's name.
- `view(string $view, array $data = []): static`: Sets the email body to the rendered output of a view template.
- `renderView(string $view, array $data = []): string`: Renders the specified view.

## Dependencies
- `Core\View` (Uses via `ResponseBuilder` or direct resolution)

## Source References
- `core/Mail/Mailable.php:1-117`
