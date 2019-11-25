<?php
    $researcher_id = $_GET['id'];
    $query = "SELECT Subject.[Subject_ID], FirstName, LastName FROM Subject LEFT JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID])".
    "WHERE Assignments.[Researcher_ID] <> '".$researcher_id. "' OR Assignments.[Researcher_ID] IS NULL";
    $res_subj = odbc_exec($conn, $query);

    $query = "SELECT Subject_ID FROM Assignments WHERE Researcher_ID ='".$researcher_id."'";
    $res = odbc_exec($conn, $query);

    // Form to assign new patient to researcher
    $in_dropdown = array();
    $assigned_to_researcher = array();

    // Get all unassigned subjects and subjects who aren't assigned to the reseacher
    $query = "SELECT Subject.[Subject_ID], FirstName, LastName FROM Subject LEFT JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID]) WHERE Assignments.[Researcher_ID] <> '".$researcher_id. "' OR Assignments.[Researcher_ID] IS NULL";
    $res_subj = odbc_exec($conn, $query);

    // Get all subjects assigned to the researcher
    $query = "SELECT Subject_ID FROM Assignments WHERE Researcher_ID ='".$researcher_id."'";
    $res_dup = odbc_exec($conn, $query);
    echo '<form style="margin-top:1em" class="assignments" method="POST" action="../PHP/new-entry-logic.php">';
    echo '<input type="hidden" name="researcher_id" value="'.$researcher_id .'" />';
    echo '<input type="hidden" name="page" value="individual" />';
    echo '<select name="subject_id">';
    echo '<option value="default">Assign new subject</option>';

    while(odbc_fetch_row($res_dup)){
        array_push($assigned_to_researcher, odbc_result($res_dup, 1));
    }
    
    while(odbc_fetch_row($res_subj)){                 

        // If not assigned to researcher and hasn't been displayed yet
        if((!in_array(odbc_result($res_subj, 1), $assigned_to_researcher)) && (!in_array(odbc_result($res_subj, 1), $in_dropdown))){
            echo "<option value=" .odbc_result($res_subj, 1) .">";
            echo "[" .odbc_result($res_subj, 1) . "] " . odbc_result($res_subj, 2) . " " . odbc_result($res_subj, 3);
            echo "</option>";

            // Add to display array
            array_push($in_dropdown, odbc_result($res_subj, 1));

        }
    }
    echo '</select>';
    echo '<input type="submit" value="Assign">';
    echo '</form>';