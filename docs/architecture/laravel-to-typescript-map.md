# Laravel to TypeScript migration map

| Previous path | New responsibility |
| --- | --- |
| `frontend/` | `apps/mobile/` |
| `backend/app/Core/Controllers/AuthController.php` | `backend/src/core/auth/api/auth.controller.ts` and `application/auth.service.ts` |
| `backend/app/Core/Controllers/*Controller.php` | `backend/src/core/platform/api/platform.controller.ts` and `application/platform.service.ts` |
| `backend/app/Core/Models/*.php` | Models in `backend/prisma/schema.prisma` |
| `backend/routes/api.php` | NestJS decorators beneath `backend/src/core/**/api` |
| `backend/database/migrations/*` | `backend/prisma/migrations/20260914000100_core_initial/migration.sql` |
| Laravel Sanctum personal access tokens | JWT access tokens plus hashed rotating refresh tokens |
| Laravel validation arrays | Allow-listed `class-validator` DTOs |
| Laravel exception responses | `backend/src/shared/errors/all-exceptions.filter.ts` |
| Laravel service bootstrap | `backend/src/main.ts` and `backend/src/app.module.ts` |
| `backend/app/Modules/Nutrition/` | Reserved boundary at `backend/src/modules/nutrition/` |
| Root PHP Dockerfile | Multi-stage Node.js Dockerfile with Prisma deployment |

The former Laravel source is retained only in a locally ignored `legacy/` directory during review. It is not included in the target repository structure or runtime.
