<?php
require_once("../../admin/inc/config.php"); // Adjusted path to match directory structure

if(isset($_POST['e_id']) && isset($_POST['c_id']) && isset($_POST['v_id'])){
     $vote_date = date("Y-m-d");
     $vote_time = date("h:i:s a");

     $query = "INSERT INTO votings (election_id, voters_id, candinate_id, vote_date, vote_time) 
               VALUES ('".$_POST['e_id']."', '".$_POST['v_id']."', '".$_POST['c_id']."', '".$vote_date."', '".$vote_time."')";

     mysqli_query($db, $query) or die(mysqli_error($db));
     echo "Success";
} else {
     echo "Error: Missing parameters.";
}
?>
