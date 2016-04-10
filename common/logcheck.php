<?php
if (!(isset($_SESSION['valid']) && $_SESSION['valid'])) {
// Back to login for you
 header('Location: index.php');
 exit();
}
?>