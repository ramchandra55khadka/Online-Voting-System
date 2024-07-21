<?php
// Include database connection
include 'config.php';

if (isset($_GET['delete_id'])) {
    $d_id = $_GET['delete_id'];
    mysqli_query($db, "DELETE FROM candinate_details WHERE id='$d_id'") or die(mysqli_error($db));
    echo "<script>location.assign('index.php?addCandinatePage=1&deleted=1');</script>";
}

if (isset($_GET['added'])) {
    echo '<div class="alert alert-success my-4" role="alert">
        <h6>Candidate has been added successfully.</h6>
    </div>';
} elseif (isset($_GET['largeFile'])) {
    echo '<div class="alert alert-danger my-3">
        Candidate image is too large; please upload an image up to 10MB.
    </div>';
} elseif (isset($_GET['invalidFile'])) {
    echo '<div class="alert alert-danger my-3">
        Invalid Image type (Only .jpg, .png, .jpeg files are allowed).
    </div>';
} elseif (isset($_GET['failed'])) {
    echo '<div class="alert alert-danger my-3">
        Image uploading failed, please try again.
    </div>';
} elseif (isset($_GET['deleted'])) {
    echo '<div class="alert alert-danger my-4" role="alert">
        <i>Candidate has been deleted successfully.</i>
    </div>';
} elseif (isset($_GET['updated'])) {
    echo '<div class="alert alert-success my-4" role="alert">
        <h6>Candidate has been updated successfully.</h6>
    </div>';
}

?>

<div class="container my-3">
    <div class="row">
        <div class="col-md-4 mt-4">
            <h3>Add New Candidate</h3>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <select class="form-control" name="election_id" required>
                        <option value="">Select Election</option>
                        <?php
                        $fetchingElection = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                        $isAnyElectionAdded = mysqli_num_rows($fetchingElection);
                        if ($isAnyElectionAdded > 0) {
                            while ($row = mysqli_fetch_assoc($fetchingElection)) {
                                $election_id = $row['id'];
                                $election_name = $row['election_topic'];
                                $allowed_candinates = $row['no_of_candinates'];
                                $fetchingCandinates = mysqli_query($db, "SELECT * FROM candinate_details WHERE election_id='$election_id'") or die(mysqli_error($db));
                                $added_candinates = mysqli_num_rows($fetchingCandinates);

                                if ($added_candinates < $allowed_candinates) {
                                    echo '<option value="' . $election_id . '">' . $election_name . '</option>';
                                }
                            }
                        } else {
                            echo '<option value="">Please select the election first</option>';
                        }
                        ?>
                    </select>
                </div>
                <br/>
                <div class="form-group">
                    <input type="text" name="candinate_name" placeholder="Candidate Name" class="form-control" required>
                </div>
                <br/>
                <div class="form-group">
                    <p>Upload Photo:</p>
                    <input type="file" name="candinate_photo" class="form-control" required>
                </div>
                <br/>
                <div class="form-group">
                    <input type="text" name="candinate_details" placeholder="Enter Candidate Details" class="form-control" required>
                </div>
                <br/>
                <input type="submit" value="Add Candidate" name="addCandinateBtn" class="btn btn-success">
            </form>
        </div>
        <div class="col-md-8 mt-4 m">
            <h3>Candidate Details</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">S.N</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Name</th>
                        <th scope="col">Details</th>
                        <th scope="col">Election</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $fetchingData = mysqli_query($db, "SELECT * FROM candinate_details") or die(mysqli_error($db));
                    $isAnyCandinateAdded = mysqli_num_rows($fetchingData);
                    if ($isAnyCandinateAdded > 0) {
                        $sno = 1;
                        while ($row = mysqli_fetch_assoc($fetchingData)) {
                            $election_id = $row['election_id'];
                            $fetchingElectionName = mysqli_query($db, "SELECT * FROM elections WHERE id='$election_id'") or die(mysqli_error($db));
                            if (mysqli_num_rows($fetchingElectionName) > 0) {
                                $execFetchingElectionNameQuery = mysqli_fetch_assoc($fetchingElectionName);
                                $election_name = $execFetchingElectionNameQuery['election_topic'];
                            } else {
                                $election_name = 'Unknown';
                            }
                            $candinate_photo = $row['candinate_photo'];
                    ?>
                            <tr>
                                <td><?php echo $sno++; ?></td>
                                <td><img src="<?php echo $candinate_photo; ?>" class="candinate_photo"/></td>
                                <td><?php echo $row['candinate_name']; ?></td>
                                <td><?php echo $row['candinate_details']; ?></td>
                                <td><?php echo $election_name; ?></td>
                                <td style="display:flex;">
                                    <button style="margin-right:5px;" class="btn btn-sm btn-warning" onClick="EditData(<?php echo $row['id']; ?>)">Edit</button>
                                    <button class="btn btn-sm btn-danger" onClick="DeleteData(<?php echo $row['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6"><i>No candidates have been added yet.</i></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    const EditData = (c_id) => {
        location.assign("edit_candidate.php?id=" + c_id);
    }
    const DeleteData = (candidate_id) => {
        let c = confirm("Are you sure you want to delete this candidate?");
        if (c == true) {
            location.assign("index.php?addCandinatePage=1&delete_id=" + candidate_id);
        }
    }
</script>

<?php
if (isset($_POST['addCandinateBtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $candinate_name = mysqli_real_escape_string($db, $_POST['candinate_name']);
    $candinate_details = mysqli_real_escape_string($db, $_POST['candinate_details']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    // Photograph logic
    $targetted_folder = "../assets/images/candinate_photos/";
    $candinate_photo = $targetted_folder . rand(1111111111, 99999999999) . "_" . rand(1111111111, 99999999999) . "_" . basename($_FILES['candinate_photo']['name']);
    $candinate_photo_tmp_name = $_FILES['candinate_photo']['tmp_name'];
    $candinate_photo_type = strtolower(pathinfo($candinate_photo, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "png", "jpeg");
    $image_size = $_FILES['candinate_photo']['size'];

    if ($image_size <= 10000000) {
        if (in_array($candinate_photo_type, $allowed_types)) {
            if (move_uploaded_file($candinate_photo_tmp_name, $candinate_photo)) {
                mysqli_query($db, "INSERT INTO candinate_details(election_id, candinate_name, candinate_details, candinate_photo, inserted_by, inserted_on) VALUES ('$election_id', '$candinate_name', '$candinate_details', '$candinate_photo', '$inserted_by', '$inserted_on')") or die(mysqli_error($db));
                echo "<script>location.assign('index.php?addCandinatePage=1&added=1');</script>";
            } else {    
                echo "<script>location.assign('index.php?addCandinatePage=1&failed=1');</script>";
            }
        } else {
            echo "<script>location.assign('index.php?addCandinatePage=1&invalidFile=1');</script>";
        }
    } else {
        echo "<script>location.assign('index.php?addCandinatePage=1&largeFile=1');</script>";
    }
}
?>
