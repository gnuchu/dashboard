param (
  [String]$server
)

$objects = @()

try {
  $self = (Get-WmiObject -Class Win32_ComputerSystem) | foreach { $_.name.toLower() + "." + $_.domain.toLower() }
  if($server -ne $self) {
    $u = "NETWORK\SVC_TST_Autodeploy"
    $p = "Gadge-Trip-Poker-92913"
    $pw = convertto-securestring -AsPlainText -Force -String "${p}"
    $cred = new-object -typename System.Management.Automation.PSCredential -argumentlist "${u}",$pw
    $results = get-WmiObject win32_logicaldisk -Credential $cred -Computername $server | where-object { $_.DeviceID -match "C:|D:" -and $_.driveType -eq 3 }
    $json = $results | convertto-json
    $objects += $json
  }
  else {
    #Don't need a credential when connecting to yourself. Also, causes powershell error. Of course.
    $results = get-WmiObject win32_logicaldisk -Computername $server | where-object { $_.DeviceID -match "C:|D:" -and $_.driveType -eq 3 }
    $json = $results | convertto-json
    $objects += $json
  }
  
}
catch {
  return = $_.exception.message
}

return $objects