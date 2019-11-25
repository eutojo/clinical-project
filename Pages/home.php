<?php
    if(session_id() == ''){
        session_start();
    }
  
    $_SESSION['page'] = 'home';
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
        
            echo '<div class ="login-page container" >';
            echo '<h1>Welcome '. $_SESSION['name']. '</h1>';
            echo '<div><img src="./hi.gif"/></div>';
            echo '</div>';


        if($_SESSION['admin'] == 1) {
            include('../TeeChart/sources/TChart.php');
            echo '<div class="page-container summary">';
            $arr_activity_type = array('Baseline Supine Rest', 'Head-up Tilt Test', 'Suction Test: Level 1', 'Suction Test: Level 2');
            $today = date("d/m/Y");
            $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
            
            echo '<h1> Activity Summaries </h1>';
    
            foreach($arr_activity_type as $type){
                $query = 'SELECT DATEDIFF(\'yyyy\', Subject.[DOB],  \''.$today.'\') AS Age, AVG(CINT(Physiological_data.[data])) AS Average, Physiological_data.[VitalSignType], Activity.[Description] '.
                'FROM ((Subject RIGHT JOIN Activity ON Subject.[Subject_ID] = Activity.[Subject_ID]) INNER JOIN Physiological_data ON Activity.[Activity_ID] = Physiological_data.[Activity_ID])'. 
                'WHERE Physiological_data.[VitalSignType]=\'Heart Rate\' AND Activity.[Description] = \''.$type.'\' '.
                'GROUP BY DATEDIFF(\'yyyy\', Subject.[DOB],  \''.$today.'\'), Physiological_data.[VitalSignType], Activity.[Description] ORDER BY DATEDIFF(\'yyyy\', Subject.[DOB],  \''.$today.'\')';
                $res = odbc_exec($conn, $query);
    
                // Get type for variable names;
                $type_array = preg_replace('/\s+/','_',$type);
                $type_array = str_replace(':','',$type_array);
                $type_array = str_replace('-','_',$type_array);
                $ages_arr =array();
                $ave_arr = array();
                $std_arr = array();
    
                echo '<h2>'.$type.'</h2>';
                while(odbc_fetch_row($res)){
                    
                    // Average
                    $age = odbc_result($res, 1);
                    $average = odbc_result($res, 2);
    
                    $ave_arr[$age] = $average;
                    //STDEV
                    $query = 'SELECT STDEV(CINT(Physiological_data.[data])) AS STD '.
                    'FROM ((Subject RIGHT JOIN Activity ON Subject.[Subject_ID] = Activity.[Subject_ID]) INNER JOIN Physiological_data ON Activity.[Activity_ID] = Physiological_data.[Activity_ID])'. 
                    'WHERE Physiological_data.[VitalSignType]=\'Heart Rate\' AND Activity.[Description] = \''.$type.'\' AND DATEDIFF(\'yyyy\', Subject.[DOB],  \''.$today.'\') = \''.$age.'\'';
                    $res_dev = odbc_exec($conn, $query);
                    $std_dev = 0;
                    if(odbc_result($res_dev, 1) != null){
                        $std_dev = odbc_result($res_dev, 1);
                    } 
    
                    $std_arr[$age] = $std_dev;
    
                    array_push($ages_arr, $age);
    
                    
                }
    
                $chart1 = new TChart(750,600);
                $title = $type . ' Heart Rate Mean and Standard Deviation';
                $chart1->getChart()->getHeader()->setText($title);
                $chart1->getAxes()->getBottom()->getTitle()->setText("Age");
                $chart1->getAxes()->getLeft()->getTitle()->setText("Heart Rate (beats/second)");
                $chart1->getAspect()->setView3D(false);
                $errorPoint = new ErrorSeries($chart1->getChart());
                $errorPoint2 = new ErrorPoint($chart1->getChart());
                $chart1->getChart()->getLegend()->setVisible(false);
                $line = new Line($chart1->getChart()); 
    
                foreach($ages_arr as $age){
                    
                    $ave = $ave_arr[$age];
                    $std = $std_arr[$age];
                    $errorPoint2->addXYLRTB($age,$ave,0,0,0,0);
                    $errorPoint -> addXYErrorColor($age, $ave, $std, $color=null);
                    $line->addXY($age,$ave);
                }
                $line->setColorEach(false);
                $errorPoint2->setColorEach(false);
                $errorPoint->setColorEach(false);        
                $chart_name = 'activity_' . $type_array .'.png';
                $chart1->render($chart_name);                                                   
                $rand=rand();
                echo '<div class="graph">';
                print '<img src="'.$chart_name.'?rand='.$rand.'">'; 
                echo '</div>';  
                
            } 
            echo '</div>';   
        } else {

            echo '<div class="page-container login-page">';
            echo '<h1> Assigned Subjects </h1>';
            $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
            $query = "SELECT Subject.[Subject_ID], FirstName, LastName, DOB, Gender, Contact FROM Subject INNER JOIN Assignments ON (Subject.[Subject_ID] = Assignments.[Subject_ID] AND Assignments.[Researcher_ID] ='" . $_SESSION['researcher_ID']."')";
            $res = odbc_exec($conn, $query);
            
            $flag = 0;
            echo '<div class="table-row">';
            for($i=1;$i<=odbc_num_fields($res);$i++){
                echo "<div style='width: 33.33%'><h2>" .odbc_field_name($res, $i) ."</h2></div>";
            }
            echo '<div style="width:85.64px"></div>';
            echo "</div>";
            $flag = 0;
            while(odbc_fetch_row($res)){
                $flag = $flag + 1;
                if ($flag%2 == 1){
                    echo '<a href="./individual-subject.php?id='.odbc_result($res, 1).'" ><div class="table-row table-odd">';
                } else {
                    echo '<a href="./individual-subject.php?id='.odbc_result($res, 1).'" ><div class="table-row">';
                }
                
                for($i=1;$i<=odbc_num_fields($res);$i++){
                    if($i == 4){
                        $date = new DateTime(odbc_result($res, $i));
                        $date = str_replace('-','/',$date->format('d-m-Y'));
                        echo "<div style='width: 33.33%'>" .$date."</div>";
                    } else {
                        echo "<div style='width: 33.33%'>" .odbc_result($res, $i) ."</div>";
                    }                }
                echo "</div></a>";
            }
            if($flag == 0){
                echo '<div style="text-align:center; color: #D72226">No subjects have been assigned.</div>';
            }
            echo '</div>';
        }

    } else {
        if(isset($_SESSION['user']) && $_SESSION['user']=='INVALID' ){
            unset($_SESSION['user']);
            echo '<script type="text/javascript">'.
                'invalidLogin();'.
                '</script>';
        }
        $login_component = ''.
        '<div class ="login-page container" >'.
        '<h1>Please login.</h1>'.
            '<form class="login" method="POST" action="../PHP/login-logic.php">'.
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
    }

    
    
    ?>
</body>