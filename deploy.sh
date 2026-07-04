#!/usr/bin/env bash
#
# deploy.sh — Build & optimize aplikasi Inventaris Lab TKJ di SERVER PRODUCTION.
#
# Jalankan dari dalam folder project di server (mis. /var/www/inventaris-lab)
# SETELAH file .env production sudah dikonfigurasi (lihat DEPLOY_SERVER.txt).
#
# Pemakaian:
#   ./deploy.sh            -> deploy/update penuh (composer, npm build, migrate, cache)
#   ./deploy.sh --no-build -> lewati npm install & build (bila aset tidak berubah)
#
# Aman dijalankan berulang (idempotent). Untuk update rutin, cukup salin file
# baru lalu jalankan ./deploy.sh kembali.
#
set -euo pipefail

BUILD=1
[ "${1:-}" = "--no-build" ] && BUILD=0

cd "$(dirname "$0")"

echo "==> Inventaris Lab TKJ — deploy production"

# 0. Pastikan .env ada & bukan mode debug
if [ ! -f .env ]; then
    echo "!! File .env belum ada. Salin dulu:  cp .env.production.example .env  lalu isi nilainya."
    exit 1
fi
if grep -qE '^APP_DEBUG=true' .env; then
    echo "!! PERINGATAN: APP_DEBUG=true di .env. Untuk production sebaiknya false."
fi
if ! grep -qE '^APP_KEY=base64:' .env; then
    echo "!! APP_KEY belum di-generate. Menjalankan php artisan key:generate ..."
    php artisan key:generate --force
fi

# 1. Mode maintenance selama proses update
php artisan down --retry=15 || true
trap 'php artisan up || true' EXIT

# 2. Dependency PHP (tanpa dev, autoloader teroptimasi)
echo "==> composer install (production)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 3. Aset frontend (Vite/Tailwind)
if [ "$BUILD" -eq 1 ]; then
    echo "==> npm ci && npm run build"
    if [ -f package-lock.json ]; then npm ci; else npm install; fi
    npm run build
else
    echo "==> Lewati build aset (--no-build)"
fi

# 4. Migrasi database (data inti otomatis ter-seed bila tabel masih kosong via first deploy)
echo "==> migrate --force"
php artisan migrate --force

# 5. Symlink storage untuk foto laporan
php artisan storage:link || true

# 6. Bersihkan lalu bangun ulang cache production
echo "==> optimize (config/route/view cache)"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache || true

# 7. Selesai — trap akan menjalankan 'php artisan up'
echo ""
echo "============================================================"
echo " Deploy selesai. Aplikasi kembali online."
echo " Cek: http://<IP-SERVER>/up   (health check harus 200 OK)"
echo "============================================================"
