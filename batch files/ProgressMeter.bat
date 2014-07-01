@echo off
SETLOCAL ENABLEDELAYEDEXPANSION
SET ProgressPercent=%1
SET /A NumBars=%ProgressPercent%/2
SET /A NumSpaces=50-%NumBars%

:: Clear the progress meter image
SET Meter=

:: Build the meter image using vertical bars followed by trailing spaces
:: Note there is a trailing space at the end of the second line below
FOR /L %%A IN (%NumBars%,-1,1) DO SET Meter=!Meter!=
FOR /L %%A IN (%NumSpaces%,-1,1) DO SET Meter=!Meter! 

:: Display the progress meter in the title bar and return to the main program
TITLE Total Installation Progress:  [%Meter%]  %ProgressPercent%%%
ENDLOCAL
GOTO :EOF
