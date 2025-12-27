# Game On - Pro Gaming Platform

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

**Game On** is a professional esports management platform designed for gamers, teams, and tournaments. Built with Laravel 12 and modern web technologies, it provides a comprehensive solution for managing esports activities, teams, tournaments, and community interactions.

## 🎮 Features

### Core Features

-   **User Management System**

    -   Multi-role system (Super Admin, Admin, Player, Viewer)
    -   User registration and authentication
    -   Email verification
    -   Google OAuth integration
    -   Profile management with avatar support

-   **Team Management**

    -   Create and manage teams
    -   Team member invitations
    -   Role assignment (Captain, Member)
    -   Team statistics and achievements

-   **Tournament Management**

    -   Create and organize tournaments
    -   Tournament brackets and scheduling
    -   Real-time result management
    -   Tournament status tracking

-   **Game Management**

    -   Add and manage games
    -   Game categories and details
    -   Game-specific tournament settings

-   **Honor System**

    -   Voting system for players/teams
    -   Event-based and free mode voting
    -   Real-time vote tracking
    -   Honor event management

-   **Social Features**

    -   Posts and comments system
    -   Reactions and likes
    -   Mentions and notifications
    -   Media uploads

-   **Chat System**

    -   Real-time messaging
    -   Group conversations
    -   Message reactions
    -   Online status tracking

-   **Search Functionality**

    -   Global search across users, teams, tournaments, and games
    -   Advanced filtering options
    -   Quick search shortcuts

-   **Multi-language Support**

    -   English (EN)
    -   Vietnamese (VI)
    -   Dynamic language switching

-   **Dashboard Analytics**
    -   User statistics
    -   Team performance metrics
    -   Tournament analytics
    -   Activity distribution charts

## 🛠️ Technology Stack

### Backend

-   **Framework**: Laravel 12.0
-   **PHP**: 8.2+
-   **Database**: SQLite (default) / MySQL / PostgreSQL
-   **Authentication**: Laravel Session Auth + Google OAuth (Laravel Socialite)

### Frontend

-   **CSS Framework**: Bootstrap 5.3.3
-   **JavaScript**: Vanilla JS + Axios
-   **Build Tool**: Vite 7.0
-   **Icons**: Font Awesome 5

### Development Tools

-   **Code Quality**: Laravel Pint
-   **Testing**: PHPUnit
-   **Package Manager**: Composer, NPM

## 📋 Requirements

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.x and NPM
-   SQLite (included) or MySQL/PostgreSQL
-   Web server (Apache/Nginx) or PHP built-in server

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/AlexanderPhan04/Market-Place.git
cd Market-Place
```

### 2. Install Dependencies

**Backend (Laravel)**

```bash
composer install
```

**Frontend (Assets)**

```bash
npm install
```

### 3. Environment Configuration

1. Copy the example environment file:

    ```bash
    cp .env.example .env
    ```

2. Generate the application key:

    ```bash
    php artisan key:generate
    ```

3. Configure your database settings in the `.env` file:

    ```env
    DB_CONNECTION=sqlite
    # Or use MySQL/PostgreSQL:
    # DB_CONNECTION=mysql
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=game_on
    # DB_USERNAME=root
    # DB_PASSWORD=
    ```

4. Run database migrations:

    ```bash
    php artisan migrate
    ```

5. (Optional) Seed the database with sample data:
    ```bash
    php artisan db:seed
    ```

### 4. Configure Google OAuth (Optional)

If you want to enable Google login:

1. Create a Google OAuth application at [Google Cloud Console](https://console.cloud.google.com/)
2. Add credentials to `.env`:
    ```env
    GOOGLE_CLIENT_ID=your_client_id
    GOOGLE_CLIENT_SECRET=your_client_secret
    GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
    ```

### 5. Configure Mail Settings (Optional)

For email verification and password reset:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@gameon.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🏃 Running the Application

### Development Server

**Option 1: Using Laravel's built-in server**

```bash
php artisan serve
```

**Option 2: Using Vite for asset compilation**

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

**Option 3: Using Composer dev script (all-in-one)**

```bash
composer dev
```

Navigate to `http://localhost:8000/`

