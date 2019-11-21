<?php

    $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);

    print_r($_REQUEST);
    if(isset($_REQUEST['researcher_id'])){
        $id = $_REQUEST['researcher_id'];
        $name = $_REQUEST['researcher_name'];
        $surname = $_REQUEST['researcher_surname'];
        $password = $_REQUEST['researcher_password1'];

        if (isset($_REQUEST['researcher_admin'])){
            $admin = 1;
        } else {
            $admin = 0;
        }

        $query = 'UPDATE Researcher SET FirstName=\''.$name.'\', Surname=\''.$surname.'\', '. 
        'Admin ='. $admin;
        if(strlen($_REQUEST['researcher_password1']) != 0) {
            $query .= 
            ', Password=\''.$password.'\'';
        }
        $query .= 
            ' WHERE Researcher_ID=\''.$id.'\'';
        
            echo $query;
        $res = odbc_exec($conn, $query);

        $location = 'Location:../Pages/individual-researcher.php?id='.$id;
        header($location);
    } else {
        $id  = $_REQUEST['subject_id'];
        $name = $_REQUEST['subject_name'];
        $surname = $_REQUEST['subject_surname'];
        $dob = $_REQUEST['subject_dob'];
        // $gender = $_REQUEST['subject_gender'];
        $contact = $_REQUEST['subject_contact'];

        $query = 'UPDATE Subject SET FirstName=\''.$name.'\', LastName=\''.$surname.'\', DOB=\''.$dob.
        '\', Contact=\''.$contact.'\' WHERE Subject_ID=\''.$id.'\'';
        echo $query;
        $res = odbc_exec($conn, $query);
        $location = 'Location:../Pages/individual-subject.php?id='.$id;
        header($location);
    }
    
