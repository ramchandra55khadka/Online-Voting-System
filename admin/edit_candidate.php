<?php
// Include database connection
include '../admin/inc/config.php';

if (isset($_GET['id'])) {
    $candidate_id = $_GET['id'];

    // Fetch candidate details
    $fetchingData = mysqli_query($db, "SELECT * FROM candinate_details WHERE id='$candidate_id'") or die(mysqli_error($db));
    if (mysqli_num_rows($fetchingData) > 0) {
        $candidate = mysqli_fetch_assoc($fetchingData);
    } else {
        echo "<script>location.assign('index.php?addCandinatePage=1');</script>";
        exit;
    }
} else {
    echo "<script>location.assign('index.php?addCandinatePage=1');</script>";
    exit;
}

// Update candidate details
if (isset($_POST['updateCandidateBtn'])) {
    $candinate_name = mysqli_real_escape_string($db, $_POST['candinate_name']);
    $candinate_details = mysqli_real_escape_string($db, $_POST['candinate_details']);
    $updated_by = $_SESSION['username'];
    $updated_on = date("Y-m-d");

    // Handle photo update
    if ($_FILES['candinate_photo']['name']) {
        $targetted_folder = "../assets/images/candinate_photos/";
        $candinate_photo = $targetted_folder . rand(1111111111, 99999999999) . "_" . rand(1111111111, 99999999999) . "_" . basename($_FILES['candinate_photo']['name']);
        $candinate_photo_tmp_name = $_FILES['candinate_photo']['tmp_name'];
        $candinate_photo_type = strtolower(pathinfo($candinate_photo, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "png", "jpeg");
        $image_size = $_FILES['candinate_photo']['size'];

        if ($image_size <= 10000000) {
            if (in_array($candinate_photo_type, $allowed_types)) {
                if (move_uploaded_file($candinate_photo_tmp_name, $candinate_photo)) {
                    $photo_query = ", candinate_photo='$candinate_photo'";
                } else {    
                    echo "<script>location.assign('edit_candidate.php?id=$candidate_id&failed=1');</script>";
                    exit;
                }
            } else {
                echo "<script>location.assign('edit_candidate.php?id=$candidate_id&invalidFile=1');</script>";
                exit;
            }
        } else {
            echo "<script>location.assign('edit_candidate.php?id=$candidate_id&largeFile=1');</script>";
            exit;
        }
    } else {
        $photo_query = "";
    }

    // Update query
    mysqli_query($db, "UPDATE candinate_details SET candinate_name='$candinate_name', candinate_details='$candinate_details' $photo_query WHERE id='$candidate_id'") or die(mysqli_error($db));
    echo "<script>location.assign('index.php?addCandinatePage=1&updated=1');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Candidate</title>
</head>
<body>
    <div class="container">
        <h3>Edit Candidate Details</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="candinate_name" value="<?php echo $candidate['candinate_name']; ?>" class="form-control" required>
            </div>
            <br/>
            <div class="form-group">
                <p>Upload Photo (leave empty to keep existing photo):</p>
                <input type="file" name="candinate_photo" class="form-control">
            </div>
            <br/>
            <div class="form-group">
                <input type="text" name="candinate_details" value="<?php echo $candidate['candinate_details']; ?>" class="form-control" required>
            </div>
            <br/>
            <input type="submit" value="Update Candidate" name="updateCandidateBtn" class="btn btn-success">
        </form>
    </div>
</body>
</html>
