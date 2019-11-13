<?php
    $researcher_id = $_GET['id'];
    $query = "SELECT Subject.[Subject_ID], FirstName, LastName FROM Subject LEFT JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID])".
    "WHERE Assignments.[Researcher_ID] <> '".$researcher_id. "' OR Assignments.[Researcher_ID] IS NULL";
    $res_subj = odbc_exec($conn, $query);

    $query = "SELECT Subject_ID FROM Assignments WHERE Researcher_ID ='".$researcher_id."'";
    $res = odbc_exec($conn, $query);

    echo '<form class="assignments" method="POST" action="../PHP/new-entry-logic.php">';
    echo '<input type="hidden" name="researcher_id" value="'.$researcher_id .'" />';
    echo '<select name="subject_id">';
    echo '<option value="default">Assign new subject</option>';


    while(odbc_fetch_row($res_subj)){
        $duplicate = 0;
        while(odbc_fetch_row($res)){
            if(odbc_result($res_subj, 1) == odbc_result($res, 1)) {
                $duplicate = 1;
            }
        }

        if($duplicate == 0){
            echo "<option value=" .odbc_result($res_subj, 1) .">";
            echo "[" .odbc_result($res_subj, 1) . "] " . odbc_result($res_subj, 2) . " " . odbc_result($res_subj, 3);
            echo "</option>";
        }
    }

    // echo '</select>';
    echo '<input type="submit" value="Assign">';
    echo '</form>';