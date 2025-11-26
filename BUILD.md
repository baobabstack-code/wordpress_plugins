# Build & CI

This repository contains the `simple-frontend-plugin` WordPress plugin.

## Local build (Windows)
Run the included PowerShell script from the repository root:

```powershell
.\build-zip.ps1 -Output "simple-frontend-plugin.zip"
```

The script will create `simple-frontend-plugin.zip` excluding `.git`, `vendor`, `.github`, and existing zip files.

## CI
I attempted to add a GitHub Actions workflow at `.github/workflows/ci.yml` to run PHP lint, PHPUnit (if available), and create a zip artifact. If the workflow file isn't present, add it to enable automated linting and zip builds.

## Tests
A very small PHPUnit test suite is included in `tests/`. To run tests locally:

1. Install dependencies (if you use Composer and PHPUnit):

```powershell
composer install
vendor\bin\phpunit -c phpunit.xml.dist
```

Note: Proper WordPress PHPUnit tests require the WP test libraries and a separate setup; the current tests are minimal and validate plugin file presence.
