# FarmDialogue - Agricultural Platform

A comprehensive web application connecting farmers, customers, vendors, and gardening enthusiasts in a unified digital platform.

## Project Structure

```
farmdialogue/
├── front/              # Frontend (HTML, CSS, JavaScript)
├── back/               # Backend (PHP)
└── database/           # Database (MySQL)
```

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Version Control**: Git

## Features

1. **User Management**
   - User registration and authentication
   - User roles: Farmer, Customer, Vendor, Gardener, Admin
   - User profiles and verification

2. **Product Marketplace**
   - Browse farm produce and equipment
   - Create and manage product listings
   - Search and filter products
   - Price and quantity management

3. **Orders & Transactions**
   - Place and manage orders
   - Order tracking
   - Payment processing
   - Transaction history

4. **Communication**
   - Direct messaging between users
   - Message notifications
   - Communication history

5. **Learning Hub**
   - Gardening tutorials and guides
   - Agricultural articles
   - Video resources
   - Educational content management

6. **Vendor Management**
   - Vendor registration and verification
   - Business profile management
   - Vendor dashboard
   - Equipment listings

## Setup Instructions

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache with mod_rewrite enabled)
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd farmdialogue
   ```

2. **Database Setup**
   - Open MySQL client or phpMyAdmin
   - Execute the schema file:
     ```bash
     mysql -u root -p < database/schema.sql
     ```
   - Update database credentials in `back/config.php`

3. **Backend Configuration**
   - Open `back/config.php`
   - Update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', 'your_password');
     ```
   - Change JWT_SECRET to a strong key

4. **Frontend Setup**
   - No build process required
   - Ensure all files in `front/` are accessible via web server

5. **Web Server Setup**
   - Point web root to project directory
   - Ensure `/uploads` directory is writable
   - Enable mod_rewrite for Apache

### Running the Application

1. Start your web server (Apache/Nginx)
2. Navigate to: `http://localhost/farmdialogue/front/`
3. Use test credentials or register a new account

## API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout

### Users
- `GET /api/users/list` - Get user list
- `GET /api/users/profile` - Get user profile
- `PUT /api/users/profile` - Update user profile

### Products
- `GET /api/products/list` - Get product list
- `POST /api/products/create` - Create product
- `GET /api/products/{id}` - Get product details
- `PUT /api/products/{id}` - Update product

### Orders
- `GET /api/orders/list` - Get orders
- `POST /api/orders/create` - Create order
- `PUT /api/orders/{id}` - Update order

### Messages
- `GET /api/messages/list` - Get messages
- `POST /api/messages/send` - Send message
- `GET /api/messages/{id}` - Get message thread

### Vendors
- `GET /api/vendors/list` - Get vendors
- `POST /api/vendors/register` - Register vendor

## File Structure

### Frontend (`front/`)
- `index.html` - Homepage
- `login.html` - Login page
- `register.html` - Registration page
- `styles.css` - Global styles
- `script.js` - Global JavaScript functions

### Backend (`back/`)
- `config.php` - Database and app configuration
- `index.php` - Main router
- `api/` - API endpoint handlers (to be created)
- `models/` - Database models (to be created)
- `controllers/` - Business logic (to be created)

### Database (`database/`)
- `schema.sql` - Database schema and initial tables

## Security Considerations

1. Change default JWT secret in `config.php`
2. Use HTTPS in production
3. Implement input validation and sanitization
4. Hash passwords using bcrypt
5. Set appropriate file permissions
6. Regularly update dependencies
7. Implement rate limiting for API endpoints
8. Use prepared statements to prevent SQL injection

## Development Workflow

### Agile Methodology
This project follows Agile/Scrum practices with:
- 2-3 week sprints
- Daily standups
- Sprint planning and reviews
- Continuous integration and testing

### Branching Strategy
- `main` - Production-ready code
- `develop` - Development branch
- `feature/*` - Feature branches
- `bugfix/*` - Bug fix branches

## Testing

Run tests using:
```bash
php artisan test  # If using Laravel (update as needed)
```

## Deployment

### Development
- Local development server

### Production
- Configure production database
- Enable error logging
- Disable debug mode
- Set up SSL certificate
- Configure backup strategy

## Contributions

1. Create a feature branch
2. Make your changes
3. Submit a pull request
4. Await code review

## License

All rights reserved - FarmDialogue 2026

## Support & Contact

For issues and questions:
- Email: support@farmdialogue.com
- GitHub Issues: [Create an issue]
- Documentation: [Link to wiki]

## Team

- Scrum Master: [Name]
- Product Owner: [Name]
- Developers: [Names]

## Changelog

### Version 1.0.0
- Initial project setup
- Database schema design
- Frontend structure
- Backend routing foundation
- API endpoint templates

---

**Last Updated**: March 2026
**Status**: In Development (Sprint 1)
