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
include('../TeeChart/sources/TChart.php');
require_once('../Components/menu-bar.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    if($_SESSION['admin'] == 1) {
        $arr_activity_type = array('Baseline Supine Rest', 'Head-up Tilt Test', 'Suction Test: Level 1', 'Suction Test: Level 2');
        $today = date("d/m/Y");
        $conn = odbc_connect('z5116858', '', '',SQL_CUR_USE_ODBC);
        

        
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

            echo '<h1>'.$type.'</h1>';
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
            $title = $type . ' Mean and Standard Deviation';
        $chart1->getChart()->getHeader()->setText($title);
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
            print '<img src="'.$chart_name.'?rand='.$rand.'">';   
        
        }    
    } else {
        echo '<script type="text/javascript">'.
            'adminPrompt();'.
            '</script>';
    }
    
} else {
    echo '<script type="text/javascript">'.
            'loginPrompt();'.
            '</script>';
}
