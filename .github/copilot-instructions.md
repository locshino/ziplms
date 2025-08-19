# Copilot Instructions for ZipLMS

## Project Architecture
- **Laravel-based**: Uses Laravel for routing, ORM (Eloquent), service container, and Blade templating.
- **Service Layer** (`app/Services/`): Encapsulates business logic. Services interact with repositories via interfaces, handle validation, transactions, and error handling. See `app/Services/README.md` for method conventions.
- **Repository Pattern** (`app/Repositories/`): All data access is abstracted via repositories and interfaces. Use repository methods for queries, not direct Eloquent calls in controllers/services. See `app/Repositories/README.md` for available methods.
- **Domain Models**: `app/Models/` contains Eloquent models. Relations are defined here and used by repositories/services.
- **Filament Admin**: Custom admin pages/components in `app/Filament/Pages/` and `resources/views/filament/pages/`.
- **Bot Scripts**: Development/test scripts in `scripts/bot/` (see its README for usage and organization).

## Developer Workflows
- **Testing**: Use Pest (`tests/`, `Pest.php`) for unit/feature tests. Run with `php artisan test` or `vendor/bin/pest`.
- **Debugging**: Use scripts in `scripts/bot/test/` for permission, logic, and migration debugging.
- **Build/Assets**: Frontend assets managed via Vite (`vite.config.js`). Use `npm run dev` for local builds.
- **Database**: Migrations/seeds in `database/`. Use `php artisan migrate` and `php artisan db:seed`.

## Project-Specific Conventions
- **Service/Repository Usage**: Always inject via interface, not concrete class. Example:
  ```php
  public function __construct(UserServiceInterface $userService) { ... }
  ```
- **Role/Permission Checks**: Use `RoleHelper` (see `app/Helpers/RoleHelper.php`) for all role logic. Example:
  ```php
  if (RoleHelper::isTeacher($user)) { ... }
  ```
- **Business Logic**: Never put business rules in controllers or Blade views. Use services.
- **Error Handling**: Use custom exceptions (e.g., `QuizServiceException`) for service errors.
- **Blade Components**: Use slot-based, reusable components for UI (see `resources/views/components/` and Filament pages).
- **Pagination/Filtering**: Use Eloquent scopes and repository methods for all list filtering and pagination.
- **Language/Localization**: Place translations in `lang/`.

## Integration Points
- **External Packages**: Filament, DaisyUI, Pest, and others via Composer/NPM.
- **Cross-Component Communication**: Services call repositories; Filament pages call services; Blade views use public properties from Filament pages.
- **Events/Notifications**: Use Laravel events and Filament notifications for user feedback.

## Examples
- **Service Method**: `QuizService::getAvailableQuizzes($userId)`
- **Repository Method**: `CourseRepository::getCoursesByTeacher($teacherId)`
- **Role Check**: `RoleHelper::isAdmin($user)`
- **Custom Exception**: `QuizServiceException::maxAttemptsReached()`

## Key Files/Directories
- `app/Services/README.md` — Service layer conventions
- `app/Repositories/README.md` — Repository pattern and usage
- `app/Helpers/RoleHelper.php` — Role/permission logic
- `scripts/bot/README.md` — Dev/test script organization
- `resources/views/components/` — Blade UI components
- `app/Filament/Pages/` — Filament admin pages

---

For unclear conventions or missing patterns, ask for clarification or review the relevant README in the directory.
