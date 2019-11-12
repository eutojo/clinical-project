<?php

    $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);

    // delete assignment
    if($_POST['researcher_id'] != "" && $_POST['subject_id'] != ""){
        $researcher_id = $_POST['researcher_id'];
        $subject_id = $_POST['subject_id'];

        $query = "SELECT ID FROM Assignments WHERE (Researcher_ID = '".$researcher_id."' AND Subject_ID = '".$subject_id."')";
        $res = odbc_exec($conn, $query);
        $index = odbc_result($res, 1);

        $query = "DELETE FROM Assignments WHERE ID = ". $index;

    } else if($_POST['researcher_id'] != "") {
        $researcher_id = $_POST['researcher_id'];
        $query = "DELETE FROM Researcher WHERE (Researcher_ID = '". $researcher_id."')";
    } else {
        $subject_id = $_POST['subject_id'];
        $query = "DELETE FROM Subject WHERE Subject_ID = '". $subject_id."'";
    }
    
   $res = odbc_exec($conn, $query);

