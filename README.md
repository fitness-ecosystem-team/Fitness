# Fitness Ecosystem

Modular wellness platform with one shared backend and independently owned product modules.

- `apps/mobile`: Expo and React Native mobile product.
- `apps/admin`: reserved for the React administration product.
- `backend`: NestJS API shared by every module.
- `packages`: framework-neutral TypeScript contracts and utilities.
- `legacy/laravel-backend`: review-stage preservation of the former backend; remove only after migration approval.

See `docs/architecture/README.md` for ownership and dependency rules.
