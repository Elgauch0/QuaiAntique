# 🍽️ Quai Antique - Gourmet Reservation System

Welcome to **Quai Antique**, a comprehensive web application developed for Chef Arnaud Michant's restaurant. This project showcases modern Symfony ecosystem mastery, relational database management, and containerized deployment.

## 🚀 Architecture & Technologies

This project is built on a cutting-edge infrastructure based on the **Symfony Docker** stack from [Kévin Dunglas](https://github.com/dunglas/symfony-docker).

### 🛠️ The Engine: FrankenPHP & Caddy
Instead of a traditional Nginx + PHP-FPM setup, this project utilizes **FrankenPHP**, a next-generation PHP application server written in Go.

**Why this choice?**
* **Integrated Caddy Server**: Natively handles HTTP/3 protocol and automatic TLS certificate renewal.
* **Simplicity & Performance**: FrankenPHP combines web server and PHP interpreter into a single Docker service, simplifying maintenance and improving responsiveness.
* **Scalability**: While the application currently runs in standard mode, FrankenPHP offers the ability to activate *Worker Mode* without infrastructure changes, allowing performance multiplication if restaurant traffic increases.

### 🐘 Technical Stack
* **Symfony 7.3**: Leveraging the latest framework features (PHP 8 Attributes, Services, Autowiring).
* **PostgreSQL**: Robust data management.
* **VichUploaderBundle**: Optimized image upload handling for the gallery.
* **Stimulus & UX Turbo**: Modern JavaScript integration for dynamic interactions.

## 📖 Key Features

### 👤 User Management
- **Registration & Authentication**: Secure user accounts with role-based access.
- **Profile Management**: Users can update personal information and view reservation history.
- **Admin Creation**: Dedicated command to create administrator account.

### 📅 Reservation System
- **Smart Booking**: Logic to manage available seats per service with capacity limits.
- **Real-time Validation**: Checks against maximum guests per time slot.
- **Allergy Information**: Users can specify dietary restrictions.
- **Booking History**: Complete reservation tracking for users.

### 🍽️ Menu Management (Admin)
- **Category Organization**: Hierarchical menu structure.
- **Dish Management**: CRUD operations for menu items with image uploads.
- **Dynamic Pricing**: Flexible pricing system.

### 🖼️ Gallery System
- **Image Upload**: Admin interface for showcasing restaurant highlights.
- **Responsive Display**: Optimized image presentation.

### ⚙️ Restaurant Settings (Admin)
- **Operating Hours**: Configurable lunch and dinner service times.
- **Capacity Management**: Adjustable maximum guest limits.
- **Flexible Configuration**: Easy-to-update restaurant parameters.

### 🔒 Security & Roles
- **Role-Based Access**: Protected routes for `ROLE_ADMIN` and `ROLE_USER`.
- **Authentication System**: Secure login/logout with Symfony Security.
- **Flash Messages**: User-friendly feedback for actions.

## 🏗️ Project Structure

```
├── app/                    # Symfony application
│   ├── src/
│   │   ├── Controller/     # Route controllers
│   │   ├── Entity/         # Doctrine entities
│   │   ├── Form/           # Symfony forms
│   │   ├── Repository/     # Doctrine repositories
│   │   └── Service/        # Business logic services
│   ├── templates/          # Twig templates
│   ├── assets/             # Frontend assets (JS, CSS, images)
│   └── config/             # Symfony configuration
├── frankenphp/             # FrankenPHP configuration
├── docker-compose.yml      # Docker services
├── Dockerfile              # Application container
└── setup.sh               # Installation script
```

## 🗄️ Database Schema

### Core Entities
- **User**: Authentication, roles, guest count, allergies
- **Booking**: Reservations with datetime, guest count, client relation
- **Dish**: Menu items with images, pricing, categories
- **Category**: Menu organization
- **Image**: Gallery images
- **RestaurantSettings**: Operating hours and capacity

## 📦 Installation & Setup

### Prerequisites
- Docker & Docker Compose
- Git

### Quick Start

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Elgauch0/QuaiAntique.git
   cd QuaiAntique
   ```

2. **Run setup script**:
   ```bash
   chmod +x setup.sh
   ./setup.sh
   ```

3. **Wait for containers to start**, then run migrations:
   ```bash
   docker compose exec php bin/console doctrine:migrations:migrate
   ```

4. **Create admin user**:
   ```bash
   docker compose exec php bin/console app:create-admin
   ```

5. **Access the application**:
   - Main site: `http://localhost`
   - Admin panel: Login with admin credentials and access `/admin/*` routes

### Development Commands

```bash
# Start services
docker compose up -d

# Stop services
docker compose down

# View logs
docker compose logs -f

# Access PHP container
docker compose exec php bash

# Run tests
docker compose exec php bin/phpunit

# Clear cache
docker compose exec php bin/console cache:clear
```

## 🔧 Configuration

### Environment Variables
Key configuration in `.env`:
- `DATABASE_URL`: PostgreSQL connection string
- `SERVER_NAME`: Domain configuration
- `POSTGRES_PASSWORD`: Database password

### Docker Services
- **php**: FrankenPHP application server
- **db**: PostgreSQL database

## 🧪 Testing

Run the test suite:
```bash
docker compose exec php bin/phpunit
```

## 📚 API Endpoints

### Public Routes
- `/`: Homepage
- `/register`: User registration
- `/login`: Authentication
- `/gallerie`: Image gallery
- `/dish`: Menu display

### User Routes (Authenticated)
- `/user/reservation`: Reservation management
- `/user/profil`: Profile editing

### Admin Routes (ROLE_ADMIN)
- `/admin/category`: Category management
- `/admin/dish`: Menu item management
- `/admin/gallerie`: Gallery management
- `/admin/settings`: Restaurant configuration

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the GNU General Public License v3.0 (GPLv3).

You are free to use, modify, and distribute this software, provided that any derivative work is also distributed under the same GPL license.


## 🙏 Acknowledgments

- Built with the excellent [Symfony Docker](https://github.com/dunglas/symfony-docker) template
- Special thanks to Chef Arnaud Michant for the inspiration
- Symfony community for the robust framework