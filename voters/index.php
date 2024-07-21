<?php
require_once("inc/header.php");
require_once("inc/navigation.php");
?>
<div class="row my-3 mx-2">
     <div class="col-12">
          
          <?php
               $totalActiveElection = mysqli_query($db, "SELECT * FROM elections WHERE status='Active'") or die(mysqli_error($db));
               if (mysqli_num_rows($totalActiveElection) > 0) {
                    while ($data = mysqli_fetch_assoc($totalActiveElection)) {
                         $election_id = $data['id'];
                         $election_topic = $data['election_topic'];
                         ?>
                         <table class="table">
                              <thead>
                                   <tr class="bg-green">
                                        <th colspan="4" class="text-black"><h5>ELECTION TOPIC: <?php echo strtoupper($election_topic); ?></h5></th>
                                   </tr>
                                   <tr>
                                        <th>Photo</th>
                                        <th>Candidate Details</th>
                                        <th>No of Votes</th>
                                        <th>Action</th>
                                   </tr>
                              </thead>
                              <tbody>
                              <?php
                              $fetchingCandidates = mysqli_query($db, "SELECT * FROM candinate_details WHERE election_id='$election_id'") or die(mysqli_error($db));
                              if (mysqli_num_rows($fetchingCandidates) > 0) {
                                   while ($candidate = mysqli_fetch_assoc($fetchingCandidates)) {
                                        $candidate_id = $candidate['id'];
                                        $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candinate_id='$candidate_id'") or die(mysqli_error($db));
                                        $totalVotes = mysqli_num_rows($fetchingVotes);
                                        ?>
                                        <tr>
                                             <td><img src="<?php echo $candidate['candinate_photo']; ?>" class="candinate_photo"/></td>
                                             <td><?php echo "<b>" . $candidate['candinate_name'] . "</b><br>" . $candidate['candinate_details']; ?></td>
                                             <td><?php echo $totalVotes; ?></td>
                                             <td>
                                                  <?php
                                                  $checkIfVoteCasted = mysqli_query($db, "SELECT * FROM votings WHERE voters_id='" . $_SESSION['user_id'] . "' AND election_id='" . $election_id . "'") or die(mysqli_error($db));
                                                  $isVoteCasted = mysqli_num_rows($checkIfVoteCasted);

                                                  if ($isVoteCasted > 0) {
                                                       $voteCastedData = mysqli_fetch_assoc($checkIfVoteCasted);
                                                       $voteCastedToCandidate = $voteCastedData['candinate_id'];
                                                       if ($voteCastedToCandidate == $candidate_id) {
                                                            ?>
                                                            <img src="../assets/images/vote.jpeg" width="100px;">
                                                            <?php
                                                       }
                                                  } else {
                                                       ?>
                                                       <button class="btn btn-success" onclick="CastVote(<?php echo $election_id; ?>, <?php echo $candidate_id; ?>, <?php echo $_SESSION['user_id']; ?>)">Vote</button>
                                                       <?php
                                                  }
                                                  ?>
                                             </td>
                                        </tr>
                                        <?php
                                   }
                              } else {
                                   ?>
                                   <tr>
                                        <td colspan="4">No candidates available for this election.</td>
                                   </tr>
                                   <?php
                              }
                              ?>
                              </tbody>
                         </table>
                         <?php
                    }
               } else {
                    echo "No any Active Election.";
               }
          ?>
     </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
     const CastVote = (e_id, c_id, v_id) => {
          $.ajax({
               type: "POST",
               url: "inc/ajaxCalls.php",
               data: { e_id: e_id, c_id: c_id, v_id: v_id },
               success: function(response) {
                    if (response === "Success") {
                         location.assign("index.php?voteCasted=1");
                    } else {
                         location.assign("index.php?voteNotCasted=1");
                    }
               }
          });
     }
</script>

<?php
require_once("inc/footer.php");
?>
