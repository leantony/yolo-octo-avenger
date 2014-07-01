@echo off
setlocal enableextensions
cd /D "%~dp0"
echo.
color 0a
::stripped down version of silent.bat, batch script to install apps
::by Cantony@localhost
title !!--NOTE--!! (Minimmal Install)
ECHO ###############################################################################
ECHO # YOU CAN VIEW INSTALL PROGRESS AT THE TITLE OF THE PROMPT. BE PATIENT        #
ECHO # EXTRACTED FILES WILL BE DELETED WHEN INSTALL PROCEDURE IS DONE              #
ECHO ###############################################################################
echo.
ECHO CHECKING Final REQUIREMENTS
echo.
::check install path variable set in previous batch "start.bat"
::also "resume" installation if files exist. this got abit harder than xpected so its quite unstable
IF EXIST "%install_dir%" ( echo echo install path is ok&goto cont
	) else ( if exist "%temp%\installs" ( set install_dir="%temp%\installs"&echo installs folder found in %temp%. proceeding to install..&goto cont)
		echo error !. install path not found&goto end
		)
	)
	
:cont
::check if still admin user
whoami /groups | find "S-1-16-12288" > nul
if "%errorlevel%" == "0" echo user access level is ok&goto cont2
If defined user ( echo user access level is ok
) else ( echo error !. access denied. programs to be installed need admin access
    goto end
    )
	
:cont2
::check for 7zip and its dll in current directory
IF NOT EXIST "%install_dir%\7z.dll" ( echo error!. 7z.dll is required and wasn't found 
	goto end
	) else (echo 7z.dll is ok)
IF NOT EXIST "%install_dir%\7z.exe" ( echo error!. 7z.exe is required and wasn't found 
	goto end
	) else ( If defined checkresult ( goto check_existing_archives
) else ( goto cont3 ) 
)

::check if all install archives exist
:check_existing_archives
set /a c=%checkresultmax%-%checkresult%
If %checkresult% neq %checkresultmax% (
	echo you have %c% archives missing in %install_dir%
	echo restart the batch making sure that you have all install archives
	pause
	goto end
	) else ( echo %checkresult%/%checkresultmax% install archives found. ok)

:cont3
::check if 32/64 bit os and prompt user
IF NOT EXIST "%PROGRAMFILES(X86)%" echo your cpu isn't 64 bit. some apps wont be installed
echo.
CHOICE /c yn /M "do you wish to continue to install. "
IF ERRORLEVEL 2 goto :end
IF ERRORLEVEL 1 goto :install
:install
call:process explorer
cd /d "%install_dir%"
cls&echo.
echo ====================installation started==================== 
echo.
echo installation started on %date% %time%
echo ------------------------------------------------------------
call ProgressMeter.bat 0
echo.
echo **** INSTALLING INTERNET BROWSERS AND THEIR COMPONENTS ****
echo.
7z.exe x -o%install_dir% -y browser.zip
::flash active x
echo.
echo %time% : installing flash components
"flashplayer_12_ax_debug.exe" -install
"flashplayer_12_plugin_debug.exe" -install
echo %time% : installed adobe flash plugins
call ProgressMeter.bat 4
echo.

::firefox
echo %time% : installing mozilla firefox
"Firefox Setup 27.0.exe" /silent
echo %time% : installed firefox
call ProgressMeter.bat 7
echo.

::thunderbird
echo %time% : installing thunderbird mail client
"Thunderbird Setup 24.3.0.exe" /silent
echo %time% : installed thunderbird
call ProgressMeter.bat 10
echo.

::chrome
echo %time% : executing ChromeStandaloneSetup
ChromeStandaloneSetup.exe /silent /install
echo %time% : installed google chrome
call ProgressMeter.bat 11
echo.

::filezilla
echo %time% : installing filezilla ftp client
"FileZilla_3.7.3_win32-setup.exe" /S
echo %time% : installed filezilla ftp
call ProgressMeter.bat 15
echo.

::java
echo %time% : installing java
start /w jre-7u51-windows-i586.exe /s
IF EXIST "%PROGRAMFILES(X86)%" (
	start /w jre-7u51-windows-x64.exe /s
	)

