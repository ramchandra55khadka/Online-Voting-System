<?php
include '../admin/inc/config.php';  // Ensure the correct path

// Handle the update
if (isset($_POST['updateElectionBtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $number_of_candinates = mysqli_real_escape_string($db, $_POST['number_of_candinates']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
    $updated_on = date("Y-m-d");

    $date1 = date_create($updated_on);
    $date2 = date_create($starting_date);
    $diff = date_diff($date1, $date2);
    if ($diff->format("%R%a") > 0) {
        $status = "Inactive";
    } else {
        $status = "Active";
    }

    // Update in db
    mysqli_query($db, "UPDATE elections 
    SET election_topic='$election_topic', no_of_candinates='$number_of_candinates', starting_date='$starting_date', ending_date='$ending_date', status='$status' 
    WHERE id='$election_id'") or die(mysqli_error($db));
    header("Location: index.php?addElectionPage=1&updated=1");
    exit();
}

// Get election data for editing
$election_id = isset($_GET['id']) ? mysqli_real_escape_string($db, $_GET['id']) : '';
$election = mysqli_query($db, "SELECT * FROM elections WHERE id='$election_id'") or die(mysqli_error($db));
$data = mysqli_fetch_assoc($election);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Election</title>
    <link rel="stylesheet" href="path_to_your_css_file.css">
</head>
<body>
    <div class="container">
        <h3>Edit Election</h3>
        <form method="post">
            <input type="hidden" name="election_id" value="<?php echo $data['id']; ?>">
            <div class="form_group">
                <input type="text" name="election_topic" value="<?php echo $data['election_topic']; ?>" class="form-control" required>
            </div><br>
            <div class="form_group">
                <input type="number" name="number_of_candinates" value="<?php echo $data['no_of_candinates']; ?>" class="form-control" required>
            </div><br>
            <div class="form_group">
                <input type="text" onfocus="this.type='date'" name="starting_date" value="<?php echo $data['starting_date']; ?>" class="form-control" required>
            </div><br>
            <div class="form_group">
                <input type="text" onfocus="this.type='date'" name="ending_date" value="<?php echo $data['ending_date']; ?>" class="form-control" required>
            </div><br>
            <input type="submit" value="Update Election" name="updateElectionBtn" class="btn btn-success">
        </form>
    </div>
</body>
</html>
