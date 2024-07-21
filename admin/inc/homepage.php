
<div class="row ">
    <div class="col-4 mt-4">
        
    </div>
    <div class="col-12 mt-4 mb-2 mx-4">
        <h3>Election</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.N</th>
                    <th scope="col">Election Name</th>
                    <th scope="col">NO of Candidate</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                $isAnyElectionAdded = mysqli_num_rows($fetchingData);
                if ($isAnyElectionAdded > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        $election_id=$row['id'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $row['election_topic']; ?></td>
                            <td><?php echo $row['no_of_candinates']; ?></td>
                            <td><?php echo $row['starting_date']; ?></td>
                            <td><?php echo $row['ending_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="index.php?viewResult=<?php echo $election_id;?>" class="btn btn-sm btn-success">View Results</a>
                            </td>
                            
                        </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="7"><i>No any election is added yet.</i></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
if (isset($_POST['addElectionBtn'])) {
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $number_of_candinates = mysqli_real_escape_string($db, $_POST['number_of_candinates']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $date1 = date_create($inserted_on);
    $date2 = date_create($starting_date);
    $diff = date_diff($date1, $date2);
    if ($diff->format("%R%a") > 0) {
        $status = "Inactive";
    } else {
        $status = "Active";
    }

    // Insert into db
    mysqli_query($db, "INSERT INTO elections(election_topic, no_of_candinates, starting_date, ending_date, status, inserted_by, inserted_on) VALUES ('".$election_topic."', '".$number_of_candinates."', '".$starting_date."', '".$ending_date."', '".$status."', '".$inserted_by."', '".$inserted_on."')") or die(mysqli_error($db));
?>
    <script>location.assign("index.php?addElectionPage=1&added=1")</script>
<?php
}
?>
