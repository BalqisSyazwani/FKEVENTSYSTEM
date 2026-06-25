<?php
include '../INCLUDE/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Top Student Ranking</title>

    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/module4-dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../CSS/adminHeader.css">

</head>

<body>

    <?php include '../INCLUDE/AdminHeader.php'; ?>

    <div class="container">

        <div class="content">

            <h1>Top Student Ranking</h1>

            <div class="table-wrapper">

                <table>

                    <tr>
                        <th>Rank</th>
                        <th>Student ID</th>
                        <th>Total Points</th>
                        <th>Recognition</th>
                    </tr>

                    <?php

                    $query = "
                    SELECT student_id,
                           SUM(points) AS total_points
                    FROM attendance
                    GROUP BY student_id
                    ORDER BY total_points DESC
                ";

                    $result = mysqli_query($conn, $query);

                    $rank = 1;

                    while ($row = mysqli_fetch_assoc($result)) {

                        $points = $row['total_points'];

                        // Recognition based on Table B
                        if ($points < 20) {
                            $recognition = "Warning";
                        } elseif ($points >= 20 && $points <= 49) {
                            $recognition = "Participation Certificate";
                        } elseif ($points >= 50 && $points <= 79) {
                            $recognition = "Active Student Award";
                        } else {
                            $recognition = "Outstanding Participant";
                        }
                    ?>

                        <tr>

                            <td>
                                <?php echo $rank++; ?>
                            </td>

                            <td>
                                <?php echo $row['student_id']; ?>
                            </td>

                            <td>
                                <?php echo $row['total_points']; ?>
                            </td>

                            <td>
                                <?php echo $recognition; ?>
                            </td>

                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>

    </div>

</body>

</html>