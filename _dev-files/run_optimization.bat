@echo off
echo ========================================
echo   Image Optimization Script
echo ========================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Python is not installed or not in PATH
    echo Please install Python from https://python.org
    pause
    exit /b 1
)

echo Checking Python packages...
echo.

REM Install required packages if not present
pip show Pillow >nul 2>&1
if %errorlevel% neq 0 (
    echo Installing Pillow...
    pip install Pillow
)

pip show pillow-heif >nul 2>&1
if %errorlevel% neq 0 (
    echo Installing AVIF support...
    pip install pillow-heif
)

echo.
echo Starting image optimization...
echo.

REM Run the Python script
python optimize_images.py

echo.
echo ========================================
echo   Optimization Complete!
echo ========================================
echo.
echo Your optimized images are in: assets/optimized/
echo Your website will now load much faster!
echo.
pause