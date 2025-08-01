# Blog Application

A Laravel blog application with Vue.js frontend using Inertia.js.

## Local Development Setup

### Prerequisites

- PHP 8.4
- Composer
- Node.js (18+ recommended)
- npm

### Quick Start

1. **Clone and install dependencies:**

    ```bash
    git clone https://github.com/Kadikis/Blog
    cd Blog
    composer install
    npm install
    ```

2. **Environment setup:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3. **Database setup:**

    ```bash
    touch database/database.sqlite
    php artisan migrate --seed
    ```

4. **Start development servers:**

    ```bash
    composer run dev
    ```

### Testing

```bash
php artisan test
```

---

Visit http://localhost:8000 to see the application.
