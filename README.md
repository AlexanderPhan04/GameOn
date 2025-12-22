# Market Place

## Technologies Used

-   Laravel
-   Bootstrap
-   Blade Templates

## Team Members

-   **Phan Nhật Quân** (Trưởng nhóm)
-   **Đỗ Thế An**
-   **Lê Khánh An**

## Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/AlexanderPhan04/Market-Place.git
cd market-place
```

### 2. Install Dependencies

**Backend (Laravel)**

```bash
composer install
```

**Frontend (Bootstrap & Assets)**

```bash
npm install
npm run dev
```

### 3. Environment Configuration

**Backend (Laravel)**

1.  Copy the example environment file:
    ```bash
    cp .env.example .env
    ```
2.  Generate the application key:
    ```bash
    php artisan key:generate
    ```
3.  Configure your database settings in the `.env` file.
4.  Run database migrations:
    ```bash
    php artisan migrate
    ```

## Development server

Run the Laravel development server:

```bash
php artisan serve
```

Navigate to `http://localhost:8000/`.
