# Game On - Pro Gaming Platform

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.1-38bdf8.svg)
![Vite](https://img.shields.io/badge/Vite-7.0-646cff.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

**Game On** is a professional esports management platform designed for gamers, teams, and tournaments. Built with Laravel 12 and modern web technologies, it provides a comprehensive solution for managing esports activities, teams, tournaments, and community interactions, featuring integrated marketplace and VNPay payment system.

## 🎮 Features

### Core Features

-   **Advanced User Management System**

    -   Multi-role system (Super Admin, Admin, Participant)
    -   User registration with email verification
    -   Google OAuth integration (Laravel Socialite)
    -   Profile management with avatar support
    -   User activity tracking and last login monitoring
    -   Account status management (active/inactive)
    -   Password reset functionality

-   **Team Management**

    -   Create and manage teams
    -   Team member invitations and management
    -   Role assignment (Captain, Member)
    -   Team statistics and achievements
    -   Team status tracking
    -   Join/Leave team functionality
    -   Transfer captain permissions
    -   Kick member capabilities

-   **Tournament Management**

    -   Create and organize tournaments
    -   Tournament brackets and scheduling
    -   Real-time result management
    -   Tournament status tracking
    -   Tournament registration system
    -   Match management
    -   Competition type support (individual, team, mixed)
    -   Tournament settings customization

-   **Game Management**

    -   Add and manage games
    -   Game categories and details
    -   Game-specific tournament settings
    -   Game status management

-   **Honor System**

    -   Voting system for players/teams
    -   Event-based and free mode voting
    -   Real-time vote tracking
    -   Honor event management
    -   Role-based voting weights
    -   Anonymous voting support
    -   Vote results and statistics

-   **Marketplace & E-commerce**

    -   Digital product marketplace
    -   Product categories (themes, stickers, game items)
    -   Shopping cart system
    -   User inventory management
    -   Item equipping system
    -   Product search and filtering
    -   Admin product management
    -   Order tracking and management

-   **VNPay Payment Integration**

    -   Secure payment gateway integration
    -   Sandbox and production environment support
    -   Order payment processing
    -   Donation payment support
    -   Payment return handling
    -   IPN (Instant Payment Notification) support
    -   Transaction query functionality
    -   Payment status tracking

-   **Donation System**

    -   Peer-to-peer donations
    -   Anonymous donation support
    -   Donation tracking and history
    -   VNPay payment integration
    -   Donation message support

-   **Social Features**

    -   Posts and comments system
    -   Reactions and likes (multiple reaction types)
    -   Post media uploads
    -   Mentions and notifications
    -   Post visibility controls
    -   Comment reactions
    -   Share functionality
    -   Post counters and statistics

-   **Real-time Chat System**

    -   Direct messaging
    -   Group conversations
    -   Message reactions
    -   Online status tracking
    -   Typing indicators
    -   Message edit and delete
    -   Conversation history
    -   Read receipts
    -   User blocking
    -   Message reporting

-   **Admin Panel**

    -   **User Management**: CRUD operations, status updates, bulk actions, export
    -   **Team Management**: Team overview, status management, bulk operations
    -   **Tournament Management**: Create and manage tournaments
    -   **Game Management**: Add/edit/delete games
    -   **Honor Management**: Manage voting events
    -   **Marketplace Management**: Product CRUD, status toggle
    -   **Admin Invitation System**: Invite new admins via email
    -   **Permission Management**: Granular permission system for admins
    -   **System Settings**: Theme management, cache control
    -   **Analytics Dashboard**: User statistics, activity distribution

-   **Super Admin Features**

    -   Full system access
    -   Admin invitation and management
    -   Permission assignment and revocation
    -   System settings management
    -   Analytics and reports
    -   Backup management (in development)
    -   System logs access
    -   Cache control

-   **Search Functionality**

    -   Global search across users, teams, tournaments, and games
    -   Advanced filtering options
    -   Quick search shortcuts
    -   Search results view

-   **Multi-language Support**

    -   English (EN)
    -   Vietnamese (VI)
    -   Dynamic language switching
    -   Session-based locale management

-   **Dashboard Analytics**
    -   User statistics and growth
    -   Team performance metrics
    -   Tournament analytics
    -   Activity distribution charts
    -   Recent users tracking
    -   Role-based dashboard views

## 🛠️ Technology Stack

### Backend

-   **Framework**: Laravel 12.0
-   **PHP**: 8.2+
-   **Database**: SQLite (default) / MySQL / PostgreSQL
-   **Authentication**: Laravel Session Auth + Google OAuth (Laravel Socialite)
-   **Payment Gateway**: VNPay Integration
-   **ORM**: Eloquent

### Frontend

-   **CSS Framework**: Tailwind CSS 4.1
-   **JavaScript**: Vanilla JS + Axios
-   **Build Tool**: Vite 7.0
-   **Icons**: Font Awesome
-   **CSS Preprocessor**: Sass

### Development Tools

-   **Code Quality**: Laravel Pint
-   **Testing**: PHPUnit
-   **Package Manager**: Composer, NPM
-   **Task Runner**: Concurrently (for dev environment)

## 📋 Requirements

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.x and NPM
-   SQLite (included) or MySQL/PostgreSQL
-   Web server (Apache/Nginx) or PHP built-in server

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/AlexanderPhan04/GameOn.git
cd GameOn
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

### 6. Configure VNPay Payment (Optional)

For marketplace and donation payment functionality:

1. Get your VNPay credentials from [VNPay Sandbox](https://sandbox.vnpayment.vn/)
2. Add to `.env`:

```env
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL=http://localhost:8000/payment/vnpay/return
VNPAY_API_URL=http://sandbox.vnpayment.vn/merchant_webapi/merchant.html
VNPAY_IPN_URL=http://localhost:8000/payment/vnpay/ipn
VNPAY_ENVIRONMENT=sandbox
VNPAY_EXPIRE_MINUTES=15
```

For production, see [VNPAY_SETUP.md](VNPAY_SETUP.md) for detailed configuration.

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
MarketPlace/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Application controllers
│   │   │   ├── Admin/            # Admin-specific controllers
│   │   │   │   ├── AdminInvitationController.php
│   │   │   │   ├── HonorManagementController.php
│   │   │   │   ├── MarketplaceController.php
│   │   │   │   ├── SystemController.php
│   │   │   │   ├── TeamManagementController.php
│   │   │   │   └── UserManagementController.php
│   │   │   ├── AuthController.php
│   │   │   ├── ChatController.php
│   │   │   ├── GameManagementController.php
│   │   │   ├── HomeController.php
│   │   │   ├── HonorController.php
│   │   │   ├── LanguageController.php
│   │   │   ├── MaintenanceController.php
│   │   │   ├── MarketplaceController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── PostsController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── SearchController.php
│   │   │   ├── TeamController.php
│   │   │   ├── TournamentController.php
│   │   │   └── TournamentManagementController.php
│   │   ├── Middleware/           # Custom middleware
│   │   │   ├── CheckParticipantRole.php
│   │   │   ├── DatabaseMaintenanceMiddleware.php
│   │   │   ├── EnsureEmailIsVerified.php
│   │   │   ├── SessionAuthMiddleware.php
│   │   │   ├── SetLocale.php
│   │   │   └── TrackLastLogin.php
│   │   ├── DTOs/                 # Data Transfer Objects
│   │   └── Results/              # Result objects
│   ├── Models/                   # Eloquent models
│   │   ├── AdminInvitation.php
│   │   ├── AdminPermission.php
│   │   ├── ChatConversation.php
│   │   ├── ChatMessage.php
│   │   ├── ChatMessageReaction.php
│   │   ├── ChatParticipant.php
│   │   ├── Donation.php
│   │   ├── Game.php
│   │   ├── HonorEvent.php
│   │   ├── HonorVote.php
│   │   ├── MarketplaceOrder.php
│   │   ├── MarketplaceOrderItem.php
│   │   ├── MarketplaceProduct.php
│   │   ├── Post.php
│   │   ├── PostComment.php
│   │   ├── ProductCategory.php
│   │   ├── Team.php
│   │   ├── Tournament.php
│   │   ├── TournamentMatch.php
│   │   ├── TournamentRegistration.php
│   │   ├── Transaction.php
│   │   ├── User.php
│   │   ├── UserInventory.php
│   │   └── UserProfile.php
│   ├── Policies/                 # Authorization policies
│   ├── Providers/                # Service providers
│   ├── Services/                 # Business logic services
│   │   └── VnpayService.php
│   ├── Mail/                     # Email classes
│   │   ├── AdminInvitationMail.php
│   │   ├── ForgotPasswordEmail.php
│   │   └── VerifyEmail.php
│   └── helpers.php               # Helper functions
├── config/                       # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── filesystems.php
│   ├── mail.php
│   ├── services.php
│   └── vnpay.php                 # VNPay configuration
├── database/
│   ├── migrations/               # Database migrations (70+ files)
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
├── public/                       # Public assets
│   ├── build/                    # Compiled assets
│   ├── contributors/             # Contributor assets
│   └── css/                      # Additional CSS
├── resources/
│   ├── lang/                     # Language files
│   │   ├── en/                   # English translations
│   │   └── vi/                   # Vietnamese translations
│   ├── views/                    # Blade templates
│   │   ├── admin/                # Admin views
│   │   │   ├── admins/           # Admin management
│   │   │   ├── games/            # Game management
│   │   │   ├── honor/            # Honor management
│   │   │   ├── marketplace/      # Marketplace management
│   │   │   ├── teams/            # Team management
│   │   │   ├── tournaments/      # Tournament management
│   │   │   └── users/            # User management
│   │   ├── auth/                 # Authentication views
│   │   ├── chat/                 # Chat interface
│   │   ├── dashboard/            # Dashboard views
│   │   ├── honor/                # Honor system views
│   │   ├── marketplace/          # Marketplace views
│   │   ├── payment/              # Payment views
│   │   ├── posts/                # Social posts views
│   │   ├── profile/              # User profile views
│   │   ├── teams/                # Team views
│   │   ├── tournaments/          # Tournament views
│   │   └── layouts/              # Layout templates
│   ├── css/                      # Stylesheets
│   │   └── app.css
│   └── js/                       # JavaScript files
│       └── app.js
├── routes/
│   ├── web.php                   # Web routes (315 lines)
│   └── console.php               # Console commands
├── tests/                        # Test files
│   ├── Feature/
│   └── Unit/
├── docs/                         # Documentation
│   └── DDD_DATABASE_REPORT.md
├── composer.json                 # PHP dependencies
├── package.json                  # NPM dependencies
├── tailwind.config.js            # Tailwind configuration
├── vite.config.js                # Vite configuration
├── VNPAY_SETUP.md               # VNPay setup guide
└── README.md                     # This file
```

## 👥 User Roles

-   **Super Admin**: Full system access, admin management, permission assignment, system settings
-   **Admin**: User/team/tournament/game management, marketplace management (with assigned permissions)
-   **Participant**: Can join teams, participate in tournaments, create posts, purchase items, receive donations

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
-   `GET /auth/verify-email/{token}` - Email verification
-   `POST /auth/resend-verification` - Resend verification email
-   `POST /auth/forgot-password` - Request password reset
-   `POST /auth/reset-password` - Reset password

### Language

-   `POST /language/switch` - Switch application language
-   `GET /language/current` - Get current language

### Search

-   `GET /search` - Global search
-   `GET /search/view` - Search results view

### Teams

-   `GET /teams` - List all teams
-   `POST /teams` - Create team (Participant)
-   `GET /teams/{team}` - View team details
-   `PUT /teams/{team}` - Update team (Captain)
-   `DELETE /teams/{team}` - Delete team (Captain)
-   `POST /teams/{team}/join` - Join team
-   `POST /teams/{team}/leave` - Leave team
-   `POST /teams/{team}/transfer-captain` - Transfer captain role
-   `POST /teams/{team}/kick-member` - Kick member
-   `POST /teams/{team}/invite-member` - Invite member

### Tournaments

-   `GET /tournaments` - List all tournaments
-   `GET /tournaments/{tournament}` - View tournament details
-   `POST /tournaments/{tournament}/register` - Register for tournament

### Posts & Social

-   `GET /posts` - List posts
-   `POST /posts` - Create post
-   `PUT /posts/{post}` - Update post
-   `DELETE /posts/{post}` - Delete post
-   `POST /posts/{post}/like` - Toggle like
-   `POST /posts/{post}/react` - Add reaction
-   `POST /posts/{post}/comment` - Add comment
-   `POST /comments/{comment}/like` - Toggle comment like
-   `POST /comments/{comment}/react` - Add comment reaction

### Chat

-   `GET /chat` - Chat interface
-   `GET /chat/conversation/{conversation}` - View conversation
-   `POST /chat/start-conversation` - Start new conversation
-   `POST /chat/create-group` - Create group chat
-   `POST /chat/conversation/{conversation}/send` - Send message
-   `POST /chat/message/{message}/react` - React to message
-   `DELETE /chat/message/{message}` - Delete message
-   `PUT /chat/message/{message}` - Edit message

### Marketplace

-   `GET /marketplace` - List products
-   `GET /marketplace/product/{id}` - Product details
-   `POST /marketplace/cart/{id}` - Add to cart
-   `GET /marketplace/cart` - View cart
-   `DELETE /marketplace/cart/{id}` - Remove from cart
-   `GET /marketplace/checkout` - Checkout page
-   `POST /marketplace/process-payment` - Process payment
-   `GET /marketplace/inventory` - User inventory
-   `POST /marketplace/inventory/{id}/equip` - Equip item
-   `POST /marketplace/donate/{userId}` - Donate to user

### Payment (VNPay)

-   `POST /payment/vnpay/create` - Create payment
-   `GET /payment/vnpay/return` - Payment return callback
-   `POST /payment/vnpay/ipn` - Payment IPN notification
-   `POST /payment/vnpay/query` - Query transaction

### Admin - User Management

-   `GET /admin/users` - List users
-   `POST /admin/users` - Create user
-   `GET /admin/users/{user}` - View user
-   `PUT /admin/users/{user}` - Update user
-   `DELETE /admin/users/{user}` - Delete user
-   `PATCH /admin/users/{user}/status` - Update user status
-   `POST /admin/users/bulk-update` - Bulk update users
-   `GET /admin/users-export` - Export users

### Admin - Game Management

-   `GET /admin/games` - List games
-   `POST /admin/games` - Create game
-   `PUT /admin/games/{game}` - Update game
-   `DELETE /admin/games/{game}` - Delete game

### Admin - Tournament Management

-   `GET /admin/tournaments` - List tournaments
-   `POST /admin/tournaments` - Create tournament
-   `PUT /admin/tournaments/{tournament}` - Update tournament
-   `DELETE /admin/tournaments/{tournament}` - Delete tournament

### Admin - Marketplace Management

-   `GET /admin/marketplace` - List products
-   `POST /admin/marketplace` - Create product
-   `PUT /admin/marketplace/{id}` - Update product
-   `DELETE /admin/marketplace/{id}` - Delete product
-   `PATCH /admin/marketplace/{id}/toggle-status` - Toggle product status

### Admin - Honor Management

-   `GET /admin/honor` - List honor events
-   `POST /admin/honor` - Create honor event
-   `PUT /admin/honor/{event}` - Update honor event
-   `DELETE /admin/honor/{event}` - Delete honor event
-   `PATCH /admin/honor/{event}/toggle` - Toggle event status
-   `DELETE /admin/honor/{event}/reset` - Reset votes

### Super Admin - Admin Management

-   `GET /admin/admins` - List admins and invitations
-   `POST /admin/admins` - Send admin invitation
-   `GET /admin/admins/{user}/edit-permissions` - Edit admin permissions
-   `PUT /admin/admins/{user}/update-permissions` - Update admin permissions
-   `DELETE /admin/admins/{user}/revoke` - Revoke admin access
-   `DELETE /admin/admins/invitation/{invitation}/cancel` - Cancel invitation
-   `POST /admin/admins/invitation/{invitation}/resend` - Resend invitation

### Super Admin - System Management

-   `GET /admin/system/settings` - System settings
-   `POST /admin/system/settings` - Update settings
-   `POST /admin/system/update-theme` - Update theme
-   `GET /admin/system/logs` - View logs
-   `POST /admin/system/clear-cache` - Clear cache
-   `GET /admin/system/analytics` - System analytics

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
php artisan config:clear
```

**Issue: Language not switching**

-   Clear cache: `php artisan config:clear`
-   Check session driver in `.env`
-   Verify `SetLocale` middleware is registered

**Issue: Database connection errors**

-   Check `.env` database configuration
-   Ensure database file exists (for SQLite): `touch database/database.sqlite`
-   Run migrations: `php artisan migrate:fresh`

**Issue: VNPay payment not working**

-   Verify VNPay credentials in `.env`
-   Check VNPAY_RETURN_URL and VNPAY_IPN_URL are accessible
-   Review logs in `storage/logs/laravel.log`
-   Ensure proper hash secret configuration

**Issue: Permission denied errors**

```bash
# On Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# On Windows (PowerShell as Admin)
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

**Issue: Email not sending**

-   Check MAIL configuration in `.env`
-   Verify SMTP credentials
-   Check `storage/logs/laravel.log` for email errors
-   Test with Mailtrap for development

**Issue: Chat messages not appearing**

-   Clear browser cache
-   Check user online status
-   Verify conversation participants
-   Check database for message records

## 📞 Support

For issues, questions, or contributions, please open an issue on the GitHub repository.

## 🎯 Roadmap

### Completed ✅

-   ✅ User authentication and authorization
-   ✅ Multi-role system (Super Admin, Admin, Participant)
-   ✅ Team management system
-   ✅ Tournament management
-   ✅ Honor/Voting system
-   ✅ Real-time chat system
-   ✅ Social posts and interactions
-   ✅ Marketplace with VNPay integration
-   ✅ Donation system
-   ✅ Admin panel with permission system
-   ✅ Multi-language support
-   ✅ Email verification and password reset
-   ✅ Google OAuth integration

### In Progress 🚧

-   🚧 Advanced tournament bracket visualization
-   🚧 System backup and restore functionality
-   🚧 Advanced analytics and reporting

### Planned 📋

-   [ ] Real-time WebSocket notifications
-   [ ] Mobile app (React Native)
-   [ ] Live streaming integration
-   [ ] API documentation (Swagger/OpenAPI)
-   [ ] Advanced tournament scheduling
-   [ ] Team ranking system
-   [ ] Achievement badges system
-   [ ] Enhanced marketplace features
-   [ ] Advanced chat features (voice/video)
-   [ ] Community forums

## 🙏 Acknowledgments

-   Laravel Framework
-   Tailwind CSS Team
-   Vite Team
-   Font Awesome
-   VNPay Payment Gateway
-   All contributors and testers

---

**Made with ❤️ by the Game On development team**

## 📚 Additional Resources

-   [VNPay Setup Guide](VNPAY_SETUP.md) - Detailed VNPay integration guide
-   [Database Documentation](docs/DDD_DATABASE_REPORT.md) - Database structure and relationships
-   [Laravel Documentation](https://laravel.com/docs/12.x) - Official Laravel docs
-   [Tailwind CSS Documentation](https://tailwindcss.com/docs) - Tailwind CSS guide

## 🔒 Security

If you discover any security-related issues, please email the development team instead of using the issue tracker.

## 📄 Changelog

See the project's commit history for detailed changes and updates.

**Version**: 1.0.0  
**Last Updated**: January 2026
