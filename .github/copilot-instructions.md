# SCC Portal — Project Guidelines

SCC Portal is a **school information system** built with Laravel 12 (PHP 8.2+). It manages the full student lifecycle: admissions, enrollment, grading, and payments — with separate portals for Admin, Registrar, Faculty, and Student roles.

## Build & Test

```bash
composer install && npm install   # Install dependencies
composer run dev                  # Starts Laravel server + queue listener + Vite (all in one)
php artisan migrate               # Run migrations (default DB: SQLite)
php artisan migrate:fresh --seed  # Reset and re-seed the database
composer run test                 # php artisan config:clear + phpunit
npm run build                     # Production build (Tailwind + Vite)
```

Tests use **SQLite in-memory** (see `phpunit.xml`). Dev uses SQLite by default; set `DB_CONNECTION=mysql` in `.env` for MySQL.

## Architecture

**MVC + Service Layer.** Controllers stay thin — business logic lives in `app/Services/`.

| Layer       | Location                                                  | Responsibility                                                                    |
| ----------- | --------------------------------------------------------- | --------------------------------------------------------------------------------- |
| Controllers | `app/Http/Controllers/{Admin,Faculty,Registrar,Student}/` | Handle HTTP, validate via FormRequests, delegate to Services                      |
| Services    | `app/Services/`                                           | Complex multi-step logic (use `DB::transaction()`, throw `\Exception` on failure) |
| Models      | `app/Models/`                                             | Eloquent relationships, scopes, casts, constants                                  |
| Policies    | `app/Policies/`                                           | Row-level authorization (`GradePolicy`, `FacultyPolicy`)                          |

**Key domain flows:**

- **Admission:** `Application` → Registrar approves → `AdmissionService` creates `User` + `Student` + sends `AdmissionConfirmed` mail
- **Enrollment:** `Enrollment` per semester → `EnrollmentSubject` per course → `EnrollmentService` auto-assigns subjects
- **Grading:** Faculty encodes draft `Grade` → Registrar finalizes (`is_finalized=true`) → every change logged in `GradeAuditLog`
- **Payments:** Manual GCash QR flow (`config/gcash.php`) → Registrar verifies → updates `payment_status`

## Conventions

**Naming:**

- Models: `PascalCase` singular (`EnrollmentSubject`, `GradeAuditLog`)
- Tables: `snake_case` plural — **exception:** `faculty` table uses singular (set explicitly via `$table = 'faculty'`)
- Routes: `kebab-case` (`/admin/grade-levels`, `/registrar/exam-schedules`)
- Service methods: return models or result arrays; throw on error (callers wrap in try/catch)

**Eloquent patterns to follow:**

- Use `SoftDeletes` on: `User`, `Student`, `Faculty`, `Program`, `Section`, `Semester`, `AcademicYear`, `Enrollment`
- Use class constants for status enums (e.g., `Student::STATUSES`, `Student::ENROLLABLE_STATUSES`)
- Add query scopes for filtered collections (e.g., `scopeActive`, `scopePending`, `scopeEnrollable`)

**Authorization:**

- Route-level: `->middleware('role:admin')` via `RoleMiddleware` — accepts comma-separated roles
- Row-level: Laravel Policies (`GradePolicy`, `FacultyPolicy`) — always check before writing new Policy logic
- User helpers: `$user->hasRole('admin')`, `$user->hasAnyRole(['admin', 'registrar'])`, `$user->primaryRole()`

**Frontend:** Pure **Blade + Tailwind CSS v4**. No Livewire, Vue, or React. Use **SweetAlert2** for JS alerts/confirmations. Assets built by Vite — entry points in `vite.config.js` (`app.css`, `app.js`, `login.css`, etc.).

## Pitfalls

- **`Student::STATUSES` vs migration enum:** The migration omits `admitted` / `suspended`; the model constants include them. Trust the model constants — the migration enum is not strictly enforced.
- **`User::primaryRole()`** returns the _first_ role from the pivot — users can technically have multiple roles, but most logic assumes one.
- **`exam_schedule` (string) vs `exam_schedule_id` (FK):** Both exist on `applications`. Prefer the FK (`exam_schedule_id`) when referencing `ExamSchedule` records.
- **Grade finalization is irreversible** via normal routes — only Admin can reopen via `AdminGradeController::reopen`.
- **GCash payments are manual:** No payment gateway SDK — the flow is QR display → student uploads proof → Registrar verifies manually.
- **PHPUnit config clears config cache** (`config:clear`) before tests — always run tests via `composer run test`, not `./vendor/bin/phpunit` directly.
