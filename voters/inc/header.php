<?php
session_start();
require_once("../admin/inc/config.php");
if($_SESSION['key']!="VotersKey")
{
     echo "<script>location.assign('../admin/inc/logout.php');?</script>";
     die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Voterspanel -Online Voting System</title>
     <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
     <div class="container-fluid">
          <div class="row bg-white1 text-black">

               <div class="col-1">
                    <img src="../assets/images/logo.jpeg" width="80px"/>

               </div>
               <div class="col-11 my-auto">
                    <h3 align="center"><b>ONLINE VOTING SYSTEM - <small>Welcome </small></b></?php echo $_SESSION['username']; ?></small></h3>
               </div>
               <div class="col-12 my-auto">
                    <h3 align="center"><b>Voters Panel </b></small></h3>
               </div>
          </div>

     </div>
