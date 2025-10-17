# Laravel Extended Grammars

> 🧩 Extends Laravel's database grammars with advanced SQL helpers like `insertOrUpdateUsing()` for MySQL, PostgreSQL, and SQLite.

---

## 🚀 Installation

```bash
composer require ishifoev/laravel-extended-grammars
```
## 🧠 Example: Query Builder
```php 
DB::table('users')->insertOrUpdateUsing(
    ['id', 'name', 'email'], // columns to insert
    DB::table('imports')->select('id', 'name', 'email'), // source data
    ['name', 'email'] // columns to update if duplicate
);
```

## Generated SQL (MySQL):
```sql
INSERT INTO users (id, name, email)
SELECT id, name, email FROM imports
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    email = VALUES(email);
```

## PostgreSQL:
```sql
INSERT INTO users (id, name, email)
SELECT id, name, email FROM imports
ON CONFLICT (id) DO UPDATE SET
    name = EXCLUDED.name,
    email = EXCLUDED.email;
```
## SQLite
```sql
INSERT INTO users (id, name, email)
SELECT id, name, email FROM imports
ON CONFLICT(id) DO UPDATE SET
    name = excluded.name,
    email = excluded.email;
```

## 🧩 Example: Eloquent Model

```php
use App\Models\User;

User::insertOrUpdateUsing(
    ['id', 'name', 'email'],
    DB::table('imports')->select('id', 'name', 'email'),
    ['name', 'email']
);
```

## Supported

| Database   | Status         | Laravel Versions |
| ---------- | -------------- | ---------------- |
| MySQL      | ✅ Full support | 10, 11, 12       |
| PostgreSQL | ✅ Full support | 10, 11, 12       |
| SQLite     | ✅ Full support | 10, 11, 12       |

## 🧪 Testing

Install dependencies:
```
composer install
```

Run all checks:
```
composer test
composer lint
composer analyze
```

Or run via Docker:

```
docker-compose run --rm test
```

## 🧰 Composer Scripts

| Command            | Description                       |
| ------------------ | --------------------------------- |
| `composer test`    | Run PHPUnit tests                 |
| `composer lint`    | Check code style via Laravel Pint |
| `composer analyze` | Run static analysis via Psalm     |

## 🧱 Project Structure
```css
src/
 ├── Concerns/SupportsInsertOrUpdateUsing.php
 ├── Contracts/InsertOrUpdateGrammarInterface.php
 ├── Factories/GrammarFactory.php
 ├── Grammars/
 │   ├── MySqlGrammar.php
 │   ├── PostgresGrammar.php
 │   └── SQLiteGrammar.php
 └── Providers/ExtendedGrammarsServiceProvider.php
tests/
 ├── MySqlGrammarTest.php
 ├── PostgresGrammarTest.php
 └── SQLiteGrammarTest.php
```

## 🧑‍💻 Author

**Ismoil Shifoev**

*Senior Laravel Developer*

📧 ismoil.shifoev94@gmail.com

🪪 License

Licensed under the MIT License

Copyright (c) 2025 Ismoil Shifoev

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.


🏷️ Version History

**v1.0.0 (Initial Release)**

Added MySQL/PostgreSQL/SQLite support

Added insertOrUpdateUsing() for models and Query Builder

Added unit tests and static analysis

Added Laravel Pint and Psalm integration

Laravel 10, 11, 12 compatible

