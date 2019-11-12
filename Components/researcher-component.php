<?php
    // if user is admin - show them all
    if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
        $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
        $query = "SELECT Researcher_ID, FirstName, Surname, Admin FROM Researcher";
        $res = odbc_exec($conn, $query);

        echo '<div class="table-row">';
        for($i=1;$i<=odbc_num_fields($res);$i++){
            echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
        }
        echo "</div>";
        while(odbc_fetch_row($res)){
            echo "<div class='table-row'>";
            for($i=1;$i<=odbc_num_fields($res);$i++){
                echo "<div id=".odbc_result($res, 1) ." style='width: 25%'>" .odbc_result($res, $i) ."</div>";
            }
            echo '<button type="button" id="remove-button" onClick="removeSubject(\''. odbc_result($res, 1).'\', \'NULL\')">Remove</button>';
            echo "</div>";
        }

        $researcher_component = ''. 

        '<div>'.
            '<h2> Add new researcher </h2>' .
            // Table for input
            '<form class="researcher" method="POST" action="../PHP/new-entry-logic.php">'.
                '<div class="table-col">' .
                    '<div>'.
                        'First Name'.
                        '<input class="researcher" type="text" id="new__researcher_name" name="new__researcher_name">'.
                    '</div>'.
                    '<div>'.
                        'Surname'.
                        '<input class="researcher" type="text" id="new__researcher_surname" name="new__researcher_surname">'.
                    '</div>'.
                    '<div>'.
                        'ID'.
                        '<input class="researcher" type="text" id="new__researcher_id" name="new__researcher_id">'.
                    '</div>'.
                '</div>'.
                '<div class="table-col">' .
                    '<div>'.
                        'Password'.
                        '<input class="researcher" type="password" id="new__researcher_password" name="new__researcher_password">'.
                    '</div>'.
                    '<div>'.
                        'Retype Password'.
                        '<input class="researcher" type="password" id="new__password_2" name="new__password_2">'.
                    '</div>'.
                    '<div>'.
                        '<input class="researcher" type="checkbox" id="new__researcher_admin" name="new__researcher_admin" value="1"> Admin Privileges'.
                    '</div>'.
                    '<div>'.
                        '<input class="researcher" type="submit" value="Submit">'.
                    '</div>'.
                '</div>'.
            '</form>'.
        '</div>';

        echo $researcher_component;
    }


?>