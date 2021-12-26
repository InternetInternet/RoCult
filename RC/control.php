<?php
    include 'config.php';
    include 'functions.php';
    $content = $_GET['args']; // www.whatever.com/register.php?args=usernameวpasswordวaction...etc
                              //                      codes
                              //               0 = username
                              //               1 = password
                              //               2 = action
                              //        3,4,5... = arguments
    $contentSplit = explode("ว", $content);
    $userDir= $currentDir."/users/".$contentSplit[0]."/";
    if (stringContainsSymbol($content))
    {
        die ("ERROR. Arguments contain ILLEGAL symbols");
    }
    if (count($contentSplit) < 3)
    {
        die ("ERROR. INSUFFICIENT ARGUMENTS!");
    }
    if (verify($contentSplit[0],$contentSplit[1]) == false)
    {
        die ("ERROR. Unauthorized access!!");
    }
    
    if (readAFile($userDir . "rank") == "banned")
    {
        die ("You have been banned from this instance! Reason: " . readAFile($userDir . "terminationReason"));
    }
    if ($contentSplit[2] == "verify")
    {
        die("S");
    }
    //CHANGE PASSWORD
    if ($contentSplit[2] == "changePassword")
    {
        if (stringContainsSymbol($contentSplit[3]))
        {
            die ("ERROR. New password contains ILLEGAL symbols");
        }
        if (strlen($contentSplit[3]) > 32)
        {
            die ("ERROR. PASSWORD LONGER THAN 32 CHARACTERS!");
        }
        if (strlen($contentSplit[3]) < 8)
        {
            die ("ERROR. PASSWORD SHORTER THAN 8 CHARACTERS!");
        }
        writeFile($currentDir . "/users/" . $contentSplit[0] . "/password",encrypt($contentSplit[3],$encryptionKey));
    }
    if ($contentSplit[2] == "tixtobux")
    { // intval strval
        $coinsAmmount = intval($contentSplit[3]);
        $buxCoinsAmmount = $coinsAmmount / intval($convertationCost);
        $pocket1 = intval(readAFile("$userDir/pocket1"));
        $pocket2 = intval(readAFile("$userDir/pocket2"));
        if ($pocket1 >= $coinsAmmount)
        {
            $pocket2 = round($pocket2 + $buxCoinsAmmount);
            $pocket1 = round($pocket1 - $coinsAmmount);
            writeFile("$userDir/pocket1", $pocket1);
            writeFile("$userDir/pocket2", $pocket2);
        }
        else{die ("ERROR. NOT ENOUGH AMOUNT OF TIX");}
    }
    if ($contentSplit[2] == "buxtotix")
    { // intval strval
        $coinsAmmount = intval($contentSplit[3]);
        $buxCoinsAmmount = $coinsAmmount * intval($convertationCost);
        $pocket1 = intval(readAFile("$userDir/pocket1"));
        $pocket2 = intval(readAFile("$userDir/pocket2"));
        if ($pocket2 >= $coinsAmmount)
        {
            $pocket2 = round($pocket2 - $coinsAmmount);
            $pocket1 = round($pocket1 + $buxCoinsAmmount);
            writeFile("$userDir/pocket1", $pocket1);
            writeFile("$userDir/pocket2", $pocket2);
        }
        else{die ("ERROR. NOT ENOUGH AMOUNT OF BUX");}
    }
    if ($contentSplit[2] == "sendMessage")
    { // $contentSplit[3] = topic
      // $contentSplit[4] = themessage
      // $contentSplit[4] = username
      if (strlen($contentSplit[3]) > 25)
      {
          die("ERROR. THE TITLE IS LONGER THAN 25 CHARACTERS");
      }
      if (strlen( $contentSplit[4]) > 120)
      {
          die("ERROR. THE BODY IS LONGER THAN 120 CHARACTERS");
      }
      if ($contentSplit[3] == "" or $contentSplit[4] == ""){die ("ERROR. sendMessage ARGUMENTS NOT SPECIFIED CORRECTLY!");}
      $fileName = date("Y|m|d") . "ว" . date('h:i:s') ."ว" . $contentSplit[0] . "ว" . $contentSplit[3];
      $userDir= $currentDir."/users/".$contentSplit[4]."/";
      $inboxDir = $userDir . "inbox/$fileName";
      writeFile($inboxDir,$contentSplit[4]);
    }
    if ($contentSplit[2] == "ban")
    { // $contentSplit[3] = user
      // $contentSplit[4] = thereason
      $ranking = readAFile($userDir . "rank");
      if (strlen($contentSplit[4]) > 120)
      {
          die("ERROR. REASON LONGER THAN 120 CHARACTERS");
      }
      if (in_array($ranking, $canBan))
      {
          $targetUserDir= $currentDir."/users/".$contentSplit[3]."/";
          $targetRankFile = $targetUserDir . "rank";
          $terminationReasonFile = $targetUserDir . "terminationReason";
          if (readAFile($targetRankFile) == "banned")
          {
              die ("User's already banned!");
          }
          if (intval(readAFile($targetRankFile)) < intval($ranking))
          {
              writeFile($targetRankFile,"banned");
              if ($contentSplit[4] == "")
              {
                  writeFile($terminationReasonFile,"No reason specified");
              }
              else 
              {
                  writeFile($terminationReasonFile,$contentSplit[4]);
              }
          }
          else {die("ERROR. You do not have the authority to ban higher or same ranked users!");}
      } else {die ("ERROR. You do not have the authority to ban people!");}
    }
    if ($contentSplit[2] == "rank")
    {
      $ranking = readAFile($userDir . "rank");
      if ($contentSplit[0] == $contentSplit[3])
      {
          die("ERROR. You can't rank yourself!");
      }
      if (in_array($ranking, $canRank))
      {
          // $contentSplit[3] user
          // $contentSplit[4] rank
          $targetUserDir= $currentDir."/users/".$contentSplit[3]."/";
          $targetRankFile = $targetUserDir . "rank";
          
          if (intval($contentSplit[4]) < intval($ranking))
          {
              if (intval(readAFile($targetRankFile)) <= intval($ranking))
              {
                  writeFile($targetRankFile,$contentSplit[4]);  
              }
              else 
              {
                  die("ERROR. You can't rank people that are higher than your rank!");
              }
          }
          else {die("ERROR. You do not have the authority to rank people ranks that are higher than your rank!");}
      } else {die ("ERROR. You do not have the authority to rank people!");}
    }
    if ($contentSplit[2] == "createForumPost")
    {
        // $contentSplit[3] = forum topic
        // $contentSplit[4] = thread title
        // $contentSplit[5] = the body of thread
        $forumTopic = $contentSplit[3];
        $threadTitle = $contentSplit[4];
        $threadBody = $contentSplit[5];
        /*
                FORUM FORMAT
                ว to seperate elements in messages
                ร to seperate messages
                
                usernameวbodyวdateวtimeร usrnameวtopicวdateวtime
        */
        
        //     $currentDir = getcwd(); // /storage/ssd5/057/17267057/public_html/RC for example
        
      if (strlen($threadTitle) < 6)
      {
          die("ERROR. TITLE HAS LESS THAN 6 CHARACTERS");
      }
      if (strlen($threadTitle) > 20)
      {
          die("ERROR. TITLE HAS MORE THAN 20 CHARACTERS");
      }
      if (strlen($threadBody) > 250)
      {
          die("ERROR. BODY HAS MORE THAN 250 CHARACTERS");
      }
      if (strlen($threadBody) < 8)
      {
          die("ERROR. BODY HAS LESS THAN 8 CHARACTERS");
      }
        
        $forumTopicDir = "$currentDir/forum/" . $forumTopic . "/";
        
        
        //         1= date
        //         2= time
        //         3= username
        //         4= thead title
        $fileName = date("Y|m|d") . "^" . date('h:i:s') ."^" . $contentSplit[0] . "^" . $threadTitle;
        
        $theFile = $forumTopicDir . $fileName;
        
        $body = $contentSplit[0] . "ว" . $threadBody ."ว" . date("Y|m|d") ."ว" . date('h:i:s');
        writeFile($theFile, $body);
    }
    
    
    
    if ($contentSplit[2] == "replyForumPost")
    {
        // $contentSplit[3] = forum topic
        // $contentSplit[4] = thread title (the filename)
        // $contentSplit[5] = the body of thread
        $forumTopic = $contentSplit[3];
        $threadTitle = $contentSplit[4];
        $threadBody = $contentSplit[5];
        /*
                FORUM FORMAT
                ว to seperate elements in messages
                ร to seperate messages
                
                usernameวbodyวdateวtimeร usrnameวtopicวdateวtime
        */
        
        //     $currentDir = getcwd(); // /storage/ssd5/057/17267057/public_html/RC for example
        

      if (strlen($threadBody) > 250)
      {
          die("ERROR. BODY HAS MORE THAN 250 CHARACTERS");
      }
      if (strlen($threadBody) < 8)
      {
          die("ERROR. BODY HAS LESS THAN 8 CHARACTERS");
      }
        
        $forumTopicDir = "$currentDir/forum/" . $forumTopic . "/";
        
        
        //         1= date
        //         2= time
        //         3= username
        //         4= thead title

        $theFile = $forumTopicDir . $threadTitle;
        
        if (!file_exists($theFile))
        {
            die("ERROR. Forum thread does not exist!");
        }
        
        $lastForum = readAFile($theFile);
        
        $body = $contentSplit[0] . "ว" . $threadBody ."ว" . date("Y|m|d") ."ว" . date('h:i:s');
        
        $fullResultBody = $lastForum . "ร" . $body;
        writeFile($theFile, $fullResultBody);
    }
    
    if ($contentSplit[2] == "removeThread")
    {
        //          3= forum topic
        //         4= thead title
      $threadTitleSplit = explode("^",$contentSplit[4]);
      $ranking = readAFile($userDir . "rank");
      if (in_array($ranking, $canDeleteThreads))
      {
          if (intval(readAFile($currentDir."/users/".$threadTitleSplit[2]."/rank")) < $ranking or $threadTitleSplit[2] == $contentSplit[0])
          {
            $forumTopicDir = "$currentDir/forum/" . $contentSplit[3] . "/" . $contentSplit[4];
            unlink($forumTopicDir);
          }
          else{die("ERROR. You can't remove threads of users that are higher rank than you are!");}
      }else{die("ERROR. Not authorized to remove threads!");}
    }
    
    if ($contentSplit[2] == "getGames")
    { //  date("h:i")
        $theFile = $currentDir . "/LastCheckedGames";
        $lastDate = readAFile($theFile);
        if ($lastDate != "")
        {
            $splitVals = explode(":",$lastDate);
            $currentDate = explode(":",date("h:i"));
            if (intval($currentDate[1]) > intval($splitVals[1]) + 5
             or intval($currentDate[0]) > intval($splitVals[0]) + 1)
             {
                 // REFRESH GAMES
             }
        }
        else if ($lastDate == "")
        {
            writeFile($theFile,date("h:i"));
            
            // REFRESH GAMES
        }
        // RETURN GAMES
    }
    if ($contentSplit[2] == "createGame")
    { // username^title^description^LastPing
      /*
          3 = title
          4 = description
      */
      
    }
?>