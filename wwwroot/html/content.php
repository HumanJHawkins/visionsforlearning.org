<?php
include 'sessionStart.php';
htmlStart('Stories and Lessons');
?>

<div class="container">
    <?php include 'divButtonGroupMain.php'; ?>
    <br/>
    <?php include 'divBanner.php'; ?>
    <br/>
    <?php
    if ((isset($_SESSION['isContentEditor']) && $_SESSION['isContentEditor']) ||
        (isset($_SESSION['isSuperuser']) && $_SESSION['isSuperuser'])
    ) {
        echo '<a href="contentEdit.php" class="btn btn-primary">&nbsp;&nbsp;&nbsp;New...&nbsp;&nbsp;&nbsp;</a><br />';
    }
    ?>
    <br/>
    <?php include 'divContentGrid.php'; ?>
</div>
</body>
</html>

