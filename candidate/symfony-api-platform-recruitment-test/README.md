## Candidate Instructions

- `docs/TEHTAVA_FI.md` – Finnish candidate instructions
- `docs/TASK_EN.md` – English candidate instructions

## Quick Start with Docker

### Prerequisites
- Docker and Docker Compose installed

### Running the Application

```bash
# Start Docker environment
docker compose up --build --wait

# Wait for services to be ready (30-60 seconds)
# The API will be available at https://localhost
```

During startup:
- ✅ Database is created
- ✅ Migrations run automatically
- ✅ Seed data is generated (5-15 random customers, products, orders)

### Testing the API

1. **Check available endpoints:**
   - https://localhost/docs (Swagger UI)
   - https://localhost/customers (list customers)
   - https://localhost/products (list products)
   - https://localhost/orders (list orders)
   - http://localhost:8080 (Adminer database management)

2. **Create a test password for a customer**
    Run the following command and follow the prompts to create a hashed password for a customer:
    ```bash
    bin/console security:hash-password
    ```
    Update the customer's password in the database with the hashed password generated.

3. **Authenticate with JWT token:**
   ```bash
   curl -X POST https://localhost/auth/login \
     -H "Content-Type: application/json" \
     -d '{
       "username": "<customer-uuid-from-database>",
       "password": "<password-from-seed-data>"
     }'
   ```

4. **Use token in protected endpoints:**
   ```bash
   curl https://localhost/customers/123/orders \
     -H "Authorization: Bearer <your-jwt-token>"
   ```

### Task Overview

Implement the following features (see `docs/TASK_EN.md` for details):

1. **Customer Orders Endpoint** – `GET /customers/{id}/orders`
2. **Order Filtering** – Add status filter: `?status=pending`
3. **Authentication** – Ensure endpoints require JWT token
4. **Error Handling** – Return proper HTTP status codes
5. **Maintainability** – Follow existing project structure

### Technology Stack

- PHP 8.5
- Symfony 7.4 LTS
- API Platform 4
- Doctrine ORM
- PostgreSQL
- LexikJWTAuthenticationBundle
- Docker (FrankenPHP + Caddy)
