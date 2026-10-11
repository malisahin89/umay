# File Report: core/Events/Event.php

## Purpose
Base class for all application events.

## Overview
Provides basic functionality for all events, most notably the ability to stop the propagation of the event to subsequent listeners.

## File Location
`core/Events/Event.php`

## Namespace
`Core\Events`

## Classes
- `abstract class Event`

## Methods
- `stopPropagation(): void`: Marks the event as stopped, preventing further listeners from being executed.
- `isPropagationStopped(): bool`: Returns whether propagation has been stopped.

## Source References
- `core/Events/Event.php:1-32`
