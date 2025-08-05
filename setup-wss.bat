@echo off
echo Configurazione WSS per WebSocket...

REM Abilita il modulo proxy_wstunnel
powershell -Command "(Get-Content 'C:\xampp\apache\conf\httpd.conf') -replace '#LoadModule proxy_wstunnel_module', 'LoadModule proxy_wstunnel_module' | Set-Content 'C:\xampp\apache\conf\httpd.conf'"

echo Modulo proxy_wstunnel abilitato!
echo.
echo PROSSIMI PASSI:
echo 1. Aggiungi le righe ProxyPass al VirtualHost HTTPS di slamin.local
echo 2. Riavvia Apache da XAMPP Control Panel
echo 3. Avvia il WebSocket server: php artisan websocket:start
echo 4. Testa su https://slamin.local
echo.
echo Configurazione completata!
pause 