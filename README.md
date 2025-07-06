# Contact Management System – Backend Test

This project presents a clean, scalable backend solution for managing contacts, built with Laravel. Originally designed to meet the requirements of a technical assessment, the implementation goes beyond the basics by applying modern architectural standards and engineering best practices.

---

## 1. Objective

The core challenge was to ensure **all existing tests passed**, while maintaining clean, well-structured, and extensible code. The solution adopts **Clean Architecture**, follows **SOLID principles**, and is fully testable.

---

## 2. Test Status

```shell
Tests: 66 passed (177 assertions)
Duration: 0.62s
```

All provided tests are passing. In addition to the original 6 tests, **60 new tests** were implemented to ensure coverage of all possible application scenarios. The testing structure remains modular and ready for future expansion.

---

## 3. Improvements & Features

### 3.1 Architecture & Design

* Clear separation of **application layers** (Controllers, Services, UseCases, Repositories, DTOs)
* Implementation of **Clean Architecture** for maintainability and testability
* Use of the **Repository Pattern** for data abstraction
* Dedicated **Use Cases** encapsulating business logic
* **Data Transfer Objects (DTOs)** for safe and validated data handling
* Consistent application of **SOLID principles**

### 3.2 Enhanced Functionality

* **Contact Pagination With Search**: 10-items per page, with validation, graceful fallbacks and multi-field search (name, email, phone) with case-insensitive partial match.
* **Soft Deletes**: Contacts can be logically deleted and restored from trash
* **Robust Validation**: Email uniqueness, phone normalization, required fields
* **Custom Exception Handling**: Consistent and meaningful error responses

### 3.3 Security

* **Eloquent Features**: SQL injection protection via parameter binding and mass assignment control through `fillable` attributes
* **Input Validation** across all request data
* **Rate Limiting**: 60 requests/minute/IP via middleware
* **Safe Error Messages**: No sensitive data leakage in exceptions
* **Type Safety**: Strict type hints prevent runtime issues

### 3.4 Code Quality & Testing

* **Well-structured test suite** with unit and feature tests
* Organized by domain and responsibility
* Adherence to **PSR standards** and clean code principles
* Rich exception handling and fallback strategies

```bash
tests/
├── Unit/              # 21 tests – isolated logic
│   ├── DTOs/
│   ├── Models/
│   ├── Services/
│   └── UseCases/
└── Feature/           # 45 tests – end-to-end coverage
    └── Contacts/
```

---

## 4. How to Run

### 4.1 Clone and install dependencies

```bash
git clone <repository>
composer install
```

### 4.2 Setup environment

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### 4.3 Run the test suite

```bash
php artisan test
```

---

## 5. Technical Highlights

* **Clean Architecture**: Dependency inversion via contracts
* **Search System**: Trimmed, normalized, multi-field search
* **Trash Support**: Restore and view soft-deleted records
* **Validation Services**: Centralized rules via FormRequest + DTO
* **Secure Error Handling**: Proper HTTP codes, custom messages
* **Security Layer**: From input to storage, all operations validated
* **Test Coverage**: High assertion count, full scenario coverage

---

Built with performance, maintainability, and security in mind.

---

Yuri Machado – Software Engineer – [ymachado1995@gmail.com](mailto:ymachado1995@gmail.com)
