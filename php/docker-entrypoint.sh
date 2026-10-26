#!/bin/bash
set -e

echo "🚀 Starting FixHome Plus container setup..."

# Step 1: Check if vendor exists, if not install
if [ ! -d /var/www/html/vendor ]; then
  echo "📦 Installing composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Step 2: Create .env if not exists
if [ ! -f /var/www/html/.env ]; then
  echo "📄 Creating .env file from environment variables..."
  cat <<EOF > /var/www/html/.env
APP_NAME="FixHome Plus"
APP_ENV=local
APP_KEY=base64:jU6xg8sp9ia37ypFlTVk1CAFx6MmeXRukO1W987uUzI=
APP_DEBUG=true
APP_TIMEZONE='Asia/Jakarta'
APP_URL="http://localhost"
ASSET_URL="http://localhost"
DEBUGBAR_ENABLED=false
ASSET_PREFIX=

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=fixhome_plus
DB_USERNAME=fixhome
DB_PASSWORD=fixhome_secret

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS="no-reply@fixhome.plus"
MAIL_FROM_NAME="FixHome Plus"

VITE_APP_NAME="FixHome Plus"
EOF
else
  echo "✅ .env file already exists. Preserving existing .env."
fi

# Step 3: Wait for DB connection (host should match DB_HOST in .env)
DB_HOST=$(grep ^DB_HOST /var/www/html/.env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
DB_PORT=$(grep ^DB_PORT /var/www/html/.env | cut -d '=' -f2 | tr -d '"' | tr -d "'")

DB_HOST=${DB_HOST:-postgres}
DB_PORT=${DB_PORT:-5432}

echo "⏳ Waiting for PostgreSQL at $DB_HOST:$DB_PORT..."

RETRIES=30
until nc -z "$DB_HOST" "$DB_PORT"; do
  if [ "$RETRIES" -le 0 ]; then
    echo "❌ Timeout waiting for database. Exiting."
    exit 1
  fi
  echo "Waiting for database..."
  sleep 2
  RETRIES=$((RETRIES - 1))
done

echo "✅ Database connection established!"

# Step 4: Generate app key if needed
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
  echo "🔐 Generating Laravel app key..."
  php artisan key:generate --force
fi

# Step 5: Fix permissions
echo "🔧 Fixing permissions..."
mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Step 6: Run database migrations if ready
echo "🗃️ Running migrations..."
php artisan migrate --force || true

# Step 7: Create storage symbolic link
echo "🔗 Creating storage link..."
php artisan storage:link || true

echo "✅ FixHome Plus setup complete. Ready to serve!"

exec "$@"
