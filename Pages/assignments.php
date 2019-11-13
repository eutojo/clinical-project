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
                        if($i == 4){
                            echo "<div style='width: 33.33%'>" .substr(odbc_result($res_subj, $i),0,-9 )."</div>";
                        } else {
                            echo "<div style='width: 33.33%'>" .odbc_result($res_subj, $i) ."</div>";
                        }
                    }
                    echo '<button type="button" id="remove-button" onClick="removeSubject(\''. $researcher_id.'\', \''. odbc_result($res_subj, 1).'\')">Remove</button>';
                    echo "</div>";
                }
                // Form to assign new patient to researcher

                $query = "SELECT Subject.[Subject_ID], FirstName, LastName FROM Subject LEFT JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID]) WHERE Assignments.[Researcher_ID] <> '".$researcher_id. "' OR Assignments.[Researcher_ID] IS NULL";
                $res_subj = odbc_exec($conn, $query);

                $query = "SELECT Subject_ID FROM Assignments WHERE Researcher_ID ='".$researcher_id."'";
                $res_dup = odbc_exec($conn, $query);
                echo '<form class="assignments" method="POST" action="../PHP/new-entry-logic.php">';
                echo '<input type="hidden" name="researcher_id" value="'.$researcher_id .'" />';
                echo '<select name="subject_id">';
                echo '<option value="default">Assign new subject</option>';
                
                while(odbc_fetch_row($res_subj)){
                    $duplicate = 0;
                    while(odbc_fetch_row($res_dup)){
                        if(odbc_result($res_subj, 1) == odbc_result($res_dup, 1)) {
                            $duplicate = 1;
                        }
                    }
            
                    if($duplicate == 0){
                        echo "<option value=" .odbc_result($res_subj, 1) .">";
                        echo "[" .odbc_result($res_subj, 1) . "] " . odbc_result($res_subj, 2) . " " . odbc_result($res_subj, 3);
                        echo "</option>";
                    }
                }
                echo '</select>';
                echo '<input type="submit" value="Assign">';
                echo '</form>';
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