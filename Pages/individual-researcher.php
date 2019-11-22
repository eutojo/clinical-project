<?php
    session_start(); 
    $_SESSION['page'] = 'individual-researcher';
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
            $id = $_GET['id'];
            $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);

            // Check if Subject exists
            $query = "SELECT FirstName, Surname, Admin FROM Researcher WHERE Researcher_ID = '". $id ."'";
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
                $firstname = odbc_result($res, 1);
                $surname = odbc_result($res, 2);
                $admin = odbc_result($res, 3);

                $subject_page = ''.
                    '<div class="page-container">'.
                    '<h1>['. $id.'] '.$surname.', '.$firstname.'</h1>'.
                    '<form id="form__change_researcher" name="form__change_researcher" method="POST" onSubmit="return validInfo(\'inv_researcher\')" action="../PHP/modify-entry-logic.php">'. 
                        '<div style="width: 33.33%" >' .
                        // ID
                            '<label>ID</label>'.
                            '<input readonly type="text" id="researcher_id" name="researcher_id" value="'.$id.'">'.
                            '</div>' .
                        '<div style="width: 33.33%" >' .
                            // First Name
                            '<label>First Name</label>'.
                            '<input readonly type="text" id="researcher_name" name="researcher_name" value="'.$firstname.'" onChange="validateName(\'first\',\'inv_researcher\')">'.
                            '<span id="validation__inv_researcher_name"></span>'.
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            // Last Name
                            '<label>Last Name</label>'.
                            '<input readonly type="text" id="researcher_surname" name="researcher_surname" value="'.$surname.'" onChange="validateName(\'last\',\'inv_researcher\')">'.
                            '<span id="validation__inv_researcher_surname"></span>'.
                        '</div>' .               
                        '<div style="width: 33.33%" >' .
                            '<label>New Password</label>'.
                            '<input readonly type="text" id="researcher_password1" name="researcher_password1" onChange="validatePassword1(\'inv_researcher\')">'.
                            '<span id="validation__inv_researcher_password1"></span>'.
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            '<label>New Retype Password</label>'.
                            '<input readonly type="text" id="researcher_password2" name="researcher_password2" onChange="validatePassword2(\'inv_researcher\')">'.
                            '<span id="validation__inv_researcher_password2"></span>'.
                        '</div>' .
                        '<div style="width: 33.33%" >' .
                            '<label>Admin</label>';
                            if($admin == 1){
                                $subject_page .=
                                '<input disabled type="checkbox" id="researcher_admin" name="researcher_admin" checked>';
                            } else {
                                $subject_page .=
                                '<input disabled type="checkbox" id="researcher_admin" name="researcher_admin">';
                                    
                            }
                        $subject_page .=
                        '</div>' .
                        '<div>' .
                            // Submit button
                            '<input name="submit_button" type="submit" value="Edit details">'.
                        '</div>'.
                    '</form>';
                    

        
                    echo $subject_page;

                require_once('../Components/subject-table.php');

                require_once('../Components/new-assignment.php');
            
                echo '</div>';
            // Subject doesn't exist
            } else {
                // Direct to researcher page if admin
                if($_SESSION['admin'] == 1){
                    echo '<script type="text/javascript">'.
                    'researcherPrompt(1);'.
                    '</script>';
                // Direct to login if not admin
                } else {
                    echo '<script type="text/javascript">'.
                    'researcherPrompt(0);'.
                    '</script>';
                }
            }
    
        // Page invalid
        } else {
             // Direct to researcher page if admin
             if($_SESSION['admin'] == 1){
                echo '<script type="text/javascript">'.
                'researcherPrompt(1);'.
                '</script>';
            // Direct to login if not admin
            } else {
                echo '<script type="text/javascript">'.
                'researcherPrompt(0);'.
                '</script>';
            }
        }

    // Not logged in, redirect
    } else {
        echo '<script type="text/javascript">'.
                'loginPrompt();'.
                '</script>';
    }

    
    
    ?>
</body>