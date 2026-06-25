<?php
if (!isset($navBase)) {
    $navBase = '';
}
?>
<nav class="admin-navbar">

    </pre>
    <!-- ================= LEFT ================= -->
    <div class="admin-nav-left">

        <a href="../Module1/student.php">

            <i class="bi bi-code-slash"></i>

        </a>

    </div>

    <!-- ================= CENTER ================= -->
    <div class="admin-nav-center">

<<<<<<< HEAD
        <a href="../Module2/viewClubListStudent.php"
=======
        <a href="/FKEVENTSYSTEM/Module2/viewClubListStudent.php"
>>>>>>> fc43f699ebb163efeeedf23ed8f58b951eee374a
            class="active-admin-nav">

            <i class="bi bi-suit-club-fill"></i>
            club List

        </a>

<<<<<<< HEAD
        <a href="../Module3/eventList.php">
=======
        <a href="/FKEVENTSYSTEM/Module3/eventList.php">
>>>>>>> fc43f699ebb163efeeedf23ed8f58b951eee374a

            <i class="bi bi-ticket-perforated"></i>
            Events List
        </a>

    </div>

    <!-- ================= RIGHT ================= -->
    <div class="admin-nav-right">

        <!-- NOTIFICATION -->
        <div class="notification-icon">

            <i class="bi bi-bell"></i>

        </div>

        <!-- PROFILE -->
        <div class="admin-profile-dropdown">

            <!-- BUTTON -->
            <div class="admin-profile-btn"
                onclick="toggleAdminDropdown()">

                <!-- PROFILE IMAGE -->
                <div class="admin-profile-circle">

                    <i class="bi bi-person-fill"></i>

                </div>

                <!-- ARROW -->
                <i class="bi bi-chevron-down admin-arrow"
                    id="adminArrow"></i>

            </div>

            <!-- ================= DROPDOWN ================= -->
            <div class="admin-dropdown-menu"
                id="adminDropdownMenu">

                <!-- TOP -->
                <div class="admin-dropdown-top">

                    <div class="admin-dropdown-profile">

                        <i class="bi bi-person-fill"></i>

                    </div>

                    <div>
                        <?php

                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }

                        ?>

                        <h4>

                            <?php

                            if (isset($_SESSION['user']['FullName'])) {
                                echo htmlspecialchars(
                                    $_SESSION['user']['FullName']
                                );
                            } else {
                                echo 'Student';
                            }

                            ?>

                        </h4>
                        <p>
                            Student Member
                        </p>
                    </div>


                </div>
<<<<<<< HEAD
                                
                <!-- PROFILE -->
                <a href="../Module1/profile.php">
                    <i class="bi bi-person-circle"></i>
                    View Profile
                </a>

                <a href="../Module1/login.php"
                    class="admin-logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    Sign Out
                </a>
=======
                <!-- TODO: Add logout button -->

                <!-- PROFILE -->
                <a href="/FKEVENTSYSTEM/Module1/profile.php">

                    <a href="/FKEVENTSYSTEM/Module1/profile.php">
                        <i class="bi bi-person-circle"></i>
                        View Profile
                    </a>

                    <!-- TODO: Add logout button -->
                    <a href="/FKEVENTSYSTEM/Module1/login.php"
                        class="admin-logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        Sign Out
                    </a>
>>>>>>> fc43f699ebb163efeeedf23ed8f58b951eee374a

            </div>

        </div>

    </div>

</nav>

<!-- ================= SCRIPT ================= -->

<script>
    function toggleAdminDropdown() {

        let dropdown =
            document.getElementById(
                "adminDropdownMenu"
            );

        let arrow =
            document.getElementById(
                "adminArrow"
            );

        dropdown.classList.toggle(
            "show-admin-dropdown"
        );

        arrow.classList.toggle(
            "rotate-admin-arrow"
        );
    }

    /* ================= CLOSE OUTSIDE ================= */

    window.addEventListener(
        "click",
        function(e) {

            let container =
                document.querySelector(
                    ".admin-profile-dropdown"
                );

            if (!container.contains(e.target)) {

                document
                    .getElementById(
                        "adminDropdownMenu"
                    )
                    .classList.remove(
                        "show-admin-dropdown"
                    );

                document
                    .getElementById(
                        "adminArrow"
                    )
                    .classList.remove(
                        "rotate-admin-arrow"
                    );
            }
        }
    );
</script>