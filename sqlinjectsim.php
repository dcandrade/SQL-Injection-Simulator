<?php
/*
    This was written by Davo Smith ( http://www.davodev.co.uk )
    For what it is worth, it is released under the GPL version 3 or above
*/
$username = false;
$password = false;
$loginok = false;
$realname = false;
$users = array(
               array('username'=>'bob','password'=>'axp149','realname'=>'System Administrator'),
               array('username'=>'dave','password'=>'davepass','realname'=>'David Jones'),
               array('username'=>'sam','password'=>'cheese','realname'=>'Sam Davis')
               );
if (isset($_REQUEST['username'])) { 
    $username = strtolower($_REQUEST['username']);
}
if (isset($_REQUEST['password'])) {
    $password = $_REQUEST['password'];
}
// Remove any 'magic quotes' (if set)
if (get_magic_quotes_gpc()) {
    $username = stripslashes($username);
    $password = stripslashes($password);
}
// Check for 'proper' login
foreach ($users as $user) {
    if ($username == $user['username'] && $password == $user['password']) {
        $loginok = true;
        $realname = $user['realname'];
        $result = "Found 1 result:<br/>username: {$user['username']}, password: {$user['password']}, realname: {$user['realname']}";
    }
}
if (!$loginok) {
    // Split the username by the ' character
    $usersplit = explode('\'', $username);
    if (count($usersplit) == 1) {
        // No 'proper' login and no attempt at SQL injection
        $result = 'No records found';
    } else if (count($usersplit) % 2 == 0) { // Should have odd number of sections, otherwise there is a problem
        $result = 'SQL error - invalid query';
    } else {
        $conn = strtolower(trim($usersplit[1]));
        if ($conn != 'or' && $conn != 'and') {
            $result = 'SQL error - invalid query';
        } else {
            if ($conn == 'or') {
                if (trim($usersplit[3]) == '=') {
                    if (trim($usersplit[2]) == trim($usersplit[4])) {
                        // Correctly inserted: OR 'XX'='XX
                        $loginok = true;
                    }
                }
            }
            if ($loginok) {
                // Display all records - selet the first as the 'logged in' user
                $result = 'Found '.count($users).' results<br/>';
                foreach ($users as $user) {
                    $result .= "username: {$user['username']}, password: {$user['password']}, realname: {$user['realname']}<br/>";
                }
                $realname = $users[0]['realname'];
            } else {
                $result = 'No records found';
            }
        }
    }
}
if ($loginok) {
    echo'<script language = "JavaScript">';
        echo'alert("Senha incorreta");';
        echo'alert("Mentira");';
        echo'alert("Conseguiu...");';
        echo'location.href = "LINK"';
    echo'</script>';
} else {
    echo'<script language = "JavaScript">';
            echo'alert("Senha incorreta");';
            echo'location.href = "LINK"';
    echo'</script>';
}
?>
