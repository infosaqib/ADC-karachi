# PowerShell script to rename images according to city names
# Images should be renamed: {city}.jpeg in the appropriate folder based on priority

Write-Host "Renaming images according to city names..." -ForegroundColor Green
Write-Host ""

# Define which cities use which image folder based on priority
$imageMapping = @{
    "peshawar" = @{
        Folder = "images\03003406220"
        Priority = "03003406220"
    }
    "abbottabad" = @{
        Folder = "images\03332874135"
        Priority = "03332874135"
    }
    "mardan" = @{
        Folder = "images\03003406220"
        Priority = "03003406220"
    }
}

foreach ($city in $imageMapping.Keys) {
    $folder = $imageMapping[$city].Folder
    $targetName = "$city.jpeg"
    $targetPath = Join-Path $folder $targetName
    
    Write-Host "Processing $city..." -ForegroundColor Yellow
    Write-Host "  Folder: $folder" -ForegroundColor Cyan
    Write-Host "  Target: $targetName" -ForegroundColor Cyan
    
    if (-not (Test-Path $folder)) {
        Write-Host "  ERROR: Folder not found!" -ForegroundColor Red
        continue
    }
    
    # Check if target already exists
    if (Test-Path $targetPath) {
        Write-Host "  [OK] Image already exists: $targetName" -ForegroundColor Green
        continue
    }
    
    # Find first available image in folder
    $images = Get-ChildItem $folder -Filter "*.jpeg" | Sort-Object Name
    if ($images.Count -eq 0) {
        Write-Host "  WARNING: No images found in folder" -ForegroundColor Yellow
        continue
    }
    
    # Use first image that's not already a city name
    $sourceImage = $images | Where-Object { $_.Name -notmatch '^(peshawar|abbottabad|mardan)\.jpeg$' } | Select-Object -First 1
    
    if (-not $sourceImage) {
        Write-Host "  WARNING: No suitable image to rename (all may already be city names)" -ForegroundColor Yellow
        continue
    }
    
    Write-Host "  Renaming: $($sourceImage.Name) -> $targetName" -ForegroundColor Cyan
    try {
        Rename-Item -Path $sourceImage.FullName -NewName $targetName -ErrorAction Stop
        Write-Host "  [OK] Successfully renamed" -ForegroundColor Green
    } catch {
        Write-Host "  ERROR: Failed to rename - $($_.Exception.Message)" -ForegroundColor Red
    }
    
    Write-Host ""
}

Write-Host "Done!" -ForegroundColor Green

