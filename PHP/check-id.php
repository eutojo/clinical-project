<?php

    $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);

    if(isset($_REQUEST['researcher_flag']) && $_REQUEST['researcher_flag']==1){
        $id = $_REQUEST['input_ID'];

        $query = 'SELECT * FROM Researcher WHERE Researcher_ID=\''.$id.'\'';
        $res = odbc_exec($conn, $query);
        $flag = 0;
        if(odbc_fetch_row($res)){
            $flag = 1;
        }
        echo $flag;
        

    } else {
        $id  = $_REQUEST['input_ID'];

        $query = 'SELECT * FROM Subject WHERE Subject_ID=\''.$id.'\'';
        $res = odbc_exec($conn, $query);
        
        $flag = 0;
        if(odbc_fetch_row($res)){
            $flag = 1;
        }
        echo $flag;

    }
    
