<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Student Conduct Management System

An enterprise-grade Laravel application for managing student conduct violations, disciplinary actions, and compliance reporting in educational institutions.

## Features

- **Role-Based Access Control** - Student, Staff, and Administrator roles with granular permissions
- **Violation Management** - Track, document, and manage student conduct violations
- **Offense Rules Library** - Comprehensive categorization of violations with standardized sanctions
- **Audit Trail** - Complete logging of authentication and system activities
- **Appeals Process** - Students can view and appeal violations
- **Reporting System** - Generate compliance and statistical reports

## Quick Start

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd student-conduct-management-system

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate:fresh --seed

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

### Test Accounts

The system comes with pre-configured test accounts for each role:

- **Administrator:** `admin@example.com` / `password`
- **Staff:** `staff@example.com` / `password`
- **Student:** `student@example.com` / `password`

📖 **[View all test accounts →](Markdowns/TEST-ACCOUNTS.md)**

## Documentation

- **[Test Accounts](Markdowns/TEST-ACCOUNTS.md)** - Available test accounts and credentials
- **[Security & Authentication](Markdowns/SECURITY-AUTHENTICATION.md)** - Enterprise-grade security implementation
- **[Timeline](Markdowns/TIMELINE.md)** - Project development timeline

## Technology Stack

- **Backend:** Laravel 12, PHP 8.5
- **Frontend:** Livewire 3, Tailwind CSS 3
- **Authentication:** Laravel Breeze, Spatie Laravel Permission
- **Database:** MySQL/PostgreSQL
- **Testing:** PHPUnit

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
