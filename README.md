# Contact Management System Backend Test

## Objectives

✅ **Back-end Assessment**: Make all tests pass, applying Laravel best practices, SOLID principles and clean architecture 

## Backend Status: COMPLETED

```bash
Tests: 66 passed (177 assertions)
Duration: 0.62s
```

## � Additional Features Implemented

### Architecture & Design Patterns

- **Clean Architecture** with clear layer separation
- **Repository Pattern** for data abstraction
- **Use Cases** for business logic encapsulation
- **DTOs** for safe data transfer
- **Service Layer** for specialized operations
- **SOLID Principles** applied throughout

### Enhanced Functionality

- **Advanced Search System**: Multi-field search (name, email, phone) with case-insensitive partial matching
- **Soft Delete System**: Logical deletion with restore capability and trash management
- **Robust Validation**: Email uniqueness, phone normalization, comprehensive field validation
- **Exception Handling**: Custom exceptions with proper HTTP responses
- **Pagination**: Optimized 10-items-per-page with validation

### Security Features

- **SQL Injection Prevention**: Eloquent ORM with parameter binding
- **Mass Assignment Protection**: Fillable attributes defined on models
- **Input Validation**: Comprehensive request validation rules
- **Email Validation**: Proper email format and uniqueness checks
- **Phone Sanitization**: Automatic phone number normalization
- **Rate Limiting**: 60 requests per minute per IP address
- **Exception Handling**: Secure error messages without data exposure
- **Type Safety**: Strict typing prevents type juggling vulnerabilities

### Code Quality

- **Modular Test Structure**: 66 organized tests (Unit + Feature)
- **Type Safety**: Full type hints and strict typing
- **PSR Standards**: Following PHP-FIG recommendations
- **Error Handling**: Comprehensive exception management


### Test Coverage

```
tests/
├── Unit/              # 21 tests - Isolated components
│   ├── DTOs/          # Data transfer objects
│   ├── Models/        # Eloquent model behavior
│   ├── Services/      # Business services
│   └── UseCases/      # Business logic
└── Feature/           # 45 tests - Integration scenarios
    └── Contacts/      # Complete CRUD operations
```

---

## Installation

1. **Clone and setup**

```bash
git clone <repository>
cd frutify-test
composer install
```

2. **Environment configuration**

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

3. **Run tests**

```bash
php artisan test
```

## Technical Highlights

- **Clean Architecture**: Dependency inversion with interfaces
- **Advanced Search**: Multi-field with trim and case-insensitive matching
- **Soft Deletes**: Full trash management with restore functionality
- **Validation Services**: Email uniqueness and phone normalization
- **Exception Handling**: Custom exceptions with proper HTTP status codes
- **Security**: SQL injection prevention, mass assignment protection, input sanitization, rate limiting
- **Comprehensive Testing**: 177 assertions covering all scenarios
