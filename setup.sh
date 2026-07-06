#!/usr/bin/env bash
#
# Setup cepat aplikasi Inventaris Lab TKJ untuk pengujian di laptop (Linux/macOS).
# Pemakaian:
#   ./setup.sh           -> pakai database MySQL/MariaDB (sesuai .env)
#   ./setup.sh sqlite    -> pakai SQLite (tanpa perlu setup MySQL, paling cepat)
#
set -e

MODE="${1:-mysql}"

echo "==> Inventaris Lab TKJ — setup ($MODE)"

# 0. Preflight: pastikan PHP & ekstensi wajib tersedia
command -v php >/dev/null 2>&1 || { echo "PHP belum terpasang. Install PHP 8.3+ terlebih dahulu."; exit 1; }

PHPVER=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
REQUIRED="dom mbstring xml tokenizer ctype fileinfo curl openssl bcmath"
[ "$MODE" = "sqlite" ] && REQUIRED="$REQUIRED pdo_sqlite sqlite3" || REQUIRED="$REQUIRED pdo_mysql"

MISSING=""
for ext in $REQUIRED; do
    php -m | grep -iq "^$ext$" || MISSING="$MISSING $ext"
done

if [ -n "$MISSING" ]; then
    echo ""
    echo "!! Ekstensi PHP berikut belum aktif:$MISSING"
    echo "   Install (Ubuntu/Debian, sebagai root/sudo):"
    echo ""
    echo "     apt-get install -y php${PHPVER}-xml php${PHPVER}-sqlite3 php${PHPVER}-mbstring \\"
    echo "       php${PHPVER}-gd php${PHPVER}-bcmath php${PHPVER}-curl php${PHPVER}-zip php${PHPVER}-mysql php${PHPVER}-intl"
    echo ""
    echo "   Lalu jalankan ./setup.sh lagi."
    exit 1
fi

# 1. Dependency PHP
if [ ! -d vendor ]; then
    echo "==> composer install"
    composer install
fi

# 2. File .env
if [ ! -f .env ]; then
    cp .env.example .env
    echo "==> .env dibuat dari .env.example"
fi

# 3. SQLite mode (paling mudah untuk uji coba) — edit .env via PHP (aman untuk path berisi '/')
if [ "$MODE" = "sqlite" ]; then
    touch database/database.sqlite
    php -r '$f=".env";$c=file_get_contents($f);
        $c=preg_replace("/^DB_CONNECTION=.*/m","DB_CONNECTION=sqlite",$c);
        $c=preg_replace("#^DB_DATABASE=.*#m","DB_DATABASE=".getcwd()."/database/database.sqlite",$c);
        file_put_contents($f,$c);'
    echo "==> Mode SQLite aktif (database/database.sqlite)"
fi

# 4. App key
php artisan key:generate

# 5. Migrasi + seeder
php artisan migrate --seed --force

# 6. Symlink storage (foto laporan)
php artisan storage:link || true

# 7. Build aset frontend
if [ ! -d node_modules ]; then
    echo "==> npm install"
    npm install
fi
echo "==> npm run build"
npm run build

echo ""
echo "============================================================"
echo " Selesai! Jalankan server pengembangan:"
echo "   php artisan serve"
echo " Buka http://127.0.0.1:8000"
echo ""
echo " Login:"
echo "   admin       / admin123"
echo "   sekretaris  / sekretaris123"
echo "============================================================"
