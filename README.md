# MathGenius

AI-powered mathematics learning platform built with Laravel 13.

## Requirements

- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL (via Laravel Sail)

## Installation

```bash
# Clone the repository
git clone <repo-url>
cd MathGenius

# Install dependencies
composer setup
# Or manually:
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
```

## Development

```bash
composer dev
```

This starts the server, queue worker, logs, and Vite dev server concurrently.

## Packages

### Laravel Sanctum

Sanctum provides API token authentication for first-party SPAs and mobile apps.

**Configuration:**
- Config: `config/sanctum.php`
- Trait: `Laravel\Sanctum\HasApiTokens` on the `User` model
- Migration: `personal_access_tokens` table

**Usage:**

```bash
# Create a token
$user->createToken('auth-token')->plainTextToken

# Authenticate requests via Bearer token
Authorization: Bearer {token}
```

**Stateful domains** are configured in `.env`:
```
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1
```

### Laravel AI

Unified AI provider abstraction supporting OpenAI, Anthropic, Groq, Gemini, and more.

**Configuration:**
- Config: `config/ai.php`
- Default provider: `openai` (configurable via `AI_PROVIDER` env)

**Environment variables:**
```
AI_PROVIDER=openai
OPENAI_API_KEY=sk-...
GROQ_API_KEY=gsk_...
ANTHROPIC_API_KEY=sk-ant-...
```

**Usage:**
```php
use Illuminate\Support\Facades\AI;

// Generate text
$response = AI::openai()->generate('Explain the Pythagorean theorem');

// With a specific provider
$response = AI::groq()->generate('Solve: 3x + 5 = 20');
```

### Scribe (API Documentation)

Automatic API documentation generator.

**Configuration:** `.scribe/` directory

**Generate docs:**
```bash
php artisan scribe:generate
```

**View docs:**
```bash
php artisan serve
# Then visit http://localhost/docs
```

**Files:**
- `.scribe/intro.md` - API introduction
- `.scribe/auth.md` - Authentication documentation
- `resources/views/scribe/index.blade.php` - Generated HTML

## Useful Artisan Commands

```bash
# Database
php artisan migrate
php artisan migrate:fresh --seed

# Scribe documentation
php artisan scribe:generate

# Laravel AI
php artisan make:agent MyAgent
php artisan make:ai-tool MyTool

# Development
composer dev          # Start all services
php artisan test      # Run tests
php artisan pail      # Tail logs
```

## Project Structure

```
app/
├── Http/Controllers/
├── Models/            # Eloquent models
config/
├── ai.php             # AI provider configuration
├── sanctum.php        # Sanctum authentication
├── auth.php           # Auth guards
routes/
├── api.php            # API routes (Sanctum protected)
├── web.php            # Web routes
.scribe/               # API documentation source
```
