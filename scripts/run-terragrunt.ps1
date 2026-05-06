Param(
  [Parameter(Mandatory = $true)]
  [ValidateSet("dev", "test", "prod")]
  [string]$Environment
)

$RepoRoot = Split-Path -Parent $PSScriptRoot
$EnvFile = Join-Path $RepoRoot ".env"

if (-not (Test-Path $EnvFile)) {
  Write-Error "Missing .env at $EnvFile. Copy .env.example to .env and set licenceplate."
  exit 1
}

Get-Content $EnvFile | ForEach-Object {
  if ($_ -match '^\s*$' -or $_ -match '^\s*#') { return }
  $parts = $_ -split '=', 2
  if ($parts.Length -lt 2) { return }
  $name = $parts[0].Trim()
  $value = $parts[1].Trim()
  [System.Environment]::SetEnvironmentVariable($name, $value, 'Process')
  Set-Item -Path "Env:$name" -Value $value
}

$TerragruntDir = Join-Path $RepoRoot "src\terraform\terragrunt\$Environment"
Set-Location $TerragruntDir
terragrunt init -input=false
terragrunt apply -auto-approve -input=false
