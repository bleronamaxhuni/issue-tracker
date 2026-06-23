# Issue Tracker

A mini issue tracker built with Laravel where a small team can manage projects, issues, tags, and comments.

## Tech stack

- **Laravel 13** (PHP 8.3+)
- **Laravel Breeze** (Blade stack)
- **Tailwind CSS** + **Alpine.js**
- **Vite**
- **SQLite** (default)

## Features

- **Authentication** — register, login, profile management (Breeze)
- **Projects** — CRUD with modals, owner-only access, `start_date` and `deadline`
- **Issues** — CRUD scoped to projects, global index with filters (status, priority, tag) and debounced text search; assign members via AJAX
- **Tags** — create and list tags; attach/detach on issue detail via AJAX
- **Comments** — paginated load and inline add on issue detail via AJAX (no full page reload)
- **Authorization** — `ProjectPolicy` and `IssuePolicy` restrict access to project owners

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+ and npm

## Setup

```bash
git clone https://github.com/bleronamaxhuni/issue-tracker.git
cd issue-tracker

composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed

npm install
npm run build
```

Or use the Composer setup script (installs dependencies, creates `.env`, migrates, and builds assets):

```bash
composer setup
php artisan db:seed
```

## Running locally

Start the PHP server and Vite dev server:

```bash
composer dev
```

Or run them separately:

```bash
php artisan serve
npm run dev
```

Open [http://localhost:8000](http://localhost:8000).

## Demo accounts

After seeding, log in with either account (password for both: `password`):

| Email | Name |
|-------|------|
| `alice@example.com` | Alice Owner |
| `bob@example.com` | Bob Owner |

Each user owns separate projects with sample issues, tags, and comments. Alice's **Website Redesign → Redesign homepage hero section** issue has 9 comments to demo comment pagination.

## Tests

```bash
php artisan test
```

## AJAX endpoints

| Method | Route | Purpose |
|--------|-------|---------|
| `POST` | `/issues/{issue}/tags/{tag}` | Attach tag to issue |
| `DELETE` | `/issues/{issue}/tags/{tag}` | Detach tag from issue |
| `POST` | `/issues/{issue}/assignees/{user}` | Assign member to issue |
| `DELETE` | `/issues/{issue}/assignees/{user}` | Remove assignee from issue |
| `GET` | `/issues/{issue}/comments` | Paginated comments (JSON) |
| `POST` | `/issues/{issue}/comments` | Add comment (JSON) |

Frontend logic lives in `resources/js/issue-tags.js`, `resources/js/issue-comments.js`, `resources/js/issue-search.js`, and `resources/js/issue-assignees.js`.

Issue search uses `GET /issues?search=...` (with optional status, priority, and tag params). The issues index fetches JSON responses as you type (300ms debounce) without reloading the page.

## License

MIT
