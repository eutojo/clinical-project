<?php

    $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
    // Adding new researcher
    if(isset($_REQUEST['new__researcher_name'])){
        $name = str_replace("'", "''", $_REQUEST['new__researcher_name']);
        $surname = str_replace("'", "''", $_REQUEST['new__researcher_surname']);
        $id = $_REQUEST['new__researcher_id'];
        $password = $_REQUEST['new__researcher_password1'];
        if(isset($_REQUEST['new__researcher_admin'])){
            $admin = 1;
        } else {
            $admin = 0;
        }
        $query = "INSERT INTO Researcher (Researcher_ID, Password, FirstName, Surname, Admin) VALUES ". 
        "('". $id ."', '". $password. "',  '". $name."', '" . $surname. "', " . $admin. ")";
        $res = odbc_exec($conn, $query);
        header('Location:../Pages/researchers.php');
    
    // Adding new assignment
    } else if(isset($_REQUEST['researcher_id'])){
        $researcher_id = $_REQUEST['researcher_id'];
        $subject_id = $_REQUEST['subject_id'];
        $query = "INSERT INTO Assignments (Researcher_ID, Subject_ID) VALUES ('". $researcher_id ."', '". $subject_id. "')";
        $res = odbc_exec($conn, $query);

        if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'individual'){
            $path = 'Location: ../Pages/individual-researcher.php?id=' . $researcher_id ;
        } else {
            $path = 'Location: ../Pages/assignments.php';
        }

        header($path);

    // Adding new subject
    } else {

        $id = $_REQUEST['new__subject_id'];
        $name = str_replace("'", "''", $_REQUEST['new__subject_name']);
        $surname = str_replace("'", "''", $_REQUEST['new__subject_surname']);
        $dob = $_REQUEST['new__subject_dob'];
        $gender = $_REQUEST['new__subject_gender'];

        if(isset($_REQUEST['new__subject_contact'])){
            $contact = $_REQUEST['new__subject_contact'];
            $query = "INSERT INTO Subject (Subject_ID, FirstName, LastName, DOB, Gender, Contact) VALUES ".
            " ('". $id ."', '". $name. "', '". $surname. "', '". $dob. "', '". $gender. "', '". $contact. "')";
        } else {
            $query = "INSERT INTO Subject (Subject_ID, FirstName, LastName, DOB, Gender) VALUES ".
            " ('". $id ."', '". $name. "', '". $surname. "', '". $dob. "', '". $gender. "')";
        }
        $res = odbc_exec($conn, $query);
        header('Location:../Pages/subjects.php');

    }
   
    

    // 