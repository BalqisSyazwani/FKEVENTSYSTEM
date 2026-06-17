<?php
require_once __DIR__ . '/../INCLUDE/db.php';

if (empty($_SESSION['user']['User_id'])) {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'] ?? '';
$userId = (int) $user['User_id'];

if (!in_array($role, ['committee', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$eventId = (int) ($_GET['event_id'] ?? $_POST['event_id'] ?? 0);
$event = getEventById($eventId);

if (!$event) {
    header('Location: clubEvents.php?msg=' . urlencode('Event not found.') . '&msg_type=danger');
    exit;
}

// Committee members can only manage the waiting list for their own club's events.
if ($role === 'committee') {
    $clubId = getClubIdByCommittee($userId);
    if ($clubId === null || (int) $clubId !== (int) $event['Club_id']) {
        header('Location: clubEvents.php');
        exit;
    }
}

// Manually promote a chosen waiting list entry (e.g. staff override).
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['promote_waiting_id'])) {
    $waitingId = (int) $_POST['promote_waiting_id'];
    $result = convertWaitingToRegistered($waitingId);

    header(
        'Location: waitingList.php?event_id=' . $eventId
        . '&msg=' . urlencode($result['message'])
        . '&msg_type=' . ($result['success'] ? 'success' : 'danger')
    );
    exit;
}

$flashMessage = $_GET['msg'] ?? null;
$flashType = in_array($_GET['msg_type'] ?? '', ['success', 'danger'], true)
    ? $_GET['msg_type']
    : 'success';

$waitingList = getWaitingListForEvent($eventId);
$currentRegistrations = countRegistrations($eventId);
$capacity = (int) $event['Student_Capacity'];
$hasOpenSlot = $currentRegistrations < $capacity;

$navBase = '../';
$activeNav = 'events';
$useCommitteeHeader = $role === 'committee';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/adminHeader.css">
    <link rel="stylesheet" href="../CSS/addUser.css">
    <link rel="stylesheet" href="../CSS/UserManagement.css">
    <link rel="stylesheet" href="../CSS/clubEvents.css">
</head>

<body>

    <?php if ($useCommitteeHeader): ?>
        <?php include __DIR__ . '/../INCLUDE/CommitteeHeader.php'; ?>
    <?php else: ?>
        <?php include __DIR__ . '/../INCLUDE/AdminHeader.php'; ?>
    <?php endif; ?>

    <div class="add-user-container">

        <div class="top-flex">
            <div>
                <h1 class="add-user-title mb-2">Waiting List</h1>
                <p class="add-user-subtitle mb-0">
                    <?= htmlspecialchars($event['Event_Name']) ?>
                    &middot;
                    <?= $currentRegistrations ?> / <?= $capacity ?> registered
                    <?php if ($hasOpenSlot): ?>
                        <span class="badge bg-success ms-2">Slot open</span>
                    <?php else: ?>
                        <span class="badge bg-secondary ms-2">Full</span>
                    <?php endif; ?>
                </p>
            </div>
            <a href="clubEvents.php" class="cancel-btn text-decoration-none d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                Back to Events
            </a>
        </div>

        <?php if ($flashMessage): ?>
            <div class="alert alert-<?= htmlspecialchars($flashType) ?> mt-3">
                <?= htmlspecialchars($flashMessage) ?>
            </div>
        <?php endif; ?>

        <div class="table-box mt-4">
            <h5 class="text-white mb-3">Students waiting, oldest first</h5>
            <table class="table custom-table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Joined waiting list</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($waitingList) > 0): ?>
                        <?php foreach ($waitingList as $i => $entry): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($entry['FullName'] ?? '') ?></td>
                                <td><?= htmlspecialchars($entry['Student_id'] ?? '') ?></td>
                                <td><?= date('d M Y, g:i A', strtotime($entry['Waiting_Date'])) ?></td>
                                <td class="text-center">
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="event_id" value="<?= $eventId ?>">
                                        <input type="hidden" name="promote_waiting_id" value="<?= (int) $entry['WaitList_Id'] ?>">
                                        <button type="submit"
                                            class="save-btn"
                                            <?= $hasOpenSlot ? '' : 'disabled title="Event is full"' ?>>
                                            <i class="bi bi-arrow-up-circle"></i>
                                            Promote
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                No one is on the waiting list for this event.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>
