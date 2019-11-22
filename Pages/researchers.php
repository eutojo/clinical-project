<?php
    session_start(); 
    $_SESSION['page'] = 'researchers';
?>

<head>
    <!-- Link to stylesheet -->
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/menu-bar.css">
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="stylesheet" href="../CSS/researcher.css">

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
        $query = "SELECT Researcher_ID, FirstName, Surname FROM Researcher";
        $res = odbc_exec($conn, $query);
        
        echo '<div class="page-container">';
        echo '<div class="table-row">';
        for($i=1;$i<=odbc_num_fields($res);$i++){
            echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
        }
        echo '<div style="width:85.64px"></div>';
        echo "</div>";
        $flag = 0;
        while(odbc_fetch_row($res)){
            $flag = $flag + 1;
            echo '<a href="./individual-researcher.php?id='.odbc_result($res, 1).'">';

            if($flag%2==1){
                echo '<div class="table-row table-odd">';
            } else {
                echo '<div class="table-row">';
            }
            
            for($i=1;$i<=odbc_num_fields($res);$i++){
                echo "<div id=".odbc_result($res, 1) ." style='width: 25%'>" .odbc_result($res, $i) ."</div>";
            }
            echo '<button type="button" id="remove-button" onClick="removeSubject(\''. odbc_result($res, 1).'\', \'NULL\')">Remove</button>';
            echo "</div></a>";
        }

        $researcher_component = ''. 

        '<div>'.
            '<h2> Add new researcher </h2>' .
            // Table for input
            '<form class="researcher" method="POST" action="../PHP/new-entry-logic.php" onSubmit="return validInfo(\'researcher\')">'.
                '<div class="table-col">' .
                    '<span id="validation__researcher_name"></span>'.
                    '<div>'.
                        'First Name'.
                        '<input class="researcher" maxlength="15" type="text" id="new__researcher_name" name="new__researcher_name" onChange="validateName(\'first\',\'researcher\')">'.
                    '</div>'.
                    '<span id="validation__researcher_surname"></span>'.
                    '<div>'.
                        'Surname'.
                        '<input class="researcher" maxlength="15" type="text" id="new__researcher_surname" name="new__researcher_surname" onChange="validateName(\'last\',\'researcher\')">'.
                    '</div>'.
                    '<span id="validation__researcher_id"></span>'.
                    '<div>'.
                        'ID'.
                        '<input class="researcher" maxlength="6" type="text" id="new__researcher_id" name="new__researcher_id" onChange="validateID(\'researcher\')">'.
                    '</div>'.
                    '<div style="height: 1.5em"></div>'.
                '</div>'.
                
                '<div class="table-col">' .
                    '<span id="validation__researcher_password1"></span>'.
                    '<div>'.
                        'Password'.
                        '<input class="researcher" type="password" id="new__researcher_password1" name="new__researcher_password1" onChange="validatePassword1()">'.
                    '</div>'.
                    '<span id="validation__researcher_password2"></span>'.
                    '<div>'.
                        'Retype Password'.
                        '<input class="researcher" type="password" id="new__researcher_password2" name="new__researcher_password2" onChange="validatePassword2()">'.
                    '</div>'.
                    '<div class="checkbox-container">'.
                        '<input class="researcher" type="checkbox" id="new__researcher_admin" name="new__researcher_admin" value="1"> Admin Privileges'.
                    '</div>'.
                    '<div style="padding-top: 1em">'.
                        '<input class="researcher" type="submit" value="Submit">'.
                    '</div>'.
                '</div>'.
            '</form>'.
        '</div>'.
        '</div>';

        echo $researcher_component;
        
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