
<?php
require_once("inc/header.php");
require_once("inc/navigation.php");
if(isset($_GET['homepage'])){
     require_once("inc/homepage.php");
}
else if(isset($_GET['addElectionPage'])){
     require_once "inc/add_election.php";
}else if(isset($_GET['addCandinatePage'])){
     require_once "inc/add_candinates.php";
}else if(isset($_GET['viewResult'])){
     require_once("inc/viewResults.php");
}

?>
<?php
require_once("inc/footer.php");
?>