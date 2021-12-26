<?php
    $version = '0.2';
    $serverName = "my server!";
    $description = "welcome to my server!! :D"; // THIS IS NOT REALLY USED
    $public = false;
    $secretCode = "anime sucks";        // if $public is false registration will require a secret code. you set it here.
    
    
    $firstCurrencyName = "tix";
    $firstCurrencyDailyGain = "10"; // the amount of currency received daily
    
    $secondCurrencyName = "cultbux";
    $secondCurrencyDailyGain = "0";      // the amount of currency received daily
    
    $convertationCost = "10"; // amount of first currency needed to convert to the other and visa versa
    
    
    $encryptionKey = "T@eq}'PF6}/w;zwpY2w_)s3yPwmpBhqw"; // make sure to never leave this default because it may leave security breaches!
    
    $currentDir = getcwd(); // /storage/ssd5/057/17267057/public_html/RC for example




    $rankIDs   = array("1"    ,"2"    ,"3"        ,"4"     ,"5");
    $RankNames = array("user", "vip", "moderator","admin"  ,"owner");
    
    /*
    ACCOUNT RANKINGS  (works like a hierarchy)
    
    owner      5
    
    admin      4
    
    moderator  3
    
    vip        2
    
    user       1
    */
    
    $canBan = array("5","4","3");
    $canRank = array("5","4");
    $canDeleteThreads = array("5","4","3","2","1");
    
    
    
    $tunnelRoomName =     "cultbloxdefault";         // you need to change this to whatever
    $tunnelRoomPassword = "cultbloxdefaultpassword"; // you need to change this to whatever
?>