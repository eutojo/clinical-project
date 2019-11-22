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

            // Check permissions
            $query = "SELECT count(*) FROM Assignments WHERE Subject_ID = '". $subject_id."' AND Researcher_ID = '".$_SESSION['researcher_ID']."'";
            $res = odbc_exec($conn, $query);

            if (odbc_result($res, 1) == 0 && $_SESSION['admin'] == 0) {
                echo '<script type="text/javascript">'.
                'subjectPromt(0);'.
                '</script>';
            } else {
                echo '<div class="page-container">';
                echo '<h1>['. $subject_id.'] '.$surname.', '.$name.' - Activity Details</h1>';

                $activity_id = $_GET['activity'];
                $query = "SELECT VitalSignType, Data FROM Physiological_data WHERE Activity_ID = ". $activity_id ."";
                $res = odbc_exec($conn, $query);

                $ecg_flag = 0;
                $ppg_flag = 0;
                $HR_flag = 0;
                $BP_flag = 0;
                $count = 0;
                while(odbc_fetch_row($res)){
                if($count == 0){
                    $query = "SELECT Description, TestDate FROM Activity WHERE Activity_ID = ". $activity_id ."";
                    $res_details = odbc_exec($conn, $query);
                    // header
                    echo '<h2>'.odbc_result($res_details, 1).' on '.substr(odbc_result($res_details, 2), 0, -9);
                }
                    
                    if(odbc_result($res, 1) == 'ECG') {
                        echo '<h1>ECG</h1>';
                        echo '<div style="display:none">'.odbc_result($res, 2).'</div>';
                        $data = explode(' ', odbc_result($res, 2));
                        $ignorenulls = 1;

                        $chart1 = new TChart(500,250);
                        $chart1->getChart()->getHeader()->setText("FastLine Style");
                        $chart1->getChart()->getAspect()->setView3D(false);
                        $chart1->getChart()->getAxes()->getLeft()->getMinorGrid()->setVisible(false);
                        $chart1->getChart()->getAxes()->getBottom()->getMinorGrid()->setVisible(false);
                        $chart1->getChart()->getLegend()->setVisible(false);
                        // Add FastLine Series
                        $fastLine=new FastLine($chart1->getChart());
                        $fastLine->setColor(new Color(250,72,52));

                        $fastLine->addArray($data);

                        // Changes Stairs mode
                        $fastLine->setStairs(false);
                        // Inverted Stairs

                        $fastLine->setInvertedStairs(false);
                        // FastLine Ignore Nulls
                        $fastLine->setIgnoreNulls(true);

                        $chart1->render("chart1.png");
                        $rand=0;
                        print '<img src="chart1.png?rand='.$rand.'">';
                        $ecg_flag = 1;
                    } else if (odbc_result($res, 1) == 'PPG') {
                        if($ecg_flag == 0) {
                            echo '<div>No data to display.</div>';
                        }
                        echo '<h1>PPG</h1>';
                        $data = explode(' ', odbc_result($res, 2));
                        $ignorenulls = 1;

                        $chart1 = new TChart(500,250);
                        $chart1->getChart()->getHeader()->setText("FastLine Style");
                        $chart1->getChart()->getAspect()->setView3D(false);
                        $chart1->getChart()->getAxes()->getLeft()->getMinorGrid()->setVisible(false);
                        $chart1->getChart()->getAxes()->getBottom()->getMinorGrid()->setVisible(false);
                        $chart1->getChart()->getLegend()->setVisible(false);
                        // Add FastLine Series
                        $fastLine=new FastLine($chart1->getChart());
                        $fastLine->setColor(new Color(250,72,52));

                        $fastLine->addArray($data);

                        // Changes Stairs mode
                        $fastLine->setStairs(false);
                        // Inverted Stairs

                        $fastLine->setInvertedStairs(false);
                        // FastLine Ignore Nulls
                        $fastLine->setIgnoreNulls(true);

                        $chart1->render("chart2.png");
                        $rand=0;
                        print '<img src="chart2.png?rand='.$rand.'">';
                        $ppg_flag = 1;
                    } else if (odbc_result($res, 1) == 'Heart Rate') {
                        if($ecg_flag == 0) {
                            echo '<h1>ECG</h1>';
                            echo '<div>No data to display.</div>';
                            $ecg_flag = 1;
                        }
                        if($ppg_flag == 0){
                            echo '<h1>PPG</h1>';
                            echo '<div>No data to display.</div>';
                            $ppg_flag = 1;
                        }
                        echo '<div class="table-col">';
                            echo '<div class="table-row" style="justify-content: space-evenly">';
                        echo '<div>';
                        echo 'Heart Rate';
                        echo '</div>';
                        echo '<div>';
                        echo odbc_result($res, 2);
                        echo '</div>';
                        echo '</div>';
                        $HR_flag = 1;

                    } else {
                        if($ecg_flag == 0) {
                            echo '<h1>ECG</h1>';
                            echo '<div>No data to display.</div>';
                            $ecg_flag = 1;
                        }
                        if($ppg_flag == 0){
                            echo '<h1>PPG</h1>';
                            echo '<div>No data to display.</div>';
                            $ppg_flag = 1;
                        }
                        if($HR_flag == 0){
                            echo '<div class="table-col">';
                            echo '<div class="table-row" style="justify-content: space-evenly">';
                            echo '<div>';
                            echo 'Heart Rate';
                            echo '</div>';
                            echo '<div>';
                            echo 'No data available.';
                            echo '</div>';
                            echo '</div>';
                            $HR_flag = 1;
                        }
                        $data = explode(' ', odbc_result($res, 2));
                        echo '<div class="table-row" style="justify-content: space-evenly">';
                        echo '<div>';
                        echo 'Systolic';
                        echo '</div>';
                        echo '<div>';
                        echo $data[0];
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="table-row" style="justify-content: space-evenly">';
                        echo '<div>';
                        echo 'Diastolic';
                        echo '</div>';
                        echo '<div>';
                        echo $data[1];
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="table-row" style="justify-content: space-evenly">';
                        echo '<div>';
                        echo 'Mean Pressure';
                        echo '</div>';
                        echo '<div>';
                        echo $data[2];
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        $BP_flag = 1;
                    }

                    $count++;
                }

                // No data to display
                if($BP_flag == 0){
                    if($ecg_flag == 0) {
                        echo '<h1>ECG</h1>';
                        echo '<div>No data to display.</div>';

                    }
                    if($ppg_flag == 0){
                        echo '<h1>PPG</h1>';
                        echo '<div>No data to display.</div>';
                    }
                    if($HR_flag == 0){
                        echo '<div style="display: flex;flex-direction: column; width:100%">';
                        echo '<div style="display:flex; flex-direction: row">';
                        echo '<div>';
                        echo 'Heart Rate';
                        echo '</div>';
                        echo '<div>';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '<div style="display:flex; flex-direction: row">';
                        echo '<div>';
                        echo 'Systolic';
                        echo '</div>';
                        echo '<div>';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                        echo '<div style="display:flex; flex-direction: row">';
                        echo '<div>';
                        echo 'Diastolic';
                        echo '</div>';
                        echo '<div>';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                        echo '<div style="display:flex; flex-direction: row">';
                        echo '<div>';
                        echo 'Mean Pressure';
                        echo '</div>';
                        echo '<div>';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo $ppg_flag;

                }

                echo '</div>';

            }
        }
    } else {
        echo '<script type="text/javascript">'.
                'loginPrompt();'.
                '</script>';
    }
    