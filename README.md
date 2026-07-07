# CSV Client Management System

## Overview

A Laravel-based REST API for importing, managing, detecting duplicate client records, and exporting CSV data.

---

## Features

- CSV Import
- Duplicate Detection
- CSV Export
- Queue Based Import
- RESTful API
- Pagination
- Import Tracking

---

## Tech Stack

- Laravel 12
- PHP 8+
- MySQL
- Queue Jobs

---

## Installation

```bash
composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

---

## Queue

```bash
php artisan queue:work
```

---

## API

### Import CSV

POST

```
/api/imports
```

multipart/form-data

```
file
```

---

### Import History

GET

```
/api/imports
```

---

### Import Status

GET

```
/api/imports/{id}
```

---

### Clients

GET

```
/api/clients
```

---

### Duplicate Clients

GET

```
/api/clients?duplicates=true
```

---

### Export CSV

GET

```
/api/export
```

---

## CSV Format

```csv
company_name,email,phone_number
ABC,abc@gmail.com,9801000000
XYZ,xyz@gmail.com,9801000001
```

---

## Architecture

```
CSV Upload
      │
      ▼
ImportController
      │
      ▼
Queue Job
      │
      ▼
ClientImportService
      │
      ▼
Database
```

---

## Performance Optimizations

- Queue based processing
- Batch Insert
- Fingerprint based duplicate detection
- Streamed CSV export

---

## Future Improvements

- Authentication
- Progress Dashboard
- Retry Failed Imports
- S3 File Storage
- Redis Queue