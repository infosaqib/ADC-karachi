# PowerShell script to create the LAST 3 PHP pages from badin.php template
# This is for initial approval before creating all pages

# Ensure pages folder exists
if (-not (Test-Path "pages")) {
    New-Item -ItemType Directory -Path "pages"
}

# Read pages.txt and filter out empty lines, get last 3
$pagesContent = Get-Content "pages.txt" | Where-Object { $_.Trim() -ne "" }
$last3Entries = $pagesContent[-3..-1]

Write-Host "Creating LAST 3 pages for approval..." -ForegroundColor Cyan
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
    
    # Convert file name to display name (e.g., "army-dog-center" -> "Army Dog Center", "upper-dir" -> "Upper Dir")
    $parts = $cityFile -split '-'
    $displayName = ($parts | ForEach-Object { 
        if ($_.Length -gt 0) {
            $_.Substring(0,1).ToUpper() + $_.Substring(1).ToLower() 
        }
    }) -join ' '
    
    return $displayName
}

# Process each of the last 3 entries
$processedCount = 0

foreach ($entry in $last3Entries) {
    $info = Extract-CityInfo $entry
    if ($info) {
        $cityFile = $info.CityFile
        $priority = $info.Priority
        $cityDisplay = Get-CityDisplayName $cityFile
        $contacts = Get-ContactNumbers $priority
        
        Write-Host "Processing: $cityFile (Priority: $priority, Display: $cityDisplay)" -ForegroundColor Yellow
        
        # Read the template
        $template = Get-Content "badin.php" -Raw
        
        # STEP 0: Replace URLs FIRST (before city name replacements to avoid conflicts)
        # Use exact cityFile from pages.txt (already lowercase with hyphens, exactly as in pages.txt)
        $exactUrl = $cityFile  # cityFile from pages.txt is already in correct format (lowercase, hyphens)
        # Replace in og:url - match filename and replace with exact URL from pages.txt
        $template = $template -replace '(property="og:url" content="https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        # Replace in canonical
        $template = $template -replace '(rel="canonical" href="https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        # Replace in schema URLs
        $template = $template -replace '("url":"https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        $template = $template -replace '("@id":"https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        
        # STEP 1: Replace city name (case-insensitive, but preserve case in template)
        # Replace "Badin" with city display name
        $template = $template -replace '\bBadin\b', $cityDisplay
        # Replace "badin" (lowercase) with city file name (lowercase)
        $template = $template -replace '\bbadin\b', $cityFile
        
        # STEP 2: Replace priority number (03003006220) - this is the main number shown
        # In title tag
        $template = $template -replace 'Army Dog Center [^|]+\| 03003006220', "Army Dog Center $cityDisplay | $priority"
        # In header h2
        $template = $template -replace '<b class="text-blue-500">03003006220</b>', "<b class=`"text-blue-500`">$priority</b>"
        # In og:title
        $template = $template -replace 'property="og:title" content="Army Dog Center [^|]+\| 03003006220"', "property=`"og:title`" content=`"Army Dog Center $cityDisplay | $priority`""
        # In twitter:title
        $template = $template -replace 'name="twitter:title" content="Army Dog Center [^|]+\| 03003006220"', "name=`"twitter:title`" content=`"Army Dog Center $cityDisplay | $priority`""
        # In schema markup name
        $template = $template -replace '"name":"Army Dog Center Badin"', "`"name`":`"Army Dog Center $cityDisplay`""
        
        # STEP 3: Replace Call to Action phone number FIRST (before contact numbers to avoid conflicts)
        # Replace in Call to Action section (has "Call Now" button text)
        $template = $template -replace '(<a href="tel:03332874135"[^>]*>\s*Call Now)', "<a href=`"tel:$priority`" class=`"inline-block bg-white text-teal-500 font-bold py-3 px-8 rounded-lg shadow-md hover:bg-cyan-50 transition duration-300 transform hover:-translate-y-1 hover:shadow-lg`">Call Now"
        
        # STEP 4: Replace contact numbers in Contact Numbers section
        # Replace first contact number (03456826761) - both href and display
        $template = $template -replace 'href="tel:03456826761"', "href=`"tel:$($contacts[0])`""
        $template = $template -replace '>03456826761<', ">$($contacts[0])<"
        # Replace second contact number (03003406220) - both href and display
        $template = $template -replace 'href="tel:03003406220"', "href=`"tel:$($contacts[1])`""
        $template = $template -replace '>03003406220<', ">$($contacts[1])<"
        
        # STEP 5: Replace image paths (sindh -> kpk, use lowercase cityFile)
        $template = $template -replace '/sindh/badin\.jpeg', "/kpk/$($cityFile.ToLower()).jpeg"
        $template = $template -replace '/sindh/', "/kpk/"
        # Also fix any uppercase city names in image paths
        $template = $template -replace "/kpk/$cityFile\.jpeg", "/kpk/$($cityFile.ToLower()).jpeg"
        $template = $template -replace "/kpk/$cityDisplay\.jpeg", "/kpk/$($cityFile.ToLower()).jpeg"
        
        # STEP 6: Fix og:site_name (should be just organization name, not full title)
        $template = $template -replace 'property="og:site_name" content="Army Dog Center [^"]+"', "property=`"og:site_name`" content=`"Army Dog Center Pakistan`""
        
        # STEP 7: Update schema description with city name
        $template = $template -replace '"description":"Professional dog services in Badin', "`"description`":`"Professional dog services in $cityDisplay"
        
        # STEP 8: Update all text references to city in content
        # "Our Trained Dogs in Badin" -> "Our Trained Dogs in {cityDisplay}"
        $template = $template -replace 'Our Trained Dogs in Badin', "Our Trained Dogs in $cityDisplay"
        # "German Shepherd Dog - Badin" -> "German Shepherd Dog - {cityDisplay}"
        $template = $template -replace 'German Shepherd Dog - Badin', "German Shepherd Dog - $cityDisplay"
        # "Our highly trained dogs are experts in detection and tracking in Badin"
        $template = $template -replace 'Our highly trained dogs are experts in detection and tracking in Badin', "Our highly trained dogs are experts in detection and tracking in $cityDisplay"
        # "Our Professional Services in Badin"
        $template = $template -replace 'Our Professional Services in Badin', "Our Professional Services in $cityDisplay"
        # "Need Professional Dog Services in Badin?"
        $template = $template -replace 'Need Professional Dog Services in Badin\?', "Need Professional Dog Services in $cityDisplay?"
        # "Our team is available 24/7 to assist with any emergency situation in Badin."
        $template = $template -replace 'Our team is available 24/7 to assist with any emergency situation in Badin\.', "Our team is available 24/7 to assist with any emergency situation in $cityDisplay."
        
        # STEP 9: Final URL cleanup - ensure all URLs match pages.txt exactly (lowercase, with hyphens)
        # This must be LAST to fix any URLs that might have been affected by city name replacements
        $exactUrl = $cityFile  # Use exact filename from pages.txt (already lowercase with hyphens)
        # Fix og:url - replace any variation with exact URL from pages.txt
        $template = $template -replace '(property="og:url" content="https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        # Fix canonical
        $template = $template -replace '(rel="canonical" href="https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        # Fix schema URLs
        $template = $template -replace '("url":"https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        $template = $template -replace '("@id":"https://services\.armydogcenter\.org\.pk/)[^"]+\.php"', "`$1$exactUrl.php`""
        
        # Write to pages folder (overwrite if exists for approval)
        $outputPath = "pages\$cityFile.php"
        $template | Set-Content $outputPath -Encoding UTF8
        Write-Host "  Created/Updated: $outputPath" -ForegroundColor Green
        $processedCount++
    }
}

Write-Host "`nLast 3 pages creation completed!" -ForegroundColor Cyan
Write-Host "  Created/Updated: $processedCount pages" -ForegroundColor Green
Write-Host "`nPlease review these pages and approve before creating remaining pages." -ForegroundColor Yellow

