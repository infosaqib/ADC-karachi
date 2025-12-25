# PowerShell script to create the LAST page from pages.txt and rename its image

# Read pages.txt and filter out empty lines, get last entry
$pagesContent = Get-Content "pages.txt" | Where-Object { $_.Trim() -ne "" }
$lastEntry = $pagesContent[-1]

Write-Host "Processing last entry from pages.txt..." -ForegroundColor Cyan
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

# Function to get contact numbers based on priority
function Get-ContactNumbers {
    param($priority)
    
    if ($priority -eq "03008977885") {
        return @("03003006220", "03332874135")
    } elseif ($priority -eq "03003006220") {
        return @("03332874135", "03003406220")
    }
    return @()
}

# Function to get city display name from file name
function Get-CityDisplayName {
    param($cityFile)
    
    $parts = $cityFile -split '-'
    $displayName = ($parts | ForEach-Object { 
        if ($_.Length -gt 0) {
            $_.Substring(0,1).ToUpper() + $_.Substring(1).ToLower() 
        }
    }) -join ' '
    
    return $displayName
}

# Process the last entry
$info = Extract-CityInfo $lastEntry
if ($info) {
    $cityFile = $info.CityFile
    $priority = $info.Priority
    $cityDisplay = Get-CityDisplayName $cityFile
    $contacts = Get-ContactNumbers $priority
    
    Write-Host "City: $cityFile (Priority: $priority, Display: $cityDisplay)" -ForegroundColor Yellow
    
    # Check if page already exists
    $outputPath = "pages\$cityFile.php"
    if (Test-Path $outputPath) {
        Write-Host "Page already exists: $outputPath" -ForegroundColor Yellow
    } else {
        # Read the template
        $template = Get-Content "badin.php" -Raw
        
        # STEP 1: Replace city name
        $template = $template -replace '\bBadin\b', $cityDisplay
        $template = $template -replace '\bbadin\b', $cityFile
        
        # STEP 2: Replace priority number
        $template = $template -replace 'Army Dog Center [^|]+\| 03003006220', "Army Dog Center $cityDisplay | $priority"
        $template = $template -replace '<b class="text-blue-500">03003006220</b>', "<b class=`"text-blue-500`">$priority</b>"
        $template = $template -replace 'property="og:title" content="Army Dog Center [^|]+\| 03003006220"', "property=`"og:title`" content=`"Army Dog Center $cityDisplay | $priority`""
        $template = $template -replace 'name="twitter:title" content="Army Dog Center [^|]+\| 03003006220"', "name=`"twitter:title`" content=`"Army Dog Center $cityDisplay | $priority`""
        $template = $template -replace '"name":"Army Dog Center Badin"', "`"name`":`"Army Dog Center $cityDisplay`""
        
        # STEP 3: Replace Call to Action phone number FIRST
        $template = $template -replace '(<a href="tel:03332874135"[^>]*>\s*Call Now)', "<a href=`"tel:$priority`" class=`"inline-block bg-white text-teal-500 font-bold py-3 px-8 rounded-lg shadow-md hover:bg-cyan-50 transition duration-300 transform hover:-translate-y-1 hover:shadow-lg`">Call Now"
        
        # STEP 4: Replace contact numbers
        $template = $template -replace 'href="tel:03456826761"', "href=`"tel:$($contacts[0])`""
        $template = $template -replace '>03456826761<', ">$($contacts[0])<"
        $template = $template -replace 'href="tel:03003406220"', "href=`"tel:$($contacts[1])`""
        $template = $template -replace '>03003406220<', ">$($contacts[1])<"
        
        # STEP 5: Replace image paths
        $template = $template -replace '/sindh/badin\.jpeg', "/kpk/$($cityFile.ToLower()).jpeg"
        $template = $template -replace '/sindh/', "/kpk/"
        $template = $template -replace "/kpk/$cityFile\.jpeg", "/kpk/$($cityFile.ToLower()).jpeg"
        $template = $template -replace "/kpk/$cityDisplay\.jpeg", "/kpk/$($cityFile.ToLower()).jpeg"
        
        # STEP 6: Replace URLs
        $template = $template -replace 'services\.armydogcenter\.org\.pk/badin\.php', "services.armydogcenter.org.pk/$($cityFile.ToLower()).php"
        $template = $template -replace 'services\.armydogcenter\.org\.pk/Badin\.php', "services.armydogcenter.org.pk/$($cityFile.ToLower()).php"
        
        # STEP 7: Fix og:site_name
        $template = $template -replace 'property="og:site_name" content="Army Dog Center [^"]+"', "property=`"og:site_name`" content=`"Army Dog Center Pakistan`""
        
        # STEP 8: Update schema description
        $template = $template -replace '"description":"Professional dog services in Badin', "`"description`":`"Professional dog services in $cityDisplay"
        
        # STEP 9: Update all text references
        $template = $template -replace 'Our Trained Dogs in Badin', "Our Trained Dogs in $cityDisplay"
        $template = $template -replace 'German Shepherd Dog - Badin', "German Shepherd Dog - $cityDisplay"
        $template = $template -replace 'Our highly trained dogs are experts in detection and tracking in Badin', "Our highly trained dogs are experts in detection and tracking in $cityDisplay"
        $template = $template -replace 'Our Professional Services in Badin', "Our Professional Services in $cityDisplay"
        $template = $template -replace 'Need Professional Dog Services in Badin\?', "Need Professional Dog Services in $cityDisplay?"
        $template = $template -replace 'Our team is available 24/7 to assist with any emergency situation in Badin\.', "Our team is available 24/7 to assist with any emergency situation in $cityDisplay."
        
        # Write to pages folder
        $template | Set-Content $outputPath -Encoding UTF8
        Write-Host "Created page: $outputPath" -ForegroundColor Green
    }
    
    # Rename image
    $imageFolder = "images\$priority"
    $targetImage = "$imageFolder\$cityFile.jpeg"
    
    if (Test-Path $targetImage) {
        Write-Host "Image already exists: $targetImage" -ForegroundColor Green
    } else {
        if (Test-Path $imageFolder) {
            # Get first available image (prefer WhatsApp images)
            $allImages = Get-ChildItem -Path $imageFolder -Filter "*.jpeg" | Sort-Object Name
            $images = $allImages | Where-Object { 
                $_.Name -match '^WhatsApp' -or ($_.Name -notmatch '^[a-z-]+\.jpeg$')
            }
            
            if ($images.Count -eq 0) {
                $images = $allImages
            }
            
            if ($images.Count -gt 0) {
                $firstImage = $images[0]
                $sourcePath = $firstImage.FullName
                
                try {
                    Rename-Item -Path $sourcePath -NewName "$cityFile.jpeg" -ErrorAction Stop
                    Write-Host "Renamed image: $($firstImage.Name) -> $cityFile.jpeg" -ForegroundColor Green
                } catch {
                    Write-Host "Failed to rename image: $($firstImage.Name) - $_" -ForegroundColor Red
                }
            } else {
                Write-Host "No available images found in: $imageFolder" -ForegroundColor Red
            }
        } else {
            Write-Host "Image folder not found: $imageFolder" -ForegroundColor Red
        }
    }
} else {
    Write-Host "Could not extract city info from last entry" -ForegroundColor Red
}

Write-Host ""
Write-Host "Completed!" -ForegroundColor Cyan

