# Installation

Umay Framework is a web framework developed using modern PHP standards, extremely lightweight yet capable. It aims for Developer Experience (DX) while staying away from unnecessary complexity.

## System Requirements

To run Umay Framework on your server or local environment, the following requirements must be met:

- **PHP**: `>= 8.2`
- **Database**: MySQL 8.0+ / MariaDB 10.4+ or SQLite for tests
- **Extensions**: `PDO`, `Mbstring`, `JSON`, `OpenSSL`
- **Web Server**: Apache (mod_rewrite active) or Nginx

## Creating a New Project

Starting an Umay Framework project is quite simple. Follow these steps on a system with Composer installed:

```bash
# Clone the project from GitHub or your local archive
git clone https://github.com/malisahin89/umay.git my-project

# Go to project directory
cd my-project

# Install dependencies
composer install
```

## Configuration (.env)

After installation is complete, create a `.env` file in the root directory:

```bash
# Copy method (if .env.example exists):
cp .env.example .env

# or create manually:
touch .env
```

Make sure your `APP_KEY` value is unique. In the development environment, using `APP_ENV=local` and `APP_DEBUG=true` settings allows you to see errors more easily.

```ini
APP_NAME="Umay Framework"
APP_ENV=local
APP_KEY=your_secret_key_here
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umay_db
DB_USERNAME=root
DB_PASSWORD=
```

## First Run

To launch your project immediately in your development environment, you can use tools like Laragon/Valet, or you can use PHP's built-in server:

```bash
php -S localhost:8000 -t public
```

When you go to `http://localhost:8000` from your browser, you will see the Umay Framework welcome screen.
