# FarmDialogue Ghana - Node.js Backend

Node.js/Express/MongoDB backend for the FarmDialogue Ghana agricultural marketplace platform.

## Features

- ✅ User authentication (register/login) with JWT
- ✅ Password hashing with bcrypt (cost factor 10)
- ✅ Product CRUD operations with ownership checks
- ✅ Text search on products (name + description)
- ✅ Category filtering and pagination
- ✅ Role-based access (farmer, customer, vendor, gardener)
- ✅ Rate limiting on login endpoint (5 attempts per 15 min)
- ✅ CORS configured for frontend
- ✅ Security headers with Helmet

## Prerequisites

- Node.js (v16 or higher)
- MongoDB (v5 or higher) running locally or remote connection string

## Installation

1. Install dependencies:
```bash
cd back
npm install
```

2. Configure environment variables:
Edit `.env` file and update:
- `MONGODB_URI` - Your MongoDB connection string
- `JWT_SECRET` - A strong random secret key
- `PORT` - Server port (default: 5000)
- `FRONTEND_URL` - Your frontend URL for CORS

3. Start MongoDB (if running locally):
```bash
mongod
```

4. Start the server:
```bash
# Development mode (with auto-reload)
npm run dev

# Production mode
npm start
```

The server will start on `http://localhost:5000`

## API Endpoints

### Authentication

#### Register
```http
POST /api/auth/register
Content-Type: application/json

{
  "firstName": "Kwame",
  "lastName": "Mensah",
  "email": "kwame@example.com",
  "phone": "+233201234567",
  "role": "farmer",
  "password": "securepass123"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "kwame@example.com",
  "password": "securepass123"
}
```

### Products

#### List Products (with filters)
```http
GET /api/products?category=produce&search=tomato&limit=20&offset=0
```

#### Get Single Product
```http
GET /api/products/:id
```

#### Create Product (requires auth)
```http
POST /api/products
Authorization: Bearer <token>
Content-Type: application/json

{
  "productName": "Fresh Tomatoes",
  "description": "Ripe tomatoes from Ashanti region",
  "category": "produce",
  "price": 15.00,
  "quantityAvailable": 50,
  "unit": "kg"
}
```

#### Update Product (requires auth + ownership)
```http
PUT /api/products/:id
Authorization: Bearer <token>
Content-Type: application/json

{
  "price": 12.00,
  "quantityAvailable": 30
}
```

#### Delete Product (requires auth + ownership)
```http
DELETE /api/products/:id
Authorization: Bearer <token>
```

## Project Structure

```
back/
├── src/
│   ├── config/
│   │   └── db.js              # MongoDB connection
│   ├── models/
│   │   ├── User.js            # User schema
│   │   └── Product.js         # Product schema
│   ├── controllers/
│   │   ├── authController.js  # Auth logic
│   │   └── productController.js # Product logic
│   ├── middleware/
│   │   └── auth.js            # JWT verification
│   ├── routes/
│   │   ├── auth.js            # Auth routes
│   │   └── products.js        # Product routes
│   └── app.js                 # Express app setup
├── .env                       # Environment variables
├── package.json
└── server.js                  # Entry point
```

## Frontend Integration

Update the frontend's base URL from:
```javascript
const baseURL = 'http://localhost/farmdialogue/back/api/';
```

To:
```javascript
const baseURL = 'http://localhost:5000/api/';
```

The API response format matches the old PHP backend, so no other frontend changes are needed.

## User Roles

- `farmer` - Can list and sell agricultural products
- `customer` - Can browse and purchase products
- `vendor` - Can sell equipment and supplies
- `gardener` - Gardening enthusiasts

## Product Categories

- `produce` - Fresh produce
- `seeds` - Seeds and plants
- `equipment` - Farm equipment
- `fertilizer` - Fertilizers
- `tools` - Gardening tools

## Security Features

- Passwords hashed with bcrypt (cost factor 10)
- JWT tokens expire after 1 hour
- Rate limiting on login (5 attempts per 15 minutes)
- CORS configured for specific frontend origin
- Helmet security headers
- Input validation on all endpoints
- Ownership checks on update/delete operations

## Development

The backend uses:
- Express.js for HTTP server and routing
- Mongoose for MongoDB ODM
- bcryptjs for password hashing
- jsonwebtoken for JWT authentication
- helmet for security headers
- cors for cross-origin requests
- express-rate-limit for rate limiting

## Troubleshooting

### MongoDB Connection Issues
- Ensure MongoDB is running: `mongod`
- Check connection string in `.env`
- Verify MongoDB port (default: 27017)

### CORS Errors
- Update `FRONTEND_URL` in `.env` to match your frontend URL
- Ensure frontend sends requests to `http://localhost:5000/api/`

### JWT Token Issues
- Ensure `JWT_SECRET` is set in `.env`
- Check token expiry (tokens expire after 1 hour)
- Verify Authorization header format: `Bearer <token>`

## License

ISC
