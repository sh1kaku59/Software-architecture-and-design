<?php
// Function to display and clear session message
function displaySessionMessage()
{
    if (isset($_SESSION['sessionMessage'])) {
        $messageText = $_SESSION['sessionMessage'];

        echo '<script>var jsMessageText = "' . $messageText . '";</script>';

        // Clear the session message
        unset($_SESSION['sessionMessage']);
    }
}
// Function to set a session message
function setSessionMessage($messageText, $messageType = 'info')
{
    $_SESSION['sessionMessage'] = $messageText;
}
?>