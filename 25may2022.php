<?php 

 
// header('WWW-Authenticate: Basic realm="My Realm"');
// echo "YOU 2"; THIS WORKS AND IT'S HOW YOU REPLY TO A POST MESSAGE

 /* file_put_contents("message.txt",  file_get_contents("php://input") );   // this works
 echo "BALANCE IS 1200 minutes"; */


 
 
 

 // THERE IS A MISSING DOT BEFORE APPROVAL IN THE ECOCASH TRANSACTION MESSAGE
  
 $rechargeMessage = file_get_contents("php://input");


 file_put_contents("message.txt",  $rechargeMessage, FILE_APPEND ); 
 file_put_contents("message.txt",  "\n", FILE_APPEND );  

 /**********************************************
      str_contains(strtoupper($rechargeMessage), strtoupper("EcoCash: Transfer Confirmation")) && 
        str_contains(strtoupper($rechargeMessage), strtoupper("RTGS\$")) &&
        str_contains(strtoupper($rechargeMessage), strtoupper("from")) &&
        str_contains(strtoupper($rechargeMessage), strtoupper("Approval Code")) &&
        str_contains(strtoupper($rechargeMessage), strtoupper("New wallet balance"))
  **********************************************/


  
 
 if(
        stristr(strtoupper($rechargeMessage), strtoupper("EcoCash: Transfer Confirmation")) != false && 
        stristr(strtoupper($rechargeMessage), strtoupper("RTGS\$")) != false &&
        stristr(strtoupper($rechargeMessage), strtoupper("from")) != false &&
        stristr(strtoupper($rechargeMessage), strtoupper("Approval Code")) != false &&
        stristr(strtoupper($rechargeMessage), strtoupper("New wallet balance")) != false
   )
 {

    
    echo "rechargeMessage contains 'EcoCash: Transfer Confirmation' <br>";
    echo "rechargeMessage contains \"RTGS\$\" <br>";
    echo "rechargeMessage contains 'from' <br>";
    echo "rechargeMessage contains 'Approval Code' <br>";
    echo "rechargeMessage contains 'New wallet balance' <br>";

    
      $balanceInDollars = trim(substr($rechargeMessage, stripos($rechargeMessage, "\$") + 1, 1 + (stripos($rechargeMessage, "from") - 1) - (stripos($rechargeMessage, "\$") + 1)));


      /********************* EXPERIMENT *************************************/
          $balance = $balanceInDollars * 202;
          if(is_int($balance ) == false)
          {
              $balance = round($balance);
          }

      /**********************************************************************/

      echo "balance " . $balance . "<br>";
 
       echo "from " . stripos($rechargeMessage, "from") . "<br>";
       echo "m: " . stripos($rechargeMessage, "m", stripos($rechargeMessage, "from")) . "<br>";

        

      $ecocashUsernamePlusDot = trim(substr($rechargeMessage, stripos($rechargeMessage, "m", stripos($rechargeMessage, "from"))  + 1, 1 + (stripos($rechargeMessage, "Approval") - 1) - (stripos($rechargeMessage, "m", stripos($rechargeMessage, "from")) + 1)));

      $ecocashUsername = trim($ecocashUsernamePlusDot, " .");
      echo "ecocashUsername " . $ecocashUsername . "<br>";

      
      echo "Approval " . stripos($rechargeMessage, "Approval") . "<br>";
      echo ": " . stripos($rechargeMessage, ":", stripos($rechargeMessage, "Approval")) . "<br>";

      

      $approvalCodePlusDot = trim(substr($rechargeMessage, 1 + stripos($rechargeMessage, ":", stripos($rechargeMessage, "Approval")), 1 + (stripos($rechargeMessage, "New") - 1) - (1 + stripos($rechargeMessage, ":", stripos($rechargeMessage, "Approval")))));

      $approvalCode = trim($approvalCodePlusDot, " .");
      echo "approvalCode " . $approvalCode . "<br>";


      /******************** EXPERIMENT ********************/
      settype($balance, "int");
      $mysqli = new mysqli("localhost", "hman", "qsefthuko", "accountsDB");
      

      if(false == $mysqli->query("INSERT INTO accountInfo VALUES ('$ecocashUsername', '$approvalCode', '$balance')"))
      {
                echo "FAILED $mysqli->query...";
      }
      
 }


 ?>