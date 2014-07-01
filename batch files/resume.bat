@echo off
cd /D "%~dp0"
::resume install batch by antonyC@localhost
::check if install dir exists. if true then set the relevant vars and proceed
if not exist "%temp%\installs" call start.bat&exit
SET install_dir="%temp%\installs"
copy /y silent.bat "%install_dir%"
copy /y ProgressMeter.bat "%install_dir%"
cd /d "%install_dir%"
cls
call silent.bat
exit