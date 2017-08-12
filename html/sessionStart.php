<?php
include_once("config.php");
include_once("functions.php");

// Put a blank line in the log at the top of each page.
debugOut('','',false, false, false);

// If session_start created a new session, log it.
if (session_start()) {
  $_SESSION['ipAddress'] = ipAddress();

  debugOut('$_SESSION[\'ipAddress\']', $_SESSION['ipAddress']);
  
  // We won't have user ID at this point, so log the session without it. On login, update
  //  the session record.
  $connection = getDBConnection();
  $sql = 'SELECT addOrUpdateSession(\'' . mysqli_real_escape_string(session_id()) .
    '\', \'' . mysqli_real_escape_string($_SESSION['ipAddress']) .
    '\', \'' . mysqli_real_escape_string(session_encode()) . '\')';
  
  $result = mysqli_query($connection, $sql) or die("<br />Error: " . $sql . '<br />' . mysqli_error($connection));
  mysqli_free_result($result);
  
  
  // Store these in the session, but allow refresh once per hour just to be safe.
  $sessionStartTime = new DateTime($_SESSION['sessionTimestamp']);
  $timeDiff = $sessionStartTime->diff(new DateTime());
  $sessionAge = ($timeDiff->days * 1440) + ($timeDiff->h * 60) + ($timeDiff->i);
  debugOut('sessionAge', $sessionAge . ' minutes and ' . ($timeDiff->s) . 'seconds');
  
  if (
    !isset($_SESSION["tagCategoryTagID"]) ||
    ($_SESSION["tagCategoryTagID"] == '') ||
    ($sessionAge > 30)
  ) {
    $sql = 'CALL procServerConfig()';
    $row = getOneStoredProcRow($connection, $sql);
    if (!empty($row)) {
      foreach ($row as $key => $val) {
        $_SESSION[$key] = $val;
      }
    }
  }
};

