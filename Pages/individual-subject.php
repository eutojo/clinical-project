<?php
    session_start(); 
    $_SESSION['page'] = 'researchers';
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
        // Check if page is correct
        if(isset($_GET['id'])){
            $subject_id = $_GET['id'];
            $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);

            // Check if Subject exists
            $query = "SELECT FirstName, LastName, DOB, Gender, Contact FROM Subject WHERE Subject_ID = '". $subject_id ."'";
            $res = odbc_exec($conn, $query);

            $count = 0;
            while(odbc_fetch_row($res)){
                if($count > 0){
                    break;
                }
                $count++;
            }

            // Subject exists
            if($count > 0) {
                // Obtain subject's details
                $name = odbc_result($res, 1);
                $surname = odbc_result($res, 2);
                $dob = odbc_result($res, 3);
                $gender = odbc_result($res, 4);
                $contact = odbc_result($res, 5);

                // Check if Subject assigned to logged in Researcher OR researcher is admin
                $query = "SELECT count(*) FROM Assignments WHERE Subject_ID = '". $subject_id .
                "' AND Researcher_ID = '". $_SESSION['researcher_ID']."'";
                $res = odbc_exec($conn, $query);
                // Subject is assigned
                if(odbc_result($res, 1) > 0 || $_SESSION['admin'] == 1) {
                    $subject_page = ''.
                    '<h1>['. $subject_id.'] '.$surname.', '.$name.'</h1>'.
                    '<form name="form__change_subject" method="post" onSubmit="return updateSubject()" action="../PHP/modify-entry-logic.php">'. 
                        '<div style="width: 33.33%" >' .
                        // ID
                            '<label>ID</label>'.
                            '<input readonly type="text" id="subject_id" name="subject_id" value="'.$subject_id.'">'.
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            // First Name
                            '<label>First Name</label>'.
                            '<input readonly type="text" id="subject_name" name="subject_name" value="'.$name.'">'.
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            // Last Name
                            '<label>Last Name</label>'.
                            '<input readonly type="text" id="subject_surname" name="subject_surname" value="'.$surname.'">'.
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            // DOB
                            '<label>Date of Birth</label>'.
                            '<input readonly type="text" id="subject_dob" name="subject_dob" value="'.$dob.'">'.
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            // Gender
                            '<label>Gender</label>'.
                            '<select disabled id="subject_gender" name="subject_gender" selected="'.$gender.'">'.
                                '<option value="Female">Female</option>'.
                                '<option value="Male">Male</option>'.
                            '</select>' .
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            // Contact
                            '<label>Contact</label>'.
                            '<input readonly type="text" id="subject_contact" name="subject_contact" value="'.$contact.'">'.
                        '</div>' .
                        '<div>' .
                            // Submit button
                            '<input name="submit_button" type="submit" value="Edit details">'.
                        '</div>' .
                    '</form>' ;
                    echo $subject_page;

                    // Activity Details
                    $query = "SELECT Activity_ID, Description, TestDate FROM Activity WHERE Subject_ID = '". $subject_id ."'";
                    $res = odbc_exec($conn, $query);

                    echo '<div class="table-row">';
                    for($i=2;$i<=odbc_num_fields($res);$i++){
                        echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
                    }
                    echo '</div>';

                    while(odbc_fetch_row($res)){
                        echo '<a href="./activity.php?id='.$subject_id.'&activity='.odbc_result($res, 1).'"><div class="table-row">';
                        for($i=2;$i<=odbc_num_fields($res);$i++){
                            if($i == 3){
                                echo "<div style='width: 33.33%'>" .substr(odbc_result($res, $i),0,-9 )."</div>";
                            } else {
                                echo "<div style='width: 33.33%'>" .odbc_result($res, $i) ."</div>";
                            }
                        }
                        if($_SESSION['page'] == 'individual-researcher'){
                            echo '<button type="button" id="remove-button" onClick="removeSubject(\''.$researcher_id.'\',\''. odbc_result($res, 1).'\')">Remove</button>';
                        }
                        echo "</div></a>";
                    }

                } else {
                    echo '<script type="text/javascript">'.
                    'subjectPromt();'.
                    '</script>';
                }
            // Subject doesn't exist or don't have permission
            } else {
                echo '<script type="text/javascript">'.
                'subjectPromt();'.
                '</script>';
            }
        // Page invalid
        } else {
            echo '<script type="text/javascript">'.
                'subjectPromt();'.
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