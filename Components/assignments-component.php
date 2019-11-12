<?php
    // if user is admin - show them all
    if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
        $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
        $query = "SELECT Researcher_ID, FirstName, Surname, Admin FROM Researcher";
        $res = odbc_exec($conn, $query);

        while(odbc_fetch_row($res)){
            // Name of researcher
            echo "<h2>[" .odbc_result($res, 1) . "] " .odbc_result($res, 2) . " " .odbc_result($res, 3) ."</h2>";
            $researcher_id = odbc_result($res, 1);
        
            // Their subjects
            $query = "SELECT Subject.[Subject_ID], FirstName, LastName, DOB, Gender, Contact FROM Subject INNER JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID] AND Assignments.[Researcher_ID] ='" . $researcher_id."')";
            $res_subj = odbc_exec($conn, $query);

            echo '<div class="table-row">';
            for($i=1;$i<=odbc_num_fields($res_subj);$i++){
                echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res_subj, $i) ."</h2></div>";
            }
            echo "</div>";
            while(odbc_fetch_row($res_subj)){
                echo "<div class='table-row'>";
                for($i=1;$i<=odbc_num_fields($res_subj);$i++){
                    echo "<div style='width: 33.33%'>" .odbc_result($res_subj, $i) ."</div>";
                }
                echo '<button type="button" id="remove-button" onClick="removeSubject(\''. $researcher_id.'\', \''. odbc_result($res_subj, 1).'\')">Remove</button>';
                echo "</div>";
            }
            // Form to assign new patient to researcher
            
            $query = "SELECT Subject.[Subject_ID], FirstName, LastName FROM Subject LEFT JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID]) WHERE Assignments.[Researcher_ID] <> '".$researcher_id. "' OR Assignments.[Researcher_ID] IS NULL";
            $res_subj = odbc_exec($conn, $query);
            echo '<form class="assignments" method="POST" action="../PHP/new-entry-logic.php">';
            echo '<input type="hidden" name="researcher_id" value="'.$researcher_id .'" />';
            echo '<select name="subject_id">';
            echo '<option value="default">Assign new subject</option>';
            while(odbc_fetch_row($res_subj)){
                echo "<option value=" .odbc_result($res_subj, 1) .">";
                echo "[" .odbc_result($res_subj, 1) . "] " . odbc_result($res_subj, 2) . " " . odbc_result($res_subj, 3);
                echo "</option>";
            }
            echo '</select>';
            echo '<input type="submit" value="Assign">';
            echo '</form>';
        }

        
    }


?>