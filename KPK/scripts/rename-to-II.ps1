# Script to rename pages and images to -II versions and update URLs

$cities = @(
    @{ Old = "rawalpindi"; New = "rawalpindi-II"; Priority = "03008977885" },
    @{ Old = "pakistan"; New = "pakistan-II"; Priority = "03008977885" },
    @{ Old = "jhelum"; New = "jhelum-II"; Priority = "03003006220" },
    @{ Old = "islamabad"; New = "islamabad-II"; Priority = "03003006220" }
)

Write-Host "Renaming pages and images to -II versions..." -ForegroundColor Cyan
Write-Host ""

foreach ($city in $cities) {
    $oldName = $city.Old
    $newName = $city.New
    $priority = $city.Priority
    
    Write-Host "Processing: $oldName -> $newName" -ForegroundColor Yellow
    
    # 1. Rename PHP file
    $oldPage = "pages\$oldName.php"
    $newPage = "pages\$newName.php"
    
    if (Test-Path $oldPage) {
        if (Test-Path $newPage) {
            Write-Host "  Page $newPage already exists, skipping rename" -ForegroundColor Yellow
        } else {
            Rename-Item -Path $oldPage -NewName "$newName.php"
            Write-Host "  Renamed page: $oldName.php -> $newName.php" -ForegroundColor Green
        }
    } else {
        Write-Host "  Page $oldPage not found" -ForegroundColor Red
        continue
    }
    
    # 2. Rename image
    $oldImage = "images\$priority\$oldName.jpeg"
    $newImage = "images\$priority\$newName.jpeg"
    
    if (Test-Path $oldImage) {
        if (Test-Path $newImage) {
            Write-Host "  Image $newImage already exists, skipping rename" -ForegroundColor Yellow
        } else {
            Rename-Item -Path $oldImage -NewName "$newName.jpeg"
            Write-Host "  Renamed image: $oldName.jpeg -> $newName.jpeg" -ForegroundColor Green
        }
    } else {
        Write-Host "  Image $oldImage not found" -ForegroundColor Red
    }
    
    # 3. Update URLs in the page file
    if (Test-Path $newPage) {
        $content = Get-Content $newPage -Raw
        
        # Replace all URL references
        $content = $content -replace "services\.armydogcenter\.org\.pk/$oldName\.php", "services.armydogcenter.org.pk/$newName.php"
        $content = $content -replace "services\.armydogcenter\.org\.pk/$($oldName.Substring(0,1).ToUpper() + $oldName.Substring(1))\.php", "services.armydogcenter.org.pk/$newName.php"
        
        # Replace image paths
        $content = $content -replace "/kpk/$oldName\.jpeg", "/kpk/$newName.jpeg"
        $content = $content -replace "/kpk/$($oldName.Substring(0,1).ToUpper() + $oldName.Substring(1))\.jpeg", "/kpk/$newName.jpeg"
        
        Set-Content -Path $newPage -Value $content -Encoding UTF8
        Write-Host "  Updated URLs in page" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "Completed!" -ForegroundColor Cyan


