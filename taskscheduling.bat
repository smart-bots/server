@ECHO OFF
TITLE SmartBots Task Scheduling
 
:runSchedule

php artisan schedule:run

TIMEOUT /T 60 /NOBREAK > nul
 
goto runSchedule
 
PAUSE
EXIT