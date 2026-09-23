@echo off
echo Starting AmaSports Laravel Backend on 0.0.0.0:8000 (accessible on LAN)...
cd /d "%~dp0\public"
php -S 0.0.0.0:8000 ..\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php
