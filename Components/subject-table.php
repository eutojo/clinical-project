<?php 

    if($_SESSION['page'] == 'individual-researcher') {
        $researcher_id = $_GET['id'];
            
        $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
        $query = "SELECT Subject.[Subject_ID], FirstName, LastName, DOB, Gender, Contact FROM Subject INNER JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID] AND Assignments.[Researcher_ID] ='" . $researcher_id."')";
        $res = odbc_exec($conn, $query);

        echo '<div class="table-row">';
        for($i=1;$i<=odbc_num_fields($res);$i++){
            echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
        }

        echo "</div>";
        while(odbc_fetch_row($res)){
            echo "<div class='table-row'>";
            for($i=1;$i<=odbc_num_fields($res);$i++){
                if($i == 4){
                    echo "<div style='width: 33.33%'>" .substr(odbc_result($res_subj, $i),0,-9 )."</div>";
                } else {
                    echo "<div style='width: 33.33%'>" .odbc_result($res_subj, $i) ."</div>";
                }
            }
            if($_SESSION['page'] == 'individual-researcher'){
                echo '<button type="button" id="remove-button" onClick="removeSubject(\''.$researcher_id.'\',\''. odbc_result($res, 1).'\')">Remove</button>';
            }
            echo "</div>";
        }
    }

        