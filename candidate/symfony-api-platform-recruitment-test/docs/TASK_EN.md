# Backend Developer – Technical Test

## Background

You are joining a development team that maintains a PHP-based REST API.

The application uses:

- PHP 8.5
- Symfony 7.4 LTS
- API Platform 4
- Doctrine ORM
- PostgreSQL
- LexikJWTAuthenticationBundle
- Docker

You will receive a partially implemented project. The goal is not to build an application from scratch, but to extend and maintain an existing application.

**Estimated time: 1–2 hours.**

You do not need to write tests.

## Task

The application manages customers and their orders. The project already contains:

- User
- Customer
- Order

A Customer can have multiple Orders.

JWT authentication is partially configured using LexikJWTAuthenticationBundle.

### 1. Customer Orders API

Implement an API endpoint for retrieving a customer's orders:

`GET /api/customers/{id}/orders`

The response should contain the customer's orders as JSON.

First inspect the existing Entities, relationships, and API Platform structure.

### 2. Order filtering

Add the ability to filter a customer's orders by status:

`GET /api/customers/123/orders?status=pending`

Possible statuses are:

- pending
- processing
- completed
- cancelled

Filtering must happen at database level rather than loading all orders into PHP and filtering them afterwards.

Use the existing Repository structure where appropriate.

### 3. Authentication

The project already contains a partially configured LexikJWTAuthenticationBundle and user authentication.

Inspect the existing authentication implementation and make sure the orders API is accessible only to authenticated users.

You do not need to implement JWT token generation from scratch.

### 4. Error handling

Handle cases such as:

`GET /api/customers/999999/orders`

If the customer does not exist, the API should return an appropriate HTTP status rather than an empty successful response.

### 5. Maintainability

Your implementation should fit the existing project structure. Do not introduce an entirely new approach if the project already has an established way of doing something.

Pay particular attention to:

- Symfony services
- Dependency Injection
- Doctrine Entity relationships
- Repository structure
- API Platform
- HTTP status codes
- authentication
- code clarity

## Laravel – short theory section

Answer briefly. No code is required.

1. In Symfony, Dependency Injection is based on the Service Container. How is the equivalent concept handled in Laravel?
   Laravel also has a Service Container. Dependencies are usually injected through a class constructor or method, and Laravel resolves them automatically through the container. If an interface needs to be mapped to a concrete implementation, that binding is registered in a Service Provider.

2. In Laravel, you have Customer and Order models where one Customer can have many Orders. How would you define this relationship using Laravel Eloquent?
   This would be a one-to-many relationship. The Customer model would define a hasMany relationship to Order, and the Order model would define the inverse belongsTo relationship to Customer.

3. How would you implement `GET /api/customers/{id}/orders` in Laravel? Which Laravel components would you use?
   I would define the route in routes/api.php, handle it in a controller, and use Eloquent to retrieve the customer and its orders. If a status query parameter is provided, I would add a where condition to the Eloquent query so the filtering happens in the database. I would protect the route with Laravel authentication middleware and return the result as JSON.

## Bonus

If you complete the main tasks and still have time, you may:

- improve API error handling
- improve API documentation
- make the implementation more reusable
- consider a possible N+1 query problem
- make another justified improvement you notice while exploring the project

The bonus is optional.

## Submission

Return the modified project using the provided repository.

No separate documentation is required. If you make a non-obvious design decision, you may briefly explain it in code comments or the README.

The goal is not to produce as much code as possible, but to make a solution that fits the existing project and is easy to maintain.
