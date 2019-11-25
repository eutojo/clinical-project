<?php
    session_start(); 
    $_SESSION['page'] = 'researchers';
?>

<head>
    <!-- Link to stylesheet -->
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/menu-bar.css">
    <link rel="stylesheet" href="../CSS/login.css">

    <script src="../JS/functions.js">
    </script>

    <!-- Document title/ -->
    <title>M2C2 2019</title>
</head>
<body>
    <?php
   
    require_once('../Components/menu-bar.php');

    // User has logged in
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        // Check if also admin
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
            $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
            $query = "SELECT Researcher_ID, FirstName, Surname, Admin FROM Researcher";
            $res = odbc_exec($conn, $query);

            while(odbc_fetch_row($res)){
                // Name of researcher
                echo '<div class="page-container assignments">';
                echo '<a href="./individual-researcher.php?id='.odbc_result($res, 1).'"><h1>[' .odbc_result($res, 1) . '] ' .odbc_result($res, 2) . ' ' .odbc_result($res, 3) .'</h1></a>';
                $researcher_id = odbc_result($res, 1);
            
                // Their subjects
                $query = "SELECT Subject.[Subject_ID], FirstName, LastName FROM Subject INNER JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID] AND Assignments.[Researcher_ID] ='" . $researcher_id."')";
                $res_subj = odbc_exec($conn, $query);

                echo '<div class="table-row">';
                for($i=1;$i<=odbc_num_fields($res_subj);$i++){
                    echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res_subj, $i) ."</h2></div>";
                }
                echo '<div style="width:65.64px"></div>';
                echo "</div>";
                $flag = 0;
                while(odbc_fetch_row($res_subj)){
                    $flag = $flag + 1;
                    if($flag%2 == 1){
                        echo '<div class="table-row table-odd">';
                    } else {
                        echo '<div class="table-row">';
                    }

                    for($i=1;$i<=odbc_num_fields($res_subj);$i++){
                        if($i == 4){
                            echo '<a href=\'./individual-subject.php?id='.odbc_result($res_subj, 1).'\' style=\'width: 33.33%\'><div >' .substr(odbc_result($res_subj, $i),0,-9 ).'</div></a>';
                        } else {
                            echo '<a href=\'./individual-subject.php?id='.odbc_result($res_subj, 1).'\' style=\'width: 33.33%\'><div>' .odbc_result($res_subj, $i) .'</div></a>';
                        }
                    }                   

                    echo '<button type="button" id="remove-button" onClick="removeSubject(\''. $researcher_id.'\', \''. odbc_result($res_subj, 1).'\')">Remove</button>';
                    
                    echo "</div></a>";
                }

                if($flag ==0){
                    echo '<div style="text-align:center; color: #D72226"> No subjects assigned. </div>';
                }
                
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
                echo '</div>';
            }
        } else {
            echo '<script type="text/javascript">'.
                'adminPrompt();'.
                '</script>';
        }

    // Not logged in, redirect
    } else {
        echo '<script type="text/javascript">'.
                'loginPrompt();'.
                '</script>';
    }

    
    
    ?>
</body>