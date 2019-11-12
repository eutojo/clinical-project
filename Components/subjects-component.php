<?php
    // if user is admin - show them all
    if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
        $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
        $query = "SELECT * FROM Subject";
        $res = odbc_exec($conn, $query);

        echo '<input type="checkbox" id="researcher_subjects_only" name="researcher_subjects_only" value="yes" onChange="subjectsFilter()"> View own subjects only.';
        echo '<div class="table-row">';
        for($i=1;$i<=odbc_num_fields($res);$i++){
            echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
        }
        echo "</div>";
        while(odbc_fetch_row($res)){
            $admin_id = false;

            $subject_ID = odbc_result($res, 1);
            $admin_query = "SELECT count(*) FROM Assignments WHERE Subject_ID ='" .$subject_ID."' AND Researcher_ID ='".$_SESSION['researcher_ID'] ."'";
            $admin_res = odbc_exec($conn, $admin_query);
            if(odbc_result($admin_res,1) > 0){
                $admin_id = true;
            }

            if($admin_id == true){
                echo "<div class='table-row'>";
            } else {
                echo "<div name='subject-row' class='table-row'>";
            }
            for($i=1;$i<=odbc_num_fields($res);$i++){
                echo "<div style='width: 33.33%'>" .odbc_result($res, $i) ."</div>";
            }
            echo '<button type="button" id="remove-button" onClick="removeSubject(\'NULL\',\''. odbc_result($res, 1).'\')">Remove</button>';
            echo "</div>";
        }
    // Only show researcher's subjects
    } else {
        $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
        $query = "SELECT Subject.[Subject_ID], FirstName, LastName, DOB, Gender, Contact FROM Subject INNER JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID] AND Assignments.[Researcher_ID] ='" . $_SESSION['researcher_ID']."')";
        $res = odbc_exec($conn, $query);

        echo '<div class="table-row">';
        for($i=1;$i<=odbc_num_fields($res);$i++){
            echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
        }

        echo "</div>";
        while(odbc_fetch_row($res)){
            echo "<div class='table-row'>";
            for($i=1;$i<=odbc_num_fields($res);$i++){
                echo "<div style='width: 33.33%'>" .odbc_result($res, $i) ."</div>";
            }
            echo "</div>";
        }
    }


?>