<?php 
    include 'config.php';
    
    $content = $_GET['args']; // www.whatever.com/register.php?args=etc
                              //                      codes
                              //               0 = infoWanted
                              //      1,2,3,4... = etc
                              //
                              
    $contentSplit = explode("ว", $content);
    if ($contentSplit[0] == "serverInfo" or $contentSplit[0] == "")
    {
        /*
            0 - version
            1 - serverName
            2 - description
            3 - public
        */
        $publicS = "true"; // converted the bool into string so its more readable by clients
        if ($public == false){$publicS = "false";}
        die( $version . 'ว' . $serverName . 'ว' . $description . 'ว' . $publicS);
    }
    if ($contentSplit[0] == "showForumTopics")
    {
        $directories = glob("$currentDir/forum/*" , GLOB_ONLYDIR);
        $formattedDirs = "";
        foreach ($directories as &$directory) 
        {
            $splitDir = explode("/",$directory);
            $directory = $splitDir[count($splitDir)-1];
            $formattedDirs = $formattedDirs . $directory . "ว";
        }
        die ($formattedDirs);
    }
    if ($contentSplit[0] == "showUsers")
    {
        $directories = glob("$currentDir/users/*" , GLOB_ONLYDIR);
        $formattedDirs = "";
        foreach ($directories as &$directory) 
        {
            $splitDir = explode("/",$directory);
            $directory = $splitDir[count($splitDir)-1];
            $formattedDirs = $formattedDirs . $directory . "ว";
        }
        die ($formattedDirs);
    }
    if ($contentSplit[0] == "showForumPosts")
    {
        if ($contentSplit[1] == "")
        {
            die("ERROR. FORUM TOPIC NOT SPECIFIED!");
        }
        $files = glob("$currentDir/forum/". $contentSplit[1] . "/*" );
        $formattedFiles = "";
        foreach ($files as &$file) 
        {
            $splitFiles = explode("/",$file);
            $file = $splitFiles[count($splitFiles)-1];
            $formattedFiles = $formattedFiles . $file . "ว";
        }
        die ($formattedFiles);
    }
    if ($contentSplit[0] == "getCurrenciesDailyGain")
    {       // first currency daily gain      second currency daily gain   convertation cost
        die("$firstCurrencyDailyGainว$secondCurrencyDailyGainว$convertationCost");
    }
    if ($contentSplit[0] == "getCurrenciesNames")
    {
        die ("$firstCurrencyNameว$secondCurrencyNameว");
    }
    if ($contentSplit[0] == "getRanks") //ร
    {
        $rankNames1 = "";
        $rankIDS1 = "";
        foreach ($rankIDs as &$rankID)
        {
            $rankIDS1 = $rankIDS1 . $rankID . "ว";
        } 
        foreach ($RankNames as &$rankName)
        {
            $rankNames1 = $rankNames1 . $rankName . "ว";
        }
        die($rankIDS1."ร".$rankNames1);
    }
    if ($contentSplit[0] == "getPermsCanRank")
    {
        $permissions = "";
        foreach ($canRank as &$rankID)
        {
            $permissions = $permissions . $rankID . "ว";
        } 
        die($permissions);
    }
    if ($contentSplit[0] == "getPermsCanBan")
    {
        $permissions = "";
        foreach ($canBan as &$rankID)
        {
            $permissions = $permissions . $rankID . "ว";
        } 
        die($permissions);
    }
    if ($contentSplit[0] == "getPermsCanRemoveThreads")
    {
        $permissions = "";
        foreach ($canDeleteThreads as &$rankID)
        {
            $permissions = $permissions . $rankID . "ว";
        } 
        die($permissions);
    }
?>