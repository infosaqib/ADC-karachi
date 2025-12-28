# PowerShell script to create PHP pages from badin.php template
# Reads pages.txt and creates pages in pages folder

$templateFile = "badin.php"
$pagesFile = "pages.txt"
$pagesFolder = "pages"
$limit = 3  # Only process first 3 pages for now

# Ensure pages folder exists
if (-not (Test-Path $pagesFolder)) {
    New-Item -ItemType Directory -Path $pagesFolder
}

# Read pages.txt
$lines = Get-Content $pagesFile
$count = 0

foreach ($line in $lines) {
    if ($count -ge $limit) { break }
    
    # Extract city name and priority number from line
    # Format: <li><a href="https://services.armydogcenter.net.pk/{city}.php" ...>ARMY DOG CENTER {CITY} | {PRIORITY}</a></li>
    
    if ($line -match 'href="https://services\.armydogcenter\.net\.pk/([^"]+)\.php".*ARMY DOG CENTER ([^|]+) \| (\d+)') {
        $phpFileName = $matches[1]
        $cityName = $matches[2].Trim()
        $priorityNumber = $matches[3]
        
        Write-Host "Processing: $cityName (Priority: $priorityNumber, File: $phpFileName.php)"
        
        # Determine second contact number based on priority
        # Rules: 
        # - Priority 03003406220: contacts = 03332874135, 03003006220 (we'll use 03003006220 as second)
        # - Priority 03332874135: contacts = 03003006220, 03003406220 (we'll use 03003006220 as second)
        $secondContact = "03003006220"
        
        # Read template file
        $content = Get-Content $templateFile -Raw
        
        # Replace priority number in header FIRST (before city name replacement)
        $content = $content -replace 'BADIN <br> 03003406220', "$($cityName.ToUpper()) <br> $priorityNumber"
        
        # Replace city names
        $content = $content -replace "Badin", $cityName
        $content = $content -replace "BADIN", $cityName.ToUpper()
        $content = $content -replace "badin\.php", "$phpFileName.php"
        $content = $content -replace "/badin\.php", "/$phpFileName.php"
        $content = $content -replace "badin", $phpFileName
        
        # Replace priority number in specific contexts (not in contact spans yet)
        # Replace in title
        $content = $content -replace 'Army Dog Center [^|]+\| 03003406220', "Army Dog Center $cityName | $priorityNumber"
        # Replace in schema
        $content = $content -replace '"name": "Army Dog Center [^"]+",', "`"name`": `"Army Dog Center $cityName`","
        $content = $content -replace 'services\.armydogcenter\.net\.pk/badin\.php', "services.armydogcenter.net.pk/$phpFileName.php"
        
        # Handle contact numbers based on priority
        # Use single-line mode for multiline matching
        $options = [System.Text.RegularExpressions.RegexOptions]::Singleline
        if ($priorityNumber -eq "03003406220") {
            # Template has: first=03003406220, second=03332874135
            # We need: first=03003406220 (priority), second=03003006220
            # First contact stays the same, just replace second contact
            # Replace second contact href
            $content = $content -replace 'href="tel:03332874135"', "href=`"tel:$secondContact`""
            # Replace second contact span - match span after the second contact href
            $pattern = "(href=`"tel:$secondContact`"[^>]*>.*?<span>)03332874135(</span>)"
            $content = [regex]::Replace($content, $pattern, "`${1}$secondContact`$2", $options)
        } elseif ($priorityNumber -eq "03332874135") {
            # Template has: first=03003406220, second=03332874135
            # We need: first=03332874135 (priority), second=03003006220
            # Replace second contact first (to avoid conflicts)
            $content = $content -replace 'href="tel:03332874135"', "href=`"tel:$secondContact`""
            # Replace second contact span
            $pattern = "(href=`"tel:$secondContact`"[^>]*>.*?<span>)03332874135(</span>)"
            $content = [regex]::Replace($content, $pattern, "`${1}$secondContact`$2", $options)
            # Now replace first contact href
            $content = $content -replace 'href="tel:03003406220"', "href=`"tel:$priorityNumber`""
            # Replace first contact span
            $pattern = "(href=`"tel:$priorityNumber`"[^>]*>.*?<span>)03003406220(</span>)"
            $content = [regex]::Replace($content, $pattern, "`${1}$priorityNumber`$2", $options)
        }
        
        # Replace in call-to-action button (should be priority number)
        $content = $content -replace 'href="tel:03003406220"', "href=`"tel:$priorityNumber`""
        
        # Update image src to use the new format: https://www.armydogcenter.net.pk/images/services/kpk/{city}.jpeg
        $imageSrc = "https://www.armydogcenter.net.pk/images/services/kpk/$phpFileName.jpeg"
        $content = $content -replace 'src="https://armydogcenter\.net\.pk/images/dogcard-6\.webp"', "src=`"$imageSrc`""
        
        # Write to pages folder
        $outputFile = Join-Path $pagesFolder "$phpFileName.php"
        $content | Set-Content $outputFile -Encoding UTF8
        
        Write-Host "Created: $outputFile"
        $count++
    } else {
        Write-Warning "Line did not match pattern: $line"
    }
}

Write-Host "`nFirst $limit pages created successfully!"
