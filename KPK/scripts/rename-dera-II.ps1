# Script to rename dera-ismail-khan to dera-ismail-khan-II

$oldName = "dera-ismail-khan"
$newName = "dera-ismail-khan-II"
$priority = "03003006220"

Write-Host "Renaming dera-ismail-khan to dera-ismail-khan-II..." -ForegroundColor Cyan
Write-Host ""

# 1. Rename PHP file
$oldPage = "pages\$oldName.php"
$newPage = "pages\$newName.php"

if (Test-Path $oldPage) {
    if (Test-Path $newPage) {
        Write-Host "Page $newPage already exists, skipping rename" -ForegroundColor Yellow
    } else {
        Rename-Item -Path $oldPage -NewName "$newName.php"
        Write-Host "Renamed page: $oldName.php -> $newName.php" -ForegroundColor Green
    }
} else {
    Write-Host "Page $oldPage not found" -ForegroundColor Red
    exit
}

# 2. Rename image
$oldImage = "images\$priority\$oldName.jpeg"
$newImage = "images\$priority\$newName.jpeg"

if (Test-Path $oldImage) {
    if (Test-Path $newImage) {
        Write-Host "Image $newImage already exists, skipping rename" -ForegroundColor Yellow
    } else {
        Rename-Item -Path $oldImage -NewName "$newName.jpeg"
        Write-Host "Renamed image: $oldName.jpeg -> $newName.jpeg" -ForegroundColor Green
    }
} else {
    Write-Host "Image $oldImage not found" -ForegroundColor Red
}

# 3. Update URLs in the page file
if (Test-Path $newPage) {
    $content = Get-Content $newPage -Raw
    
    # Replace all URL references
    $content = $content -replace "services\.armydogcenter\.org\.pk/$oldName\.php", "services.armydogcenter.org.pk/$newName.php"
    $content = $content -replace "services\.armydogcenter\.org\.pk/Dera Ismail Khan\.php", "services.armydogcenter.org.pk/$newName.php"
    $content = $content -replace "services\.armydogcenter\.org\.pk/dera-ismail-khan\.php", "services.armydogcenter.org.pk/$newName.php"
    
    # Replace image paths
    $content = $content -replace "/kpk/$oldName\.jpeg", "/kpk/$newName.jpeg"
    $content = $content -replace "/kpk/dera-ismail-khan\.jpeg", "/kpk/$newName.jpeg"
    
    Set-Content -Path $newPage -Value $content -Encoding UTF8
    Write-Host "Updated URLs in page" -ForegroundColor Green
}

Write-Host ""
Write-Host "Completed!" -ForegroundColor Cyan


