<?php
function encrypt($string, $key)
{
    $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB);
    return $encrypted;
}
function decrypt($string, $key)
{
    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB);
    return $decrypted;
}
function stringContainsSymbol($string)
{
    if (strpos($string, '<') !== false) {
        return true;
    }
    else if (strpos($string, '>') !== false) {
        return true;
    }
    else if (strpos($string, '\\') !== false) {
        return true;
    }
    else if (strpos($string, '/') !== false) {
        return true;
    }
    else if (strpos($string, '.') !== false) {
        return true;
    }
    else{return false;}
}
function writeFile($directoryFile,$string)
{
        $file = fopen($directoryFile, "w");
        $txt = $string;
        fwrite($file, $txt);
        fclose($file);
}
function readAFile ($directory, $error = "ERROR. File not found!")
{
    $myfile = fopen($directory, "r") or die($error);
    $content = fread($myfile,filesize($directory)) or die($error);
    fclose($myfile) or die($error);
    return $content;
}
function verify ($user, $password)
{
    if (strlen($password) > 32)
    {
        die ("ERROR. PASSWORD LONGER THAN 32 CHARACTERS!");
    }
    include 'config.php';
    $userPasswordDir = $currentDir."/users/$user/password";
    $userDir= $currentDir."/users/$user/";
    $encryptedPassword = readAFile($userPasswordDir, "ERROR. User possibly does not exist!");
    $decryptedPass = decrypt($encryptedPassword,$encryptionKey);
    if  (trim($decryptedPass) ==  trim($password))
    {
        // daily reward check
        $lastTime = readAFile("$userDir"."lastonline");
        if ($lastTime != date("Y/m/d"))
        {
            // $firstCurrencyDailyGain     pocket1
            // $secondCurrencyDailyGain    pocket2
            $pocket1 = strval(intval(readAFile("$userDir/pocket1"))+intval($firstCurrencyDailyGain));
            $pocket2 = strval(intval(readAFile("$userDir/pocket2"))+intval($secondCurrencyDailyGain));
            writeFile("$userDir/pocket1", $pocket1);
            writeFile("$userDir/pocket2", $pocket2);
            writeFile("$userDir/lastonline", date("Y/m/d"));
        }
        return true;
    }
    else
    {
        return false;
    }
}
?>