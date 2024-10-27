## Getting Started

1. First, install the dependencies:

```bash
cp .env.tpl .env
composer install
```

2. Initialize the database:

```bash
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
```

3. Import the fixtures orders:

```bash
bin/console ugo:orders:import
```

4. Run the server:

```bash
symfony server:start
```

5. Use the api routes

```bash
GET http://127.0.0.1:8000/customers
GET http://127.0.0.1:8000/customers/1/orders
```

6. Run the tests:

```bash
bin/console doctrine:database:create --env=test
bin/console doctrine:migrations:migrate --env=test
bin/phpunit
```

