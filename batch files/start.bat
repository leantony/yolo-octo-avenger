@echo off
cd /D "%~dp0"
color 0a
::startup batch for silent installs by Cantony@localhost
::check prequisites
::check if admin user
whoami /groups | find "S-1-16-12288" > nul
if "%errorlevel%" == "0" (
	 SET user=root 
    ) else ( title Access denied
    echo right click batch file and select run as admin
    pause
    exit
    )

::chech system arch
IF NOT EXIST %PROCESSOR_ARCHITECTURE% set %PROCESSOR_ARCHITECTURE%=

::check OSVersion & run only if windows 7 +. we move with the times, dont we?
SET OSVersion=Unknown
VER | FINDSTR /L "6.1." > NUL
IF %ERRORLEVEL% EQU 0 SET OSVersion=7
VER | FINDSTR /L "6.2." > NUL
IF %ERRORLEVEL% EQU 0 SET OSVersion=8
VER | FINDSTR /L "6.3." > NUL
IF %ERRORLEVEL% EQU 0 SET OSVersion=8.1
IF %OSVersion%==Unknown (
	title unsupported OS version
 echo try windows 7 and 8&pause&exit
) else ( echo ###############################################################################
echo.
echo OK...Batch running on Windows %OSVersion%&echo.)

::check free space. at least 2gb free on system drive
set "low="
for /f "tokens=3" %%a in ('dir %systemdrive% /-p 2^>nul') do set "size=%%a"
for /f "tokens=1-4 delims=," %%a in ("%size%") do (
if "%%d"=="" set low=1
set gb=%%a
set mb=%%b
)
if %gb%%mb% LEQ 2000 set low=1
If defined low (
	title No enough space for extraction on %systemdrive% 
echo at least 2 gig of free space is needed on %systemdrive%&pause&exit
) else (
echo OK...Installation drive %systemdrive% has %gb%.%mb% free GB for installs&echo.
)

::directories & other batch files that must be required
call:checkfiles soft
call:checkfiles %temp%
call:checkfiles silent.bat
call:checkfiles ProgressMeter.bat
echo OK...extraction directory "%~dp0soft" found
cd /d "%~dp0soft"
echo.
echo #### checking if install archives exist in "%~dp0soft" ####
call:archive_check activate.7z
call:archive_check browser.zip
call:archive_check cracks.7z
call:archive_check docs.7z
call:archive_check Gaming.7z
call:archive_check media.zip
call:archive_check other.7z
call:archive_check progutil.zip
call:archive_check util.7z
call:archive_check windows.zip
echo.
echo proceeding to copy files...
echo.
ECHO ###############################################################################
echo.
cd..
:proceed
SET extract_src="%~dp0soft"
SET install_dir="%temp%\installs"
set checkresultmax=10
title silent installs on  %PROCESSOR_ARCHITECTURE%
echo.
echo install Files will be copied to a directory named "installs" in :
echo "%temp%"
echo once the copy process is done, its safe to remove the external device
echo.
xcopy "%extract_src%\"*.* "%install_dir%" /k /f /y /i /w
echo.
copy /y ProgressMeter.bat "%install_dir%" >nul
cls
::present install options since we dont always have to install erthng
echo ****************************************&echo.
echo Install options:
echo option 1 = install all software
echo option 2 = install only essential software
echo.&echo ****************************************
CHOICE /c 12 /M "Your option is?"
IF ERRORLEVEL 2 goto :essential_software
IF ERRORLEVEL 1 goto :all_software
:all_software
copy /y silent.bat "%install_dir%"
cls
cd /d "%install_dir%"
call silent.bat
exit
:essential_software
copy /y silent_essential.bat "%install_dir%"
cls
cd /d "%install_dir%"
call silent_essential.bat
exit
::functions
set checkresult=0
:archive_check
SET archive=%~1
dir | find /i "%archive%" >nul
if %errorlevel% equ 0 ( echo.&echo OK....archive "%archive%" exists
	set /a checkresult=%checkresult%+1
	) else (  set /a checkresult=%checkresult%-1
	title missing archive&echo.&echo error! . archive "%archive%" doesn't exist
	pause
	 )
goto:eof

:checkfiles
set file=%~1
if not exist %file% title missing critical requirement&echo Fatal Error! . file/directory "%file%" not found&pause&exit
goto:eof