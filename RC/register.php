<?php
    include 'config.php';
    include 'functions.php';
    $content = $_GET['args']; // www.whatever.com/register.php?args=usernameวpasswordวcode
                              //                      codes
                              //        0 = username
                              //        1 = password
                              //        2 = code         // if its private
    $contentSplit = explode("ว", $content);
    if ($public == false)
    {
        if (count($contentSplit) != 3)
        {
            die("ERROR. Private code is possibly missing or wrong amount of arguments specified!");
        }
        if ($contentSplit[2] != $secretCode)
        {
            die("ERROR. Private code is incorrect!");
        }
    }
    else if ($public == true)
    {
        if (count($contentSplit) != 2)
        {
            die("ERROR. Wrong amount of arguments specified!");
        }
    }
    
    if(stringContainsSymbol($contentSplit[0]) == true)
    {
        die("ERROR. USERNAME CONTAINS SYMBOLS");
    }
    if(stringContainsSymbol($contentSplit[1]) == true)
    {
        die("ERROR. PASSWORD CONTAINS SYMBOLS");
    }
    
    if (strlen($contentSplit[1]) > 32)
    {
        die ("ERROR. PASSWORD LONGER THAN 32 CHARACTERS!");
    }
    if (strlen($contentSplit[1]) < 8)
    {
        die ("ERROR. PASSWORD SHORTER THAN 8 CHARACTERS!");
    }
    
    
    $encryptedPassword = encrypt($contentSplit[1],$encryptionKey);
    
    
    if (is_dir($currentDir . "/users/" . $contentSplit[0]))
    {
        die ("ERROR. USER ALREADY EXISTS");
    }
    else
    {
        mkdir($currentDir . "/users/" . $contentSplit[0]);
        $userDir = $currentDir . "/users/" . $contentSplit[0] . "/";
        mkdir($userDir."inbox");
        writeFile($userDir."joindate",date("Y/m/d"));
        writeFile($userDir."lastonline",date("Y/m/d"));
        writeFile($userDir."rank","1");
        writeFile($userDir."pocket1", $firstCurrencyDailyGain);     // currency 1
        writeFile($userDir."pocket2", $secondCurrencyDailyGain);    // currency 2
        writeFile($userDir."description","Just an average user on $serverName!");
        writeFile($userDir."items","");
        writeFile($userDir."terminationReason","");
        writeFile($userDir."friends","");
        writeFile($userDir."password",$encryptedPassword);
    }
?>