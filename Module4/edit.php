<?php

include '../INCLUDE/db.php';

$id = (int)($_GET['id'] ?? 0);
$event_id = (int)($_GET['event_id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM attendance WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$attendance = $result->fetch_assoc();

$stmt->close();

$success = '';
$error = '';
if (isset($_POST['update_attendance'])) {

    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $club_name = $_POST['club_name'];
    $event_name = $_POST['event_name'];
    $attendance_date = $_POST['attendance_date'];
    $attendance_time = $_POST['attendance_time'];
    $attendance_status = $_POST['attendance_status'];
    $volunteer_status = $_POST['volunteer_status'];

    $points = calculateAttendancePoints(
        $attendance_status,
        $volunteer_status
    );

    $update = $conn->prepare("
        UPDATE attendance
        SET student_name=?,
            student_id=?,
            club_name=?,
            event_name=?,
            attendance_date=?,
            attendance_time=?,
            attendance_status=?,
            volunteer_status=?,
            points=?
        WHERE id=?
    ");

    $update->bind_param(
        "ssssssssii",
        $student_name,
        $student_id,
        $club_name,
        $event_name,
        $attendance_date,
        $attendance_time,
        $attendance_status,
        $volunteer_status,
        $points,
        $id
    );

    if ($update->execute()) {

        header(
            "Location: manage-event-attendance.php?event_id=$event_id&msg=Attendance+updated+successfully&msg_type=success"
        );
        exit();

    } else {

        $error = "Failed to update attendance.";

    }

    $update->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Attendance</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Your Existing CSS -->
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/adminHeader.css">
    <link rel="stylesheet" href="../CSS/addUser.css">

</head>

<body>

<div class="add-user-container">

    <!-- PAGE TITLE -->
    <h1 class="add-user-title">
        Edit Attendance
    </h1>

    <p class="add-user-subtitle">
        Update student attendance information.
    </p>

    <!-- SUCCESS MESSAGE -->
    <?php if (!empty($success)): ?>

        <div class="alert alert-success mb-4">
            <?= $success ?>
        </div>

    <?php endif; ?>

    <!-- ERROR MESSAGE -->
    <?php if (!empty($error)): ?>

        <div class="alert alert-danger mb-4">
            <?= $error ?>
        </div>

    <?php endif; ?>

    <!-- FORM BOX -->
    <div class="add-user-box">

        <form method="POST">

            <!-- Hidden ID -->
            <input type="hidden"
                name="id"
                value="<?= $attendance['id'] ?>">

            <div class="row">

                <!-- STUDENT NAME -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Student Name
                    </label>

                    <input type="text"
                        name="student_name"
                        class="form-input-custom"
                        value="<?= htmlspecialchars($attendance['student_name']) ?>"
                        required>

                </div>

                <!-- STUDENT ID -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Student ID
                    </label>

                    <input type="text"
                        name="student_id"
                        class="form-input-custom"
                        value="<?= htmlspecialchars($attendance['student_id']) ?>"
                        required>

                </div>

                <!-- CLUB NAME -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Club Name
                    </label>

                    <select name="club_name"
                        class="form-input-custom">

                        <option <?= ($attendance['club_name'] == 'Programming & Coding Club') ? 'selected' : '' ?>>
                            Programming & Coding Club
                        </option>

                        <option <?= ($attendance['club_name'] == 'Cyber Security Club') ? 'selected' : '' ?>>
                            Cyber Security Club
                        </option>

                        <option <?= ($attendance['club_name'] == 'Data Science & AI Club') ? 'selected' : '' ?>>
                            Data Science & AI Club
                        </option>

                        <option <?= ($attendance['club_name'] == 'Game Development Club') ? 'selected' : '' ?>>
                            Game Development Club
                        </option>

                        <option <?= ($attendance['club_name'] == 'Cloud Computing Club') ? 'selected' : '' ?>>
                            Cloud Computing Club
                        </option>

                    </select>

                </div>

                <!-- EVENT NAME -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Event Name
                    </label>

                    <input type="text"
                        name="event_name"
                        class="form-input-custom"
                        value="<?= htmlspecialchars($attendance['event_name']) ?>"
                        required>

                </div>

                <!-- ATTENDANCE DATE -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Attendance Date
                    </label>

                    <input type="date"
                        name="attendance_date"
                        class="form-input-custom"
                        value="<?= $attendance['attendance_date'] ?>"
                        required>

                </div>

                <!-- ATTENDANCE TIME -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Attendance Time
                    </label>

                    <input type="time"
                        name="attendance_time"
                        class="form-input-custom"
                        value="<?= $attendance['attendance_time'] ?>"
                        required>

                </div>

                <!-- ATTENDANCE STATUS -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Attendance Status
                    </label>

                    <select name="attendance_status"
                        class="form-input-custom">

                        <option value="Present"
                            <?= ($attendance['attendance_status'] == 'Present') ? 'selected' : '' ?>>
                            Present
                        </option>

                        <option value="Late"
                            <?= ($attendance['attendance_status'] == 'Late') ? 'selected' : '' ?>>
                            Late
                        </option>

                        <option value="Absent"
                            <?= ($attendance['attendance_status'] == 'Absent') ? 'selected' : '' ?>>
                            Absent
                        </option>

                    </select>

                </div>

                <!-- VOLUNTEER -->
                <div class="col-lg-6 mb-4">

                    <label class="form-label-custom">
                        Volunteer / Helper
                    </label>

                    <select name="volunteer_status"
                        class="form-input-custom">

                        <option value="Yes"
                            <?= ($attendance['volunteer_status'] == 'Yes') ? 'selected' : '' ?>>
                            Yes
                        </option>

                        <option value="No"
                            <?= ($attendance['volunteer_status'] == 'No') ? 'selected' : '' ?>>
                            No
                        </option>

                    </select>

                </div>

            </div>

            <!-- BUTTONS -->
            <div class="submit-flex">

                <button type="submit"
                    name="update_attendance"
                    class="save-btn">

                    <i class="bi bi-check-circle-fill"></i>
                    Update Attendance

                </button>

                <a href="manage-event-attendance.php?event_id=<?= $_GET['event_id'] ?>"
                    class="cancel-btn">

                    <i class="bi bi-x-circle"></i>
                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>