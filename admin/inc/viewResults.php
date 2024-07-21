<?php
require_once("config.php");

$election_id = $_GET['viewResult'];
?>

<div class="row my-3 mx-2">
     <div class="col-12">
          <h3>Election Results</h3>
          <?php
               $totalActiveElection = mysqli_query($db, "SELECT * FROM elections WHERE id='$election_id'") or die(mysqli_error($db));
               if (mysqli_num_rows($totalActiveElection) > 0) {
                    while ($data = mysqli_fetch_assoc($totalActiveElection)) {
                         $election_topic = $data['election_topic'];
                         ?>
                         <table class="table">
                              <thead>
                                   <tr class="bg-green">
                                        <th colspan="3" class="text-black"><h5>ELECTION TOPIC: <?php echo strtoupper($election_topic); ?></h5></th>
                                   </tr>
                                   <tr>
                                        <th>Photo</th>
                                        <th>Candidate Details</th>
                                        <th>No of Votes</th>
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
                                             <td><img src="<?php echo $candidate['candinate_photo']; ?>" class="candinate_photo" /></td>
                                             <td><?php echo "<b>" . $candidate['candinate_name'] . "</b><br>" . $candidate['candinate_details']; ?></td>
                                             <td><?php echo $totalVotes; ?></td>
                                        </tr>
                                        <?php
                                   }
                              } else {
                                   ?>
                                   <tr>
                                        <td colspan="3">No candidates available for this election.</td>
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
          <hr>
          <h3>Voting Details</h3>
          <table class="table">
               <tr>
                    <th>S.N</th>
                    <th>Voter name</th>
                    <th>Contact No</th>
                    <th>Voted To</th>
                    <th>Date</th>
                    <th>Time</th>
               </tr>
               <?php
               $fetchingVoteDetails = mysqli_query($db, "SELECT * FROM votings WHERE election_id='$election_id'") or die(mysqli_error($db));
               $number_of_votes = mysqli_num_rows($fetchingVoteDetails);
               if ($number_of_votes > 0) {
                    $sno = 1;
                    while ($data = mysqli_fetch_assoc($fetchingVoteDetails)) {
                         $voter_id = $data['voters_id'];
                         $candidate_id = $data['candinate_id'];

                         // Fetch voter details
                         $fetchingUsername = mysqli_query($db, "SELECT * FROM users WHERE id='$voter_id'") or die(mysqli_error($db));
                         $userData = mysqli_fetch_assoc($fetchingUsername);
                         $username = isset($userData['username']) ? $userData['username'] : "No_Data";
                         $contact_no = isset($userData['contact_no']) ? $userData['contact_no'] : "No_Data";

                         // Fetch candidate details
                         $fetchingCandidateName = mysqli_query($db, "SELECT * FROM candinate_details WHERE id='$candidate_id'") or die(mysqli_error($db));
                         $candidateData = mysqli_fetch_assoc($fetchingCandidateName);
                         $candidate_name = isset($candidateData['candinate_name']) ? $candidateData['candinate_name'] : "No_Data";
                         ?>
                         <tr>
                              <td><?php echo $sno++; ?></td>
                              <td><?php echo $username; ?></td>
                              <td><?php echo $contact_no; ?></td>
                              <td><?php echo $candidate_name; ?></td>
                              <td><?php echo $data['vote_date']; ?></td>
                              <td><?php echo $data['vote_time']; ?></td>
                         </tr>
                         <?php
                    }
               } else {
                    ?>
                    <tr>
                         <td colspan="6"><h3><i><strong>No votes have been cast in this election.</strong></i></h3></td>
                    </tr>
                    <?php
               }
               ?>
          </table>
     </div>
</div>

