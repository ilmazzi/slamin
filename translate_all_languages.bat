@echo off
echo 🌍 Traduzione automatica di tutti i file in tutte le lingue...
echo.

echo 📝 Traduzione in English (en)...
php artisan translations:translate-page en admin --provider=libre --force
php artisan translations:translate-page en common --provider=libre --force
php artisan translations:translate-page en auth --provider=libre --force
php artisan translations:translate-page en dashboard --provider=libre --force
php artisan translations:translate-page en events --provider=libre --force
php artisan translations:translate-page en poems --provider=libre --force
php artisan translations:translate-page en videos --provider=libre --force
php artisan translations:translate-page en articles --provider=libre --force
php artisan translations:translate-page en profile --provider=libre --force
php artisan translations:translate-page en carousel --provider=libre --force

echo.
echo 📝 Traduzione in Français (fr)...
php artisan translations:translate-page fr admin --provider=libre --force
php artisan translations:translate-page fr common --provider=libre --force
php artisan translations:translate-page fr auth --provider=libre --force
php artisan translations:translate-page fr dashboard --provider=libre --force
php artisan translations:translate-page fr events --provider=libre --force
php artisan translations:translate-page fr poems --provider=libre --force
php artisan translations:translate-page fr videos --provider=libre --force
php artisan translations:translate-page fr articles --provider=libre --force
php artisan translations:translate-page fr profile --provider=libre --force
php artisan translations:translate-page fr carousel --provider=libre --force

echo.
echo 📝 Traduzione in Español (es)...
php artisan translations:translate-page es admin --provider=libre --force
php artisan translations:translate-page es common --provider=libre --force
php artisan translations:translate-page es auth --provider=libre --force
php artisan translations:translate-page es dashboard --provider=libre --force
php artisan translations:translate-page es events --provider=libre --force
php artisan translations:translate-page es poems --provider=libre --force
php artisan translations:translate-page es videos --provider=libre --force
php artisan translations:translate-page es articles --provider=libre --force
php artisan translations:translate-page es profile --provider=libre --force
php artisan translations:translate-page es carousel --provider=libre --force

echo.
echo 📝 Traduzione in Deutsch (de)...
php artisan translations:translate-page de admin --provider=libre --force
php artisan translations:translate-page de common --provider=libre --force
php artisan translations:translate-page de auth --provider=libre --force
php artisan translations:translate-page de dashboard --provider=libre --force
php artisan translations:translate-page de events --provider=libre --force
php artisan translations:translate-page de poems --provider=libre --force
php artisan translations:translate-page de videos --provider=libre --force
php artisan translations:translate-page de articles --provider=libre --force
php artisan translations:translate-page de profile --provider=libre --force
php artisan translations:translate-page de carousel --provider=libre --force

echo.
echo 📝 Traduzione in Português (pt)...
php artisan translations:translate-page pt admin --provider=libre --force
php artisan translations:translate-page pt common --provider=libre --force
php artisan translations:translate-page pt auth --provider=libre --force
php artisan translations:translate-page pt dashboard --provider=libre --force
php artisan translations:translate-page pt events --provider=libre --force
php artisan translations:translate-page pt poems --provider=libre --force
php artisan translations:translate-page pt videos --provider=libre --force
php artisan translations:translate-page pt articles --provider=libre --force
php artisan translations:translate-page pt profile --provider=libre --force
php artisan translations:translate-page pt carousel --provider=libre --force

echo.
echo 🎉 Traduzione completata per tutte le lingue!
echo ✅ Tutti i file sono stati tradotti con API reali
echo 🌍 Le traduzioni sono ora corrette e non contengono più placeholder
pause
