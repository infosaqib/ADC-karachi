# PowerShell script to rename images based on city names

# Read pages.txt and get last 3 entries
$pagesContent = Get-Content "pages.txt"
$last3Entries = $pagesContent[-3..-1]

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
foreach ($entry in $last3Entries) {
    $info = Extract-CityInfo $entry
    if ($info) {
        $cityFile = $info.CityFile
        $priority = $info.Priority
        $imageFolder = "images\$priority"
        $targetImage = "$imageFolder\$cityFile.jpeg"
        
        Write-Host "Processing: $cityFile (Priority: $priority)"
        
        # Check if image folder exists
        if (Test-Path $imageFolder) {
            # Get first available image in the folder
            $images = Get-ChildItem -Path $imageFolder -Filter "*.jpeg" | Sort-Object Name
            if ($images.Count -gt 0) {
                $firstImage = $images[0]
                $sourcePath = $firstImage.FullName
                
                # Check if target already exists
                if (Test-Path $targetImage) {
                    Write-Host "  Image already exists: $targetImage"
                } else {
                    # Rename the image
                    Rename-Item -Path $sourcePath -NewName "$cityFile.jpeg" -ErrorAction SilentlyContinue
                    if ($?) {
                        Write-Host "  Renamed: $($firstImage.Name) -> $cityFile.jpeg"
                    } else {
                        Write-Host "  Failed to rename: $($firstImage.Name)"
                    }
                }
            } else {
                Write-Host "  No images found in: $imageFolder"
            }
        } else {
            Write-Host "  Folder not found: $imageFolder"
        }
    }
}

Write-Host "`nImage renaming completed!"

