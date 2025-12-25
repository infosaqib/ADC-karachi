# PowerShell script to create PHP pages from badin.php template

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
    
    # Convert file name to display name (e.g., "army-dog-center" -> "Army Dog Center")
    $parts = $cityFile -split '-'
    $displayName = ($parts | ForEach-Object { 
        $_.Substring(0,1).ToUpper() + $_.Substring(1).ToLower() 
    }) -join ' '
    
    return $displayName
}

# Process each of the last 3 entries
foreach ($entry in $last3Entries) {
    $info = Extract-CityInfo $entry
    if ($info) {
        $cityFile = $info.CityFile
        $priority = $info.Priority
        $cityDisplay = Get-CityDisplayName $cityFile
        $contacts = Get-ContactNumbers $priority
        
        Write-Host "Processing: $cityFile (Priority: $priority, Display: $cityDisplay)"
        
        # Read the template
        $template = Get-Content "badin.php" -Raw
        
        # STEP 1: Replace city name first (to avoid conflicts)
        $template = $template -replace '\bBadin\b', $cityDisplay
        $template = $template -replace '\bbadin\b', $cityFile
        
        # STEP 2: Replace priority number (03003006220) in specific contexts only (before contact numbers)
        # In title
        $template = $template -replace 'Army Dog Center [^|]+\| 03003006220', "Army Dog Center $cityDisplay | $priority"
        # In header
        $template = $template -replace '<b class="text-blue-500">03003006220</b>', "<b class=`"text-blue-500`">$priority</b>"
        # In og:title
        $template = $template -replace 'content="Army Dog Center [^|]+\| 03003006220"', "content=`"Army Dog Center $cityDisplay | $priority`""
        # In twitter:title
        $template = $template -replace 'name="twitter:title" content="Army Dog Center [^|]+\| 03003006220"', "name=`"twitter:title`" content=`"Army Dog Center $cityDisplay | $priority`""
        
        # STEP 3: Replace contact numbers AFTER priority replacement
        # Replace first contact number (03456826761) - replace both tel: and display
        $template = $template -replace 'href="tel:03456826761"', "href=`"tel:$($contacts[0])`""
        $template = $template -replace '>03456826761<', ">$($contacts[0])<"
        # Replace second contact number (03003406220) - replace both tel: and display
        # Use -replace with proper escaping
        $template = $template -replace 'href="tel:03003406220"', "href=`"tel:$($contacts[1])`""
        $template = $template -replace '>03003406220<', ">$($contacts[1])<"
        
        # STEP 4: Replace image paths (sindh -> kpk, use lowercase cityFile)
        # Need to handle both "badin" and the replaced city name
        $template = $template -replace '/sindh/[^/"]+\.jpeg', "/kpk/$cityFile.jpeg"
        $template = $template -replace '/sindh/', "/kpk/"
        
        # STEP 5: Replace URL in meta tags and schema (use lowercase cityFile)
        $template = $template -replace 'services\.armydogcenter\.org\.pk/[Bb]adin\.php', "services.armydogcenter.org.pk/$cityFile.php"
        
        # STEP 6: Replace Call to Action phone number (03332874135 -> priority)
        # Only if it's still there (might have been replaced already)
        $template = $template -replace 'href="tel:03332874135"', "href=`"tel:$priority`""
        
        # Write to pages folder
        $outputPath = "pages\$cityFile.php"
        $template | Set-Content $outputPath -Encoding UTF8
        Write-Host "Created: $outputPath"
    }
}

Write-Host "`nPage creation completed!"

