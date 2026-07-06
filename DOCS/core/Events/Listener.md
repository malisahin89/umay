# File Report: core/Events/Listener.php

## Purpose
Base class for all event listeners.

## Overview
Defines the contract for listener classes. Subclasses must implement the `handle` method to define the logic that should execute when the associated event is triggered.

## File Location
`core/Events/Listener.php`

## Namespace
`Core\Events`

## Classes
- `abstract class Listener`

## Methods
- `handle(Event $event): void`: Abstract method to process the event.

## Dependencies
- `Core\Events\Event` (Uses)

## Source References
- `core/Events/Listener.php:1-29`
