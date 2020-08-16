<?php

// Created by Deon van zyl
// Matches LDAP usernames , get email and other info
// 25 June 2013
// Example myusername3
// +++++++++++++++


// +++++++++++++++++++

$ldaphost = "localhost";
$ldapport = 389;
$searchlimit = array("mail");

echo "Connecting to $ldaphost...\n";
$ldapconn=ldap_connect($ldaphost, $ldapport)
   or die("Could not connect to LDAP server");

if ($ldapconn) {
   echo "Binding ...\n";
   $ldapbind=ldap_bind($ldapconn);    // anonymous bind

   if ($ldapbind) {

      $search = fopen("c:\\fullname.csv", "r");

      $match = fopen("c:\\email.csv", "w");
     


      while (($searchrow=fgetcsv($search)) !== FALSE) {

         // Parse usernames for searching
        
	$apple = 0;

	$theline =$searchrow[0];
       
	$usersinthisline=explode(";",$theline);
	
	While ($apple<count($usersinthisline)){ 
	$username = $usersinthisline[$apple];


	echo ("Searching for $theline"."\n");
	 // echo ("Searching for ".$username." END this line! "."\n");

	$username1 = explode("_",$username);

	$username=$username1[0];
	
         $searchdn = "";
         $searchfilter = "CN=".$username;
echo $searchfilter; 
$searchlimit=array("preferredname","sn","mail","telephonenumber","edupersonprimaryaffiliation");

//print_r($searchlimit);

$searchdn = "o=someorginization";


	
         //Search for valid usernames in LDAP and return staff number
         if ($searchdn) {
            $sr=ldap_search($ldapconn, $searchdn, $searchfilter, $searchlimit);
            $info = ldap_get_entries($ldapconn, $sr);

            $i = 0;   
            // The loop produces multiple staff numbers for users with entries in subtrees
            
	 for ($i=0; $i<$info["count"]; $i++) {

                if ($info[$i]["mail"][0]) {
		
                  $mail = $info[$i]["mail"][0];
                   $telephonenumber = $info[$i]["telephonenumber"][0];
                  $preferredname = $info[$i]["preferredname"][0];
                  $sn = $info[$i]["sn"][0]; 
                  $edupersonprimaryaffiliation = $info[$i]["edupersonprimaryaffiliation"][0];
                 
                	
                }else {
                 echo "No Result";
                }
                

                fwrite($match, $searchfilter.",".$preferredname.",".$sn.",".$mail.",".$telephonenumber.",".$edupersonprimaryaffiliation."\r\n");
                
              echo "$preferredname,$sn,$mail,$telephonenumber, $edupersonprimaryaffiliation". "\n";;
                
                
	} // End For

	$apple++;
         } 

	}
      }
      fclose($search);
      fclose($match);
      
   }
   echo "Closing connection\n";
   ldap_close($ldapconn);

}
