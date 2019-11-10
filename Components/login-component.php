<?php

    $login_component = ''.
    '<div class ="login-page container" >'.
    '<h1>Please login.</h1>'.
        '<form method="POST" action="../PHP/login-logic.php">'.
            '<!-- Username -->'.
            'Username'.
            '<span>'.
                '<!-- For error handling -->'.
            '</span>'.
            '<input type="text" id="login-username" name="login-username">'.
            '<!-- Password -->'.
            'Password'.
            '<span>'.
                '<!-- For error handling -->'.
            '</span>'.
            '<input type="password" id="login-password" name="login-password">'.
            '<input type="submit" value="Login">'.
        '</form>'.
    '</div>';

    echo $login_component;
?>