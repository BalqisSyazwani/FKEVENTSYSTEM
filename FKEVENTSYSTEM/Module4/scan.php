<?php

include '../INCLUDE/db.php';

date_default_timezone_set("Asia/Kuala_Lumpur");

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

if (isset($_POST['submit_attendance'])) {

    $event_id = (int)$_POST['event_id'];

    $student_name = trim($_POST['student_name']);
    $student_id = trim($_POST['student_id']);
    $club_name = trim($_POST['club_name']);
    $event_name = trim($_POST['event_name']);
    $attendance_status = trim($_POST['attendance_status']);
    $volunteer_status = trim($_POST['volunteer_status']);

    $attendance_date = date("Y-m-d");
    $attendance_time = date("H:i:s");

    // Check duplicate attendance
    $checkSql = "
        SELECT *
        FROM attendance
        WHERE event_id = ?
        AND student_id = ?
    ";

    $stmt = $conn->prepare($checkSql);

    if ($stmt) {

        $stmt->bind_param(
            "is",
            $event_id,
            $student_id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            echo "
            <script>
                alert('Attendance already recorded.');
                window.location='scan.php?event_id=$event_id';
            </script>
            ";

            exit();
        }

        $stmt->close();
    }

    $success = insertEventAttendanceRecord(
        $event_id,
        $student_name,
        $student_id,
        $club_name,
        $event_name,
        $attendance_date,
        $attendance_time,
        $attendance_status,
        $volunteer_status
    );

    if ($success) {

        echo "
        <script>
            alert('Attendance Recorded Successfully');
            window.location='scan.php?event_id=$event_id';
        </script>
        ";

        exit();
    } else {

        echo "
        <script>
            alert('Failed to record attendance');
        </script>
        ";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>QR Attendance Form</title>

    <link rel="stylesheet" href="../CSS/module4_style.css">

</head>

<body>

    <div class="container">

        <div class="content">

            <h1>QR Attendance Form</h1>

            <?php if ($event_id > 0) { ?>

                <p>
                    Event ID:
                    <strong><?php echo $event_id; ?></strong>
                </p>

            <?php } ?>

            <div class="form-container">

                <form method="POST" class="modern-form">

                    <input
                        type="hidden"
                        name="event_id"
                        value="<?php echo $event_id; ?>">

                    <div class="form-row">

                        <div class="form-group">

                            <label>Student Name</label>

                            <input
                                type="text"
                                name="student_name"
                                placeholder="Enter Student Name"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Student ID</label>

                            <input
                                type="text"
                                name="student_id"
                                placeholder="Enter Student ID"
                                required>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group">

                            <label>clubs Name</label>

                            <select name="club_name" required>

                                <option value="Programming & Coding clubs">
                                    Programming & Coding clubs
                                </option>

                                <option value="Cyber Security clubs">
                                    Cyber Security clubs
                                </option>

                                <option value="Data Science & AI clubs">
                                    Data Science & AI clubs
                                </option>

                                <option value="Game Development clubs">
                                    Game Development clubs
                                </option>

                                <option value="Cloud Computing clubs">
                                    Cloud Computing clubs
                                </option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Event Name</label>

                            <input
                                type="text"
                                name="event_name"
                                placeholder="Enter Event Name"
                                required>

                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group">

                            <label>Attendance Status</label>

                            <select name="attendance_status">

                                <option value="Present">
                                    Present
                                </option>

                                <option value="Late">
                                    Late
                                </option>

                                <option value="Absent">
                                    Absent
                                </option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Volunteer / Helper</label>

                            <select name="volunteer_status">

                                <option value="No">
                                    No
                                </option>

                                <option value="Yes">
                                    Yes
                                </option>

                            </select>

                        </div>

                    </div>

                    <button
                        type="submit"
                        name="submit_attendance"
                        class="submit-btn">

                        Submit Attendance

                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>