echo %time% : done installing java
echo.
del browser.zip /f /q >nul
echo --------------------------------------------------------------
echo %time% : done installing internet browsers and their components
echo --------------------------------------------------------------
echo.
call ProgressMeter.bat 20
echo.

cls&echo.
echo **** INSTALLING PDF AND TEXT UTILS ****
7z.exe x -o%install_dir% -y cracks.7z > nul
IF EXIST "%PROGRAMFILES(X86)%" (set crack_dir="%~dp0Crackx64"
	) else (set crack_dir="%~dp0Crackx86" )
del cracks.7z /f /q >nul
	call ProgressMeter.bat 20
7z.exe x -o%install_dir% -y %install_dir%\docs.7z
echo.

::libreoffice
echo %time% : installing openoffice 
msiexec.exe /i "LibreOffice_4.2.0_Win_x86.msi" /qb- REBOOT=ReallySupress
msiexec.exe /i "LibreOffice_4.2.0_Win_x86_helppack_en-US.msi" /qb- REBOOT=ReallySupress
echo %time% : installed openoffice 
call ProgressMeter.bat 28
echo.

::foxitpdf
echo %time% : installing foxit pdf reader
"FoxitReader612.12241_enu_Setup.exe" /VERYSILENT /SUPPRESSMSGBOXES /NORESTART /SP-
echo %time% : installed foxit pdf reader
call ProgressMeter.bat 37
echo.

::notepad++
echo %time% : installing notepad plus
"npp.6.5.3.Installer.exe"/L=1033 /S
echo %time% : installed notepad++
del docs.7z /f /q >nul
echo.
echo --------------------------------------------------------------
echo %time% : done installing document tools
echo --------------------------------------------------------------
call ProgressMeter.bat 40
echo.

cls&echo.
echo **** INSTALLING OTHER IMPORTANT STUFF ****
echo.
	call ProgressMeter.bat 40
7z.exe x -o"%install_dir%" -y util.7z
echo.
::7zip
7z920.exe /S
IF EXIST "%PROGRAMFILES(X86)%" (
	echo %time% : installing 7zip software
	msiexec.exe /i "7z925-x64.msi" /qb- REBOOT=ReallySupress
	echo %time% : installed 7zip 
)
call ProgressMeter.bat 45
echo.

::picasa
echo %time% : installing picasa
"picasa39-setup.exe" /S /L=1033
call:process picasa3
call:process iexplore
call:process chrome
call:process opera
call:process firefox
echo %time% : installed picasa
call ProgressMeter.bat 50
echo.

::cclener
echo %time% : installing cclener
"ccsetup410_slim.exe" /S /L=1033
echo %time% : installed ccleaner
call ProgressMeter.bat 55
echo.

call:process everything
del util.7z /f /q >nul
echo.
echo --------------------------------------------------------------
echo %time% : done installing misc tools
echo --------------------------------------------------------------
call ProgressMeter.bat 60
echo.

cls&echo.
echo **** INSTALLING MEDIA PLAYERS ****
7z.exe x -o"%install_dir%" -y media.zip
echo.
::vlc
echo %time% : executing vlc setup
"vlc-2.1.3-win32.exe"/L=1033 /S
echo %time% : installed vlc 
call ProgressMeter.bat 68
echo.
echo --------------------------------------------------------------
echo %time% : done installing media utils
echo --------------------------------------------------------------
call ProgressMeter.bat 70
echo.

cls&echo.
echo **** INSTALLING WINDOWS COMPONENTS ****
echo.
7z.exe x -o"%install_dir%" -y windows.zip >nul
	call ProgressMeter.bat 70
echo.
::silverlight
IF EXIST "%PROGRAMFILES(X86)%" (
echo %time% : installing silverlight
"Silverlight_x64.exe" /q
echo %time% : installed silverlight
call ProgressMeter.bat 75
)
echo.

::visual c++ libraries
echo %time% : installing visual c/c++ libraries 
"VBCFJRedist_AIO_x86_x64.exe" /ai
call ProgressMeter.bat 82
CHOICE /C AB /T 3 /D A > NUL
IF EXIST "%PROGRAMFILES(X86)%" (
		"vcredist_x64.exe" /q /norestart
	)
call ProgressMeter.bat 84
"vcredist_x86.exe" /q /norestart	
echo %time% : installed visual c/c++ libraries
call ProgressMeter.bat 86
echo.

