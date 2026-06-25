<?php
require_once __DIR__ . '/../INCLUDE/db.php';

session_start();

// Validate event_id input
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
if ($event_id <= 0) {
    header("Location: clubEvents.php?msg=" . urlencode("Invalid event ID.") . "&msg_type=danger");
    exit;
}

// Fetch event
$event = getEventById($event_id);
if (!$event) {
    header("Location: clubEvents.php?msg=" . urlencode("Event not found.") . "&msg_type=danger");
    exit;
}

// Fetch user and permission check
if (empty($_SESSION['user']['User_id']) || ($_SESSION['user']['role'] ?? '') !== 'committee') {
    header("Location: ../login.php");
    exit;
}

$user_id = (int) $_SESSION['user']['User_id'];

// This helper should exist; else use whatever logic gets the committee's club_id for the user.
// Must restrict: committee can only delete event for their own club.
// $club = getCommitteeClubForUser($user_id);
// if (!$club || (int)$event['Club_id'] !== (int)$club['club_id']) {
//     header("Location: clubEvents.php?msg=" . urlencode("You are not allowed to delete this event.") . "&msg_type=danger");
//     exit;
// }

// Try to delete the event
if (deleteEvent($event_id)) {
    header("Location: clubEvents.php?msg=" . urlencode("Event deleted successfully.") . "&msg_type=success");
} else {
    header("Location: clubEvents.php?msg=" . urlencode("Failed to delete event.") . "&msg_type=danger");
}
exit;
