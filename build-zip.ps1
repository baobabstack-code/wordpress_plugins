# Build plugin zip on Windows PowerShell
Param(
    [string]$Output = "simple-frontend-plugin.zip"
)

$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $Root

# Files/folders to exclude
$exclude = @('.git', 'vendor', '.github', '*.zip')

# Create a temporary folder containing plugin files
$tmp = Join-Path $env:TEMP "sfp_build_$(Get-Random)"
New-Item -ItemType Directory -Path $tmp | Out-Null

Get-ChildItem -Path . -Recurse -Force | Where-Object {
    $p = $_.FullName
    foreach ($e in $exclude) {
        if ($p -like "*\$e*") { return $false }
    }
    return $true
} | ForEach-Object {
    $dest = $p = $_.FullName.Substring((Get-Location).Path.Length).TrimStart('\')
    $target = Join-Path $tmp $dest
    if ($_.PSIsContainer) { New-Item -ItemType Directory -Force -Path $target | Out-Null } else { Copy-Item -Path $_.FullName -Destination $target -Force }
}

# Create zip
if (Test-Path $Output) { Remove-Item $Output -Force }
Compress-Archive -Path (Join-Path $tmp '*') -DestinationPath $Output

# Cleanup
Remove-Item -Recurse -Force $tmp
Write-Output "Created $Output"