::dotnet
if %OSVersion% leq 7 ( echo %time% : installing net framework 4.5
	"dotNetFx451_Full_LDR_x86_x64_Slim.exe" /y
	echo %time% : installed net 4.5
) else ( echo net 4.5 cannot be installed to windows %OSVersion% because it exists
echo please wait while net35 is activated&start /wait net35_install.bat
echo net35 activation was succesful
)
call ProgressMeter.bat 92
echo.

::direct x
echo **** ALMOST DONE...ONE INSTALLATION REMAINING ****
echo.
7z.exe x -o"%install_dir%" -y redist.zip >nul
DXSETUP.exe /silent
echo %time% : successfully installed/updated direct x
call ProgressMeter.bat 96
del redist.zip /f /q >nul
del *.cab DXSETUP.exe /f /q >nul
del windows.zip /f /q >nul
echo.
echo --------------------------------------------------------------
echo %time% : done installing windows components
echo --------------------------------------------------------------
call ProgressMeter.bat 97
cls&echo.

::activate windows only if its windows 7 since the loader will only work in that os
::check if OSversion var exists
if not defined OSVersion echo some problems were encountered while trying to activate windows. try that later&goto noOSvar
if %OSVersion% == 7 (
	echo **** ACTIVATING WINDOWS %OSVersion%. Please wait... ****
	7z.exe x -o%install_dir% -y activate.7z >nul
	loader.exe /silent /preactivate /norestart
	echo.
	echo %time% : Windows is now activated
	call ProgressMeter.bat 98
	)
	
::windows 8/8.1 activation
if %OSVersion% geq 8 (
	echo **** ACTIVATING WINDOWS %OSVersion%. Please wait... ****
	7z.exe x -o%install_dir% -y activate.7z >nul
	"KMSpico_setup.exe" /VERYSILENT /SUPPRESSMSGBOXES /NORESTART /SP-
	echo.
	echo %time% : Windows is now activated
	call ProgressMeter.bat 98
	)
del activate.7z /f /q >nul
:noOSvar
cls
::msc
call ProgressMeter.bat 99
7z.exe x -o"%install_dir%" -y other.7z
CHOICE /C AB /T 3 /D A > NUL
ren addfwrs.bat.txt reject.bat
ren YUMI-0.1.0.7.exe YUMI.exe
regedit.exe /I /s edit.reg
move /y YUMI.exe %userprofile%\Desktop\
move /y reject.bat %windir%\system32\
move /y resetattribs.exe %windir%\system32\
move /y resetntfspermissions.exe %windir%\system32\
move /y 7z.dll %windir%\system32\
move /y 7z.exe %windir%\system32\

::delete fukin startups
reg delete "HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run" /f
reg delete "HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows\CurrentVersion\Run" /f
echo %date% %time% : All GOOD!.Installation completed
call ProgressMeter.bat 100
CHOICE /C AB /T 2 /D A > NUL
echo ####################################################################
echo.&cls

::end batch
echo deleting extracted files
cd /d %temp%
shutdown -r -t 5 /c "finished installing apps. will now restart"
rd /s /q installs
exit /b 0

:process
SET process=%~1.exe
tasklist | find /i "%process%" >nul
if %errorlevel% equ 0 ( taskkill /f /im "%process%" >nul )
goto:eof

:scheduledtasks
SET task=%~1
schtasks | find /i "%task%" >nul
if %errorlevel% equ 0 ( schtasks /delete /tn "%task%" /f >nul )
goto:eof

:end
echo.
call ProgressMeter.bat 0
echo %time% : nothing was installed because possibly;
echo.
echo * you are not elevated. restart the batch "start.bat/resume.bat" as an admin
echo * install path was not found. execute "start.bat/resume.bat" as admin to solve the error
echo * you chose to quit
echo * some or all of the install archives weren't found.
echo.
pause
echo * Timeout 10 sec, default Y *
IF EXIST "%temp%\installs" ( CHOICE /N /T 10 /D y /M "do you want to delete extracted files? [y: n:]"
IF ERRORLEVEL 2 goto :inst
IF ERRORLEVEL 1 goto :delete
) else ( echo nothing to delete
exit /b 1
)
:delete
cd /d %temp%
rd /s /q installs
exit /b 0
:inst
exit /b 0