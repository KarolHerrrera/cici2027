# Plataforma Web Oficial — IV Congreso Internacional de Ciencia e Ingeniería (CICI 2027)
**Facultad de Ingeniería · Universidad Mariana (San Juan de Pasto, Nariño, Colombia)**  
Sitio oficial de referencia: [https://cici.umariana.edu.co/](https://cici.umariana.edu.co/)

---

## 1. Credenciales de Acceso Iniciales (creadas automáticamente por los Seeders)

Al ejecutar `php artisan migrate --seed` (o al desplegar en Render / Docker, donde `RUN_MIGRATIONS=true` y `RUN_SEEDERS=true` están activos por defecto), el sistema crea automáticamente las cuentas oficiales:

| Rol en la Plataforma | Correo electrónico | Contraseña | Ruta de acceso |
|---|---|---|---|
| **Administrador General** | `admin@cici.test` | `Cici2027*` | `/login` → `/admin` |
| **Líder del Comité Científico** (Ing. Civil) | `comite@cici.test` | `Comite2027*` | `/login` → `/comite/resumenes` |
| **Evaluadora del Comité Científico** (Ing. Ambiental) | `evaluador@cici.test` | `Comite2027*` | `/login` → `/comite/resumenes` |

### Cédulas de prueba para Consulta Pública (`/estado-resumen`)
- `1085200101` — **Aprobado en Modalidad Póster** (habilita descarga de plantilla de póster).
- `1085200202` — **Aprobado en Ponencia Oral**.
- `1085200303` — **En revisión por el Comité Científico**.

---

## 2. Ejecución Local (Visual Studio Code / Windows)

```powershell
# 1. Instalar dependencias (si copiaste la carpeta sin vendor o node_modules)
composer install
npm install

# 2. Preparar base de datos SQLite local, enlaces simbólicos y datos oficiales
php artisan storage:link
php artisan migrate:fresh --seed

# 3. Levantar el servidor (en dos terminales de VS Code):
# Terminal 1:
npm run dev
# Terminal 2:
php artisan serve
```
Luego abre **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

> **Nota importante sobre `.env` local:** En tu computador deja `DB_CONNECTION=sqlite` y mantén comentada la línea `#DB_URL=...` (la URL `postgresql://...` interna de Render solo funciona dentro de la nube de Render).

---

## 3. Despliegue en Render (Docker + PostgreSQL o SQLite)

El proyecto ya incluye `Dockerfile`, `docker/entrypoint.sh`, `.dockerignore` y `render.yaml` corregidos y listos para producción:

1. **Autodetección de Base de Datos (`docker/entrypoint.sh`)**:
   - Si configuras `DB_URL=postgresql://...` (o `DATABASE_URL`) en Render, el contenedor activa automáticamente `DB_CONNECTION=pgsql` y limpia `DB_DATABASE` para que no haya conflicto con SQLite.
   - Si no conectas una base de datos externa, el contenedor funciona automáticamente con **SQLite** (`/var/www/html/database/database.sqlite`).
2. **Migraciones y Seeders Automáticos (`RUN_MIGRATIONS=true` y `RUN_SEEDERS=true`)**:
   - Cada vez que el contenedor arranca en Render, ejecuta `php artisan migrate --force` y `php artisan db:seed --force` (todos los seeders usan `updateOrCreate`, por lo que son idempotentes y no duplican registros).
   - Esto garantiza que el usuario **`admin@cici.test` (`Cici2027*`)**, los 7 ponentes oficiales (incluido el **Dr. Mauricio Alvarado Bueno**), los Términos de Referencia en PDF y la programación académica de los 3 días estén disponibles desde el primer arranque.

### Pasos en Render
1. Sube los últimos cambios a GitHub (`git push origin main`).
2. En [dashboard.render.com](https://dashboard.render.com):
   - **Opción A (Blueprint automático con PostgreSQL):** Haz clic en **New + → Blueprint**, selecciona el repositorio `cici2027` y pulsa **Apply**. Render leerá `render.yaml`, creará la base de datos `cici2027-db`, el servicio web `cici2027`, generará `APP_KEY` y poblará todas las tablas y usuarios automáticamente.
   - **Opción B (Si ya tenías el Web Service creado en Render):** Entra a tu servicio en Render → pestaña **Environment**:
     - Si tienes una base PostgreSQL en Render, pon su *Internal Database URL* en `DB_URL` y `DB_CONNECTION=pgsql` (elimina `DB_DATABASE` si aparece en Environment).
     - Si no tienes base PostgreSQL creada, deja `DB_CONNECTION=sqlite` y elimina `DB_URL`.
     - Pulsa **Manual Deploy → Deploy latest commit**.

---

## 4. Despliegue en el Servidor Institucional de la Universidad Mariana (`cici.umariana.edu.co`)

Cuando la Oficina de Sistemas / TI de la Universidad Mariana reciba el proyecto para producción:
1. Configurar `.env` con las credenciales de la base de datos institucional (`pgsql` o `mysql`), `APP_ENV=production`, `APP_DEBUG=false` y `APP_URL=https://cici.umariana.edu.co`.
2. Apuntar el `DocumentRoot` de Apache o Nginx a la carpeta `/public`.
3. Ejecutar una única vez:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan key:generate --force
   php artisan storage:link
   php artisan migrate --seed --force
   php artisan optimize
   ```
4. Ingresar con `admin@cici.test` / `Cici2027*` en `/login` y actualizar la contraseña del administrador desde `/profile`.
