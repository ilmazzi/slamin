@echo off
echo Configurazione HTTPS per XAMPP...

REM Crea directory per certificati
if not exist "C:\xampp\apache\conf\ssl.crt\slamin" mkdir "C:\xampp\apache\conf\ssl.crt\slamin"
if not exist "C:\xampp\apache\conf\ssl.key\slamin" mkdir "C:\xampp\apache\conf\ssl.key\slamin"

REM Copia certificati esistenti
copy "C:\xampp\apache\conf\ssl.crt\server.crt" "C:\xampp\apache\conf\ssl.crt\slamin\slamin.local.crt"
copy "C:\xampp\apache\conf\ssl.key\server.key" "C:\xampp\apache\conf\ssl.key\slamin\slamin.local.key"

echo Certificati copiati!
echo.
echo PROSSIMI PASSI:
echo 1. Apri C:\xampp\apache\conf\httpd.conf
echo 2. Cerca "#Include conf/extra/httpd-ssl.conf"
echo 3. Rimuovi il # per abilitare SSL
echo 4. Aggiungi la configurazione VirtualHost al file httpd-ssl.conf
echo 5. Riavvia Apache da XAMPP Control Panel
echo.
echo Configurazione completata!
pause 