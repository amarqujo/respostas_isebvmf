@echo off
echo.
echo Collecting data ...
echo.
"%CD%\..\apps\php" -f textos.php
"%CD%\..\apps\php" -f dims.php
echo.
echo Done ...
echo.
pause