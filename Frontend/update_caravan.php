<?php
session_start();
$_SESSION['user_id'] = 1; // TEMP login simulation

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $vehicle_id = (int)$_POST['vehicle_id'];

    // Fetch existing caravan to preserve data
    $select = "SELECT * FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
    $result = mysqli_query($conn, $select);

    if (!$result || mysqli_num_rows($result) === 0) {
        die("Caravan not found or does not belong to this user.");
    }

    $existing = mysqli_fetch_assoc($result);

    // Only overwrite if field has data
    $make     = !empty($_POST['vehicle_make'])     ? $_POST['vehicle_make']     : $existing['vehicle_make'];
    $model    = !empty($_POST['vehicle_model'])    ? $_POST['vehicle_model']    : $existing['vehicle_model'];
    $bodytype = !empty($_POST['vehicle_bodytype']) ? $_POST['vehicle_bodytype'] : $existing['vehicle_bodytype'];
    $fuel     = !empty($_POST['fuel_type'])        ? $_POST['fuel_type']        : $existing['fuel_type'];
    $mileage  = !empty($_POST['mileage'])          ? $_POST['mileage']          : $existing['mileage'];
    $location = !empty($_POST['location'])         ? $_POST['location']         : $existing['location'];
    $year     = !empty($_POST['year'])             ? $_POST['year']             : $existing['year'];
    $doors    = !empty($_POST['num_doors'])        ? $_POST['num_doors']        : $existing['num_doors'];
    $video    = !empty($_POST['video_url'])        ? $_POST['video_url']        : $existing['video_url'];
    $image    = !empty($_POST['image_url'])        ? $_POST['image_url']        : $existing['image_url'];

    // Update query
    $update = "UPDATE vehicle_details SET
        vehicle_make = '$make',
        vehicle_model = '$model',
        vehicle_bodytype = '$bodytype',
        fuel_type = '$fuel',
        mileage = '$mileage',
        location = '$location',
        year = '$year',
        num_doors = '$doors',
        video_url = '$video',
        image_url = '$image'
        WHERE vehicle_id = $vehicle_id AND user_id = $user_id";

    if (mysqli_query($conn, $update)) {
        header("Location: my_caravans.php");
        exit;
    } else {
        echo "Error updating caravan: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}
?>
