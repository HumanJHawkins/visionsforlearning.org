<?php
include 'sessionStart.php';
if (!isset($pdo)) {
    $pdo = getDBPDO();
}

$EMPTY_TINYMCE_FIELD_LENGTH = 68;

// View $_POST["pageContentID"]
consolidatePageContentID();

// Get Content to display
$sql = 'CALL procViewContent(?, ?)';
if (isset($_SESSION['userID']) && ($_SESSION['userID'] > 0)) {
    $sqlParamsArray = [$_POST["pageContentID"], $_SESSION['userID']];
} else {
    $sqlParamsArray = [$_POST["pageContentID"], 0];
}
$row = getOnePDORow($pdo, $sql, $sqlParamsArray);
outputArray($row);
if (!empty($row)) {
    $contentTitle = trim($row['contentTitle']);
    $contentDescription = trim($row['contentDescription']);
    $contentExcerpt = trim($row['contentExcerpt']);
    $contentSummary = trim($row['contentSummary']);
    $contentAvatarID = $row['contentAvatarID'];
    $canEdit = $row['canEdit'];
} else {
    $contentTitle = 'Title';
    $contentDescription = 'Description.';
    $contentExcerpt = 'Excerpt';
    $contentSummary = 'URL';
    $contentAvatarID = 'Select graphic file (avatar for content) with Browse Button.';
    // $canEdit = true;
}

htmlStart('Content View');

echo '<div class="container">';
include 'divButtonGroupMain.php';
echo '<br/>';
// include 'divBanner.php';
// echo '<br/>';
debugSectionOut("Edit Content");
debugOut('$contentTitle', $contentTitle);
debugOut('$contentDescription', $contentDescription);
debugOut('$contentExcerpt', $contentExcerpt);
debugOut('$contentSummary', $contentSummary);
debugOut('$contentAvatarID', $contentAvatarID);
debugOut('$sql', $sql);

if ($contentAvatarID > 0) {
    echo '<p><img class="img-thumbnail left avatar" src="./userImage/' . $contentAvatarID . '" /></p>';
}

echo '<h1>' . $contentTitle . '</h1>';

// echo '<br/><br/>';

if (strlen($contentSummary) > $EMPTY_TINYMCE_FIELD_LENGTH) {
    echo '<h3>' . $GLOBALS['CONTENT_SUMMARY_LABEL'] . '</h3>';
    echo $contentSummary;
    echo '<div style="clear: both;"></div>';
}
// echo '<hr />';

if (strlen($contentExcerpt) > $EMPTY_TINYMCE_FIELD_LENGTH) {
    echo '<h3>' . $GLOBALS['CONTENT_EXCERPT_LABEL'] . '</h3>';
    echo $contentExcerpt;
    echo '<hr/>';
}

if (strlen($contentDescription) > $EMPTY_TINYMCE_FIELD_LENGTH) {
    echo '<h3>' . $GLOBALS['CONTENT_DESCRIPTION_LABEL'] . '</h3>';
    echo $contentDescription;
    echo '<hr/>';
}

echo '<h3>Available Files</h3>';

include 'divContentFiles.php';

echo '<hr/>';

echo '<h3>Metadata</h3>';
if (isset($_POST["pageContentID"]) && ($_POST["pageContentID"] > 0)) {
    include 'divContentTags.php';
}

echo '<br /><hr /><input type="button" class="btn btn-default" name="back" value="    Back    " onClick="window.location=\'./content.php\';" />&nbsp;';
if ($canEdit) {
    echo '<input type="button" class="btn btn-default" name="edit" value="    Edit    " onClick="window.location=\'./contentEdit.php?action=edit&pageContentID=' .
        $_POST["pageContentID"] . '\';" />';
}

?>

</div>
</body>
</html>

































