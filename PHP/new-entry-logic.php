<?php

    $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);

    // Adding new researcher
    if(isset($_POST['new__researcher_name'])){
        $name = $_POST['new__researcher_name'];
        $surname = $_POST['new__researcher_surname'];
        $id = $_POST['new__researcher_id'];
        $password = $_POST['new__researcher_password'];
        if(isset($_POST['new__researcher_admin'])){
            $admin = 1;
        } else {
            $admin = 0;
        }
        $query = "INSERT INTO Researcher (Researcher_ID, Password, FirstName, Surname, Admin) VALUES". 
        "('". $id ."', '". $password. "', ' ". $name."', '" . $surname. "'," . $admin.")";
        echo $query;
        $res = odbc_exec($conn, $query);
        header('Location:../Pages/researchers.php');

    } else {
        $researcher_id = $_POST['researcher_id'];
        $subject_id = $_POST['subject_id'];
        $query = "INSERT INTO Assignments (Researcher_ID, Subject_ID) VALUES ('". $researcher_id ."', '". $subject_id. "')";
        $res = odbc_exec($conn, $query);
        header('Location:../Pages/assignments.php');
    }
   
    

    // 