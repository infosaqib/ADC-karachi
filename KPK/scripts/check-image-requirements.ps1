# Script to check how many images are needed for each priority folder

# Read pages.txt and filter out empty lines
$pages = Get-Content "pages.txt" | Where-Object { $_.Trim() -ne "" }

# Count pages by priority
$priority03008977885 = ($pages | Select-String "03008977885").Count
$priority03003006220 = ($pages | Select-String "03003006220").Count

# Count images in each folder
$images03008977885 = (Get-ChildItem "images\03008977885\*.jpeg" -ErrorAction SilentlyContinue | Measure-Object).Count
$images03003006220 = (Get-ChildItem "images\03003006220\*.jpeg" -ErrorAction SilentlyContinue | Measure-Object).Count

# Calculate needed images
$need03008977885 = $priority03008977885 - $images03008977885
$need03003006220 = $priority03003006220 - $images03003006220

# Display results
Write-Host ""
Write-Host "=== IMAGE REQUIREMENTS ANALYSIS ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Priority 03008977885:" -ForegroundColor Yellow
Write-Host "  Total pages needed: $priority03008977885"
Write-Host "  Images currently available: $images03008977885"
Write-Host "  Images still needed: $need03008977885" -ForegroundColor $(if ($need03008977885 -gt 0) { "Red" } else { "Green" })
Write-Host ""
Write-Host "Priority 03003006220:" -ForegroundColor Yellow
Write-Host "  Total pages needed: $priority03003006220"
Write-Host "  Images currently available: $images03003006220"
Write-Host "  Images still needed: $need03003006220" -ForegroundColor $(if ($need03003006220 -gt 0) { "Red" } else { "Green" })
Write-Host ""

# List cities missing images
Write-Host "=== CITIES MISSING IMAGES ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "Priority 03008977885 missing images:" -ForegroundColor Yellow
foreach ($page in $pages) {
    if ($page -match 'href="https://services\.armydogcenter\.org\.pk/([^"]+)\.php".*\| 03008977885') {
        $cityFile = $matches[1]
        $imagePath = "images\03008977885\$cityFile.jpeg"
        if (-not (Test-Path $imagePath)) {
            Write-Host "  - $cityFile" -ForegroundColor Red
        }
    }
}

Write-Host ""
Write-Host "Priority 03003006220 missing images:" -ForegroundColor Yellow
foreach ($page in $pages) {
    if ($page -match 'href="https://services\.armydogcenter\.org\.pk/([^"]+)\.php".*\| 03003006220') {
        $cityFile = $matches[1]
        $imagePath = "images\03003006220\$cityFile.jpeg"
        if (-not (Test-Path $imagePath)) {
            Write-Host "  - $cityFile" -ForegroundColor Red
        }
    }
}

Write-Host ""