### Production Build

```bash
# Build assets for production
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📁 Project Structure

```
Market-Place/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Application controllers
│   │   │   ├── Admin/            # Admin-specific controllers
│   │   │   ├── AuthController.php
│   │   │   ├── ChatController.php
│   │   │   ├── HomeController.php
│   │   │   └── ...
│   │   ├── Middleware/           # Custom middleware
│   │   └── DTOs/                 # Data Transfer Objects
│   ├── Models/                   # Eloquent models
│   ├── Policies/                 # Authorization policies
│   ├── Providers/                # Service providers
│   └── Services/                 # Business logic services
├── config/                       # Configuration files
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                 # Database seeders
├── public/                       # Public assets
├── resources/
│   ├── lang/                    # Language files (en, vi)
│   ├── views/                   # Blade templates
│   │   ├── admin/              # Admin views
│   │   ├── auth/               # Authentication views
│   │   ├── dashboard/          # Dashboard views
│   │   └── ...
│   ├── css/                    # Stylesheets
│   └── js/                     # JavaScript files
├── routes/
│   └── web.php                  # Web routes
└── tests/                       # Test files
```

## 👥 User Roles

-   **Super Admin**: Full system access, user management, system settings
-   **Admin**: User management, tournament/game/team management
-   **Player**: Can join teams, participate in tournaments, create posts
-   **Viewer**: Can view content, vote in honor system, basic interactions

## 🔐 Default Credentials

After running migrations, you can create a super admin user:

```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Super Admin',
    'email' => 'admin@gameon.com',
    'password' => Hash::make('password'),
    'user_role' => 'super_admin',
    'email_verified_at' => now(),
]);
```

## 🌐 API Endpoints

### Authentication

-   `POST /auth/login` - User login
-   `POST /auth/register` - User registration
-   `POST /auth/logout` - User logout
-   `GET /auth/google` - Google OAuth redirect
-   `GET /auth/google/callback` - Google OAuth callback

### Language

-   `POST /language/switch` - Switch application language
-   `GET /language/current` - Get current language

### Search

-   `GET /search` - Global search
-   `GET /search/view` - Search results view

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Or using PHPUnit directly
vendor/bin/phpunit
```

## 📝 Code Style

This project uses Laravel Pint for code formatting:

```bash
# Format code
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

## 🤝 Contributing

### Team Members

-   **Phan Nhật Quân** (Alexander Phan) - Team Lead
-   **Đỗ Thế An** - Developer
-   **Lê Khánh An** - Developer

### Contribution Guidelines

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 🐛 Troubleshooting

### Common Issues

**Issue: "Class not found" errors**

```bash
composer dump-autoload
```

**Issue: Assets not loading**

```bash
npm run build
php artisan view:clear
```

**Issue: Language not switching**

-   Clear cache: `php artisan config:clear`
-   Check session driver in `.env`
-   Verify `SetLocale` middleware is registered

**Issue: Database connection errors**

-   Check `.env` database configuration
-   Ensure database file exists (for SQLite): `touch database/database.sqlite`
-   Run migrations: `php artisan migrate:fresh`

## 📞 Support

For issues, questions, or contributions, please open an issue on the GitHub repository.

## 🎯 Roadmap

-   [ ] Real-time notifications system
-   [ ] Advanced tournament bracket visualization
-   [ ] Mobile app (React Native)
-   [ ] Live streaming integration
-   [ ] Payment gateway integration
-   [ ] Advanced analytics dashboard
-   [ ] API documentation (Swagger/OpenAPI)

## 🙏 Acknowledgments

-   Laravel Framework
-   Bootstrap Team
-   Font Awesome
-   All contributors and testers

---

**Made with ❤️ by the Game On development team**
