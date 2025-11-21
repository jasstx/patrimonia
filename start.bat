@echo off
echo ========================================
echo   Demarrage du serveur Patrimonia
echo ========================================
echo.

cd /d "%~dp0"

echo Verification de l'environnement...
php --version >nul 2>&1
if errorlevel 1 (
    echo ERREUR: PHP n'est pas installe ou n'est pas dans le PATH
    pause
    exit /b 1
)

echo PHP trouve: OK
echo.

echo Verification des dependances...
if not exist "vendor\autoload.php" (
    echo Installation des dependances Composer...
    composer install
)

if not exist "node_modules" (
    echo Installation des dependances npm...
    call npm install
)

echo.
echo Verification de la base de donnees...
if not exist "database\database.sqlite" (
    echo Creation de la base de donnees SQLite...
    type nul > database\database.sqlite
)

echo.
echo Nettoyage du cache...
php artisan optimize:clear >nul 2>&1

echo.
echo Demarrage du serveur sur http://127.0.0.1:8000
echo.
echo Appuyez sur Ctrl+C pour arreter le serveur
echo.

php artisan serve --host=127.0.0.1 --port=8000

pause

