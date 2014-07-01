@echo off
cd /D "%~dp0"
color 0a
::enable net framework 3.5 win 8+ by antonyC@localhost
::run only if windows 8 + of course since the others have wat we r installing
set osversion=
VER | FINDSTR /L "6.1." > NUL
IF %ERRORLEVEL% EQU 0 SET OSVersion=7
ver | findstr /l "6.2." > nul
if %errorlevel% equ 0 set osversion=8
ver | findstr /l "6.3." > nul
if %errorlevel% equ 0 set osversion=8.1
if %osversion% leq 7 (
	title Info
 	echo Windows %osversion% already has net35 pre-installed. get windows 8+ for this&pause&exit
 )
title activating net35 on windows %osversion%
::keep proceeding if admin user. of coz inahitajika
whoami /groups | find "S-1-16-12288" > nul
if "%errorlevel%" neq "0" (echo you'll need admin access to run the batch. just close and rerun the batch&pause&exit )
::proceed, checking directories
echo working from "%~dp0"
::check for existing copy
IF NOT EXIST "%systemdrive%\sources" ( echo directory "%systemdrive%\sources%" doesn't exist
) else ( cd /d "%systemdrive%\sources" 
goto main
)
::incase someone doesnt ave 7z* and has dir specified use normal copy
if exist "%~dp0sources" xcopy "%~dp0sources\"*.* "%systemdrive%\sources" /S /I&goto main
if not exist "%~dp0sources.zip" echo install file not found&pause&exit
if not exist 7z.dll if not exist 7z.exe echo copy 7z.dll and 7z.exe to batch/system directory then&echo retry coz its needed to extract the install file&pause&exit
7z.exe x -o%systemdrive% -y sources.zip
:main
cls
::do sme renaming based on system specs
cd /d "%systemdrive%\sources"
IF EXIST "%PROGRAMFILES(X86)%" ( ren "%cd%\sxs64" sxs&set net_src="%cd%\sxs"
	) else ( ren "%cd%\sxs86" sxs&set net_src="%cd%\sxs")
echo **** Timeout 10 sec, default choice N ****&echo.
CHOICE /N /T 10 /D n /M "shall I Proceed to activate net 35 for you on windows %osversion% ? [y: n:]"
if errorlevel 2 goto :eof
if errorlevel 1 goto :activate_net35
:activate_net35
echo.&echo activating net35 from %net_src% . please wait
::use full dir name as source to prevent the rename error from carrying over
Dism.exe /online /enable-feature /featurename:NetFX3 /All /Source:%systemdrive%\sources\sxs /LimitAccess
::delete files if successful
cd..\..
if errorlevel 0 rd /s /q sources&goto eof
echo.
echo errors were encountered. look at the dism log for more details&exit
:eof
echo all done&exit