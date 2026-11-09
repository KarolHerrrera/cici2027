#!/bin/sh
# Se ejecuta cada vez que arranca el contenedor.
set -e

# Render indica el puerto en la variable PORT (por defecto 10000): Apache debe escuchar ahí.
PORT="${PORT:-10000}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Si no se configuró APP_KEY, se genera una automáticamente.
if [ -z "$APP_KEY" ]; then
    export APP_KEY="$(php artisan key:generate --show)"
fi

# Compatibilidad con Render / proveedores cloud: si se define DATABASE_URL pero no DB_URL, sincronizarla.
if [ -n "$DATABASE_URL" ] && [ -z "$DB_URL" ]; then
    export DB_URL="$DATABASE_URL"
fi

# Autodetección inteligente del motor de base de datos a partir de DB_URL o DB_HOST:
if [ -n "$DB_URL" ]; then
    case "$DB_URL" in
        postgres://*|postgresql://*)
            export DB_CONNECTION=pgsql
            unset DB_DATABASE
            ;;
        mysql://*)
            export DB_CONNECTION=mysql
            unset DB_DATABASE
            ;;
    esac
fi

# Con SQLite (modo sin base de datos externa), el archivo se crea y asegura permisos aquí.
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    export DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    mkdir -p "$(dirname "$DB_DATABASE")"
    touch "$DB_DATABASE"
    chown -R www-data:www-data "$(dirname "$DB_DATABASE")"
fi

# Limpiar cachés previas para tomar las variables de entorno actuales de Render/servidor
php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true

# Enlace simbólico para archivos públicos en storage (si ya existe, no falla)
php artisan storage:link >/dev/null 2>&1 || true

# Ejecutar migraciones automáticamente (por defecto: true)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "==> Ejecutando migraciones de base de datos (DB_CONNECTION=${DB_CONNECTION})..."
    php artisan migrate --force
fi

# Cargar datos oficiales y usuarios de acceso (admin@cici.test, comite@cici.test) usando updateOrCreate (idempotente)
if [ "${RUN_SEEDERS:-true}" = "true" ]; then
    echo "==> Sincronizando datos oficiales y usuarios iniciales (seeders idempotentes)..."
    php artisan db:seed --force || echo "AVISO: los seeders reportaron una advertencia (revise el log)."
fi

# Asegurar permisos de escritura para Apache (www-data) después de correr migraciones/seeders como root
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database 2>/dev/null || true

exec apache2-foreground
