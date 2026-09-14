# Fitness architecture

## Dependency direction

`apps -> backend API -> module application -> module domain`

Every product module may consume exported Core contracts. Core must never import a product module. Modules must not import one another directly; cross-module workflows use shared contracts or domain events.

## Ownership

- Core Platform owns authentication, users, profiles, permissions, consent, subscriptions, entitlements, notifications, preferences, auditing, infrastructure, and shared contracts.
- A module backend owns its domain, use cases, persistence adapters, and HTTP API below `backend/src/modules/<module>`.
- Mobile and Admin applications consume versioned contracts and never access the database directly.

## API rules

- All endpoints are below `/api/v1`.
- Authentication uses short-lived JWT access tokens and rotating refresh tokens.
- Request DTOs are allow-listed and reject unknown properties.
- Database identifiers are UUIDs and timestamps are UTC.
- Breaking contract changes require a new API version.

The former Laravel backend is temporarily preserved in `legacy/laravel-backend` for review and behavioral comparison. It is not part of the new runtime.
