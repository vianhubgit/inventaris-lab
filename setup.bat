@echo off
REM Setup cepat aplikasi Inventaris Lab TKJ untuk pengujian di laptop (Windows).
REM Pemakaian:
REM   setup.bat          -> pakai MySQL/MariaDB (sesuai .env)
REM   setup.bat sqlite   -> pakai SQLite (paling cepat, tanpa setup MySQL)

setlocal
set MODE=%1
if "%MODE%"=="" set MODE=mysql

echo ==^> Inventaris Lab TKJ - setup (%MODE%)

if not exist vendor (
    echo ==^> composer install
    call composer install || goto :error
)

if not exist .env (
    copy .env.example .env
    echo ==^> .env dibuat dari .env.example
)

if "%MODE%"=="sqlite" (
    type nul > database\database.sqlite
    powershell -Command "(Get-Content .env) -replace '^DB_CONNECTION=.*','DB_CONNECTION=sqlite' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^DB_DATABASE=.*',('DB_DATABASE=' + (Resolve-Path 'database\database.sqlite').Path) | Set-Content .env"
    echo ==^> Mode SQLite aktif
)

call php artisan key:generate || goto :error
call php artisan migrate --seed --force || goto :error
call php artisan storage:link

if not exist node_modules (
    echo ==^> npm install
    call npm install || goto :error
)
echo ==^> npm run build
call npm run build || goto :error

echo.
echo ============================================================
echo  Selesai! Jalankan: php artisan serve
echo  Buka http://127.0.0.1:8000
echo  Login: admin/admin123  atau  sekretaris/sekretaris123
echo ============================================================
goto :eof

:error
echo Terjadi kesalahan saat setup.
exit /b 1
