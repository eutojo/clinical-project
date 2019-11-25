<?php
    session_start(); 
    $_SESSION['page'] = 'subjects';
?>

<head>
    <!-- Link to stylesheet -->
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/menu-bar.css">

    <script src="../JS/functions.js">
    </script>

    <!-- Document title/ -->
    <title>M2C2 2019</title>
</head>
<body>
    <?php
   
    require_once('../Components/menu-bar.php');

    ?>


    <?php
    // User has logged in
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        echo '<div class="page-container">';
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
            $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
            $query = "SELECT * FROM Subject";
            $res = odbc_exec($conn, $query);
            echo '<div class="page-container" style="margin-top:1em">';
            echo '<input type="checkbox" id="researcher_subjects_only" name="researcher_subjects_only" value="yes" onChange="subjectsFilter()"> View own subjects only.';
            echo '<div class="table-row">';
            for($i=1;$i<=odbc_num_fields($res);$i++){
                echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
            }
            echo '<div style="width:85.64px"></div>';
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
                    echo '<a href="./individual-subject.php?id='.odbc_result($res, 1).'" ><div class="table-row">';
                } else {
                    echo '<a href="./individual-subject.php?id='.odbc_result($res, 1).'" ><div name="subject-row" class="table-row">';
                }
                for($i=1;$i<=odbc_num_fields($res);$i++){
                    if($i == 4){
                        $date = new DateTime(odbc_result($res, $i));
                        $dob = str_replace('-','/',$date->format('d-m-Y'));
                        echo "<div style='width: 33.33%'>" .$dob."</div>";
                    } else {
                        echo "<div style='width: 33.33%'>" .odbc_result($res, $i) ."</div>";
                    }
                }
                echo '<button type="button" id="remove-button" onClick="removeSubject(\'NULL\',\''. odbc_result($res, 1).'\')">Remove</button>';
                echo "</div></a>";
            }
            
        $new_subject_form = ''.
        '<div>'.
            '<div class="table-row" style="margin-left:-0.5em">'. 
                '<span style="width: 33.33%" id="validation__subject_id"></span>'.
                '<span style="width: 33.33%" id="validation__subject_name"></span>'.
                '<span style="width: 33.33%" id="validation__subject_surname"></span>'.
                '<span style="width: 33.33%" id="validation__subject_dob"></span>'.
                '<span style="width: 33.33%"></span>'.
                '<span style="width: 33.33%" id="validation__subject_contact"></span>'.
                '<span style="width: 50px"></span>'.
            '</div>'.
        '</div>'.
        '<div>'.
            '<form id="subjects-form" class="table-row" method="POST" action="../PHP/new-entry-logic.php" onSubmit="return validInfo(\'subject\')">'.
                '<div style="width: 33.33%" >' .
                    // ID
                    '<input type="text" id="new__subject_id" name="new__subject_id" maxlength="4" onChange="validateID(\'subject\')">'.
                '</div>' .
                '<div style="width: 33.33%" >' .
                    // First Name
                    '<input type="text" id="new__subject_name" name="new__subject_name" maxlength="15" onChange="validateName(\'first\',\'subject\')">'.
                '</div>' .
                '<div style="width: 33.33%" >' .
                    // Last Name
                    '<input type="text" id="new__subject_surname" name="new__subject_surname" maxlength="15" onChange="validateName(\'last\',\'subject\')">'.
                '</div>' .
                '<div style="width: 33.33%" >' .
                    // DOB
                    '<input type="text" id="new__subject_dob" name="new__subject_dob" onChange="validateBirthday(\'subject\')">'.
                '</div>' .
                '<div style="width: 33.33%" >' .
                    // Gender
                    '<select id="new__subject_gender" name="new__subject_gender">'.
                        '<option value="Female">Female</option>'.
                        '<option value="Male">Male</option>'.
                    '</select>' .
                '</div>' .
                '<div style="width: 33.33%" >' .
                    // Contact
                    '<input type="text" id="new__subject_contact" name="new__subject_contact" maxlength="10" onChange="validateContact(\'subject\')">'.
                '</div>' .
                '<div>' .
                    // Submit button
                    '<input type="submit" value="Submit">'.
                '</div>' .
            '</form>'.
        '</div></div>';

        echo $new_subject_form;

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