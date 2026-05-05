$files = Get-ChildItem -Path "c:\FE_MONEFY_WEB\WEB\resources\views" -Filter "*.blade.php"

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw

    $content = $content -replace 'href="(?:\.\./)?pages/history\.html"|href="history\.html"|href="\.\./history\.html"', 'href="{{ route(''history'') }}"'
    $content = $content -replace 'href="(?:\.\./)?pages/analytic\.html"|href="analytic\.html"|href="\.\./analytic\.html"', 'href="{{ route(''analytic'') }}"'
    $content = $content -replace 'href="(?:\.\./)?pages/profile\.html"|href="profile\.html"|href="\.\./profile\.html"', 'href="{{ route(''profile'') }}"'
    $content = $content -replace 'href="(?:\.\./)?pages/add-wallet\.html"|href="add-wallet\.html"|href="\.\./add-wallet\.html"', 'href="{{ route(''add-wallet'') }}"'
    $content = $content -replace 'href="(?:\.\./)?pages/bills\.html"|href="bills\.html"|href="\.\./bills\.html"', 'href="{{ route(''bills'') }}"'
    $content = $content -replace 'href="(?:\.\./)?pages/saving\.html"|href="saving\.html"|href="\.\./saving\.html"', 'href="{{ route(''saving'') }}"'
    $content = $content -replace 'href="(?:\.\./)?index\.html"|href="index\.html"', 'href="{{ route(''home'') }}"'

    $content = $content -replace 'href="(?:\.\./|\./)?assets/css/styles\.css"', 'href="{{ asset(''assets/css/styles.css'') }}"'
    $content = $content -replace 'src="(?:\.\./|\./)?assets/images/logo\.png"', 'src="{{ asset(''assets/images/logo.png'') }}"'
    
    $content = $content -replace 'href="(?:\.\./|\./)?node_modules/bootstrap/dist/css/bootstrap\.min\.css"', 'href="{{ asset(''node_modules/bootstrap/dist/css/bootstrap.min.css'') }}"'
    $content = $content -replace 'src="(?:\.\./|\./)?node_modules/bootstrap/dist/js/bootstrap\.bundle\.min\.js"', 'src="{{ asset(''node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'') }}"'

    Set-Content -Path $file.FullName -Value $content -Encoding UTF8
}
