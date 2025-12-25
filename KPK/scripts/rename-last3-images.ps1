# PowerShell script to rename images for the LAST 3 pages from pages.txt
# This is for initial approval before renaming all images

# Read pages.txt and filter out empty lines, get last 3
$pagesContent = Get-Content "pages.txt" | Where-Object { $_.Trim() -ne "" }
$last3Entries = $pagesContent[-3..-1]

Write-Host "Renaming images for LAST 3 pages..." -ForegroundColor Cyan
Write-Host ""

# Function to extract city name and priority from a line
function Extract-CityInfo {
    param($line)
    
    if ($line -match 'href="https://services\.armydogcenter\.org\.pk/([^"]+)\.php".*\| (\d+)') {
        $cityFile = $matches[1]
        $priority = $matches[2]
        return @{
            CityFile = $cityFile
            Priority = $priority
        }
    }
    return $null
}

# Process each of the last 3 entries
$renamedCount = 0
$skippedCount = 0

foreach ($entry in $last3Entries) {
    $info = Extract-CityInfo $entry
    if ($info) {
        $cityFile = $info.CityFile
        $priority = $info.Priority
        $imageFolder = "images\$priority"
        $targetImage = "$imageFolder\$cityFile.jpeg"
        
        Write-Host "Processing: $cityFile (Priority: $priority)" -ForegroundColor Yellow
        
        # Check if image folder exists
        if (-not (Test-Path $imageFolder)) {
            Write-Host "  Folder not found: $imageFolder" -ForegroundColor Red
            continue
        }
        
        # Check if target already exists
        if (Test-Path $targetImage) {
            Write-Host "  Image already exists: $cityFile.jpeg" -ForegroundColor Green
            $skippedCount++
            continue
        }
        
        # Get first available image in the folder
        $allImages = Get-ChildItem -Path $imageFolder -Filter "*.jpeg" | Sort-Object Name
        $images = $allImages | Where-Object { 
            $_.Name -match '^WhatsApp' -or ($_.Name -notmatch '^[a-z-]+\.jpeg$')
        }
        
        # If no filtered images, use all images
        if ($images.Count -eq 0) {
            $images = $allImages
        }
        
        if ($images.Count -gt 0) {
            $firstImage = $images[0]
            $sourcePath = $firstImage.FullName
            
            try {
                Rename-Item -Path $sourcePath -NewName "$cityFile.jpeg" -ErrorAction Stop
                Write-Host "  Renamed: $($firstImage.Name) -> $cityFile.jpeg" -ForegroundColor Green
                $renamedCount++
            } catch {
                Write-Host "  Failed to rename: $($firstImage.Name) - $_" -ForegroundColor Red
            }
        } else {
            Write-Host "  No available images found in: $imageFolder" -ForegroundColor Red
        }
    }
}

Write-Host ""
Write-Host "Image renaming completed!" -ForegroundColor Cyan
Write-Host "  Renamed: $renamedCount images" -ForegroundColor Green
Write-Host "  Already correct: $skippedCount images" -ForegroundColor Yellow
