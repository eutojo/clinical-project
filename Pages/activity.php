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
        echo '<div class="page-container" style="margin-bottom: 20px">';

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
                echo '<div class="page-container activity">';
                echo '<a href="./individual-subject.php?id='.$subject_id.'"><h1 style="text-align: left">['. $subject_id.'] '.$surname.', '.$name.' - Activity Details</h1></a>';

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
                    $date = new DateTime(odbc_result($res_details, 2));
                    $dob = str_replace('-','/',$date->format('d-m-Y'));
                    echo '<h2>'.odbc_result($res_details, 1).' on '.$dob;
                }
                    
                    if(odbc_result($res, 1) == 'ECG') {
                        echo '<h1>ECG</h1>';
                        echo '<div style="display:none">'.odbc_result($res, 2).'</div>';
                        $data = explode(' ', odbc_result($res, 2));
                        $ignorenulls = 1;
                        $increments = array();

                        for($i=0; $i<sizeof($data);$i++){
                            $k = $i*0.02*1000;
                            array_push($increments, $k);
                        }

                        $chart1 = new TChart(500,250);
                        $chart1->getChart()->getHeader()->setText("ECG data");
                        $chart1->getChart()->getAspect()->setView3D(false);
                        $chart1->getChart()->getAxes()->getLeft()->getMinorGrid()->setVisible(false);
                        $chart1->getAxes()->getBottom()->getTitle()->setText("Time (ms)");
                        $chart1->getAxes()->getLeft()->getTitle()->setText("Voltage (mV)");
                        $chart1->getChart()->getAxes()->getBottom()->getMinorGrid()->setVisible(false);
                        $chart1->getChart()->getLegend()->setVisible(false);
                        // Add FastLine Series
                        $fastLine=new FastLine($chart1->getChart());
                        $fastLine->setColor(new Color(250,72,52));

                        $fastLine->addArrays($increments, $data);

                        // Changes Stairs mode
                        $fastLine->setStairs(false);
                        // Inverted Stairs

                        $fastLine->setInvertedStairs(false);
                        // FastLine Ignore Nulls
                        $fastLine->setIgnoreNulls(true);

                        $chart1->render("chart1.png");
                        $rand=0;
                        echo '<div class="graph">';
                        print '<img src="chart1.png?rand='.$rand.'">';
                        echo '</div>';
                        $ecg_flag = 1;
                    } else if (odbc_result($res, 1) == 'PPG') {
                        if($ecg_flag == 0) {
                            echo '<div class="incorrect">No data to display.</div>';
                        }
                        echo '<h1>PPG</h1>';
                        $data = explode(' ', odbc_result($res, 2));
                        $ignorenulls = 1;

                        $increments = array();

                        for($i=0; $i<sizeof($data);$i++){
                            $k = $i*0.02;
                            array_push($increments, $k);
                        }

                        $chart1 = new TChart(500,250);
                        $chart1->getChart()->getHeader()->setText("PPG data");
                        $chart1->getChart()->getAspect()->setView3D(false);
                        $chart1->getChart()->getAxes()->getLeft()->getMinorGrid()->setVisible(false);
                        $chart1->getAxes()->getBottom()->getTitle()->setText("Time (s)");
                        $chart1->getAxes()->getBottom()->setIncrement(2);
                        $chart1->getAxes()->getLeft()->getTitle()->setText("Voltage (mV)");
                        $chart1->getChart()->getAxes()->getBottom()->getMinorGrid()->setVisible(false);
                        $chart1->getChart()->getLegend()->setVisible(false);
                        // Add FastLine Series
                        $fastLine=new FastLine($chart1->getChart());
                        $fastLine->setColor(new Color(250,72,52));

                        $fastLine->addArrays($increments, $data);

                        // Changes Stairs mode
                        $fastLine->setStairs(false);
                        // Inverted Stairs

                        $fastLine->setInvertedStairs(false);
                        // FastLine Ignore Nulls
                        $fastLine->setIgnoreNulls(true);

                        $chart1->render("chart2.png");
                        $rand=0;
                        echo '<div class="graph">';
                        print '<img src="chart2.png?rand='.$rand.'">';
                        echo '</div>';
                        $ppg_flag = 1;
                    } else if (odbc_result($res, 1) == 'Heart Rate') {
                        if($ecg_flag == 0) {
                            echo '<h1>ECG</h1>';
                            echo '<div class="incorrect">No data to display.</div>';
                            $ecg_flag = 1;
                        }
                        if($ppg_flag == 0){
                            echo '<h1>PPG</h1>';
                            echo '<div class="incorrect">No data to display.</div>';
                            $ppg_flag = 1;
                        }
                        echo '<h1>Heart Rate & Blood Pressure</h1>';
                        echo '<div class="table-col">';
                        echo '<div class="table-row"  style="margin-top: 2em">';
                        echo '<div>';
                        echo 'Heart Rate';
                        echo '</div>';
                        echo '<div>';
                        echo odbc_result($res, 2) . ' beats per minute';
                        echo '</div>';
                        echo '</div>';
                        $HR_flag = 1;

                    } else {
                        if($ecg_flag == 0) {
                            echo '<h1>ECG</h1>';
                            echo '<div class="incorrect">No data to display.</div>';
                            $ecg_flag = 1;
                        }
                        if($ppg_flag == 0){
                            echo '<h1>PPG</h1>';
                            echo '<div class="incorrect">No data to display.</div>';
                            $ppg_flag = 1;
                        }
                        if($HR_flag == 0){
                            echo '<h1>Heart Rate & Blood Pressure</h1>';
                            echo '<div class="table-col">';
                            echo '<div class="table-row" style="margin-top: 2em">';
                            echo '<div>';
                            echo 'Heart Rate';
                            echo '</div>';
                            echo '<div class="incorrect">';
                            echo 'No data available.';
                            echo '</div>';
                            echo '</div>';
                            $HR_flag = 1;
                        }
                        $data = explode(' ', odbc_result($res, 2));
                        echo '<div class="table-row table-odd">';
                        echo '<div>';
                        echo 'Systolic';
                        echo '</div>';
                        echo '<div>';
                        echo $data[0] . ' mmHg';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="table-row">';
                        echo '<div>';
                        echo 'Diastolic';
                        echo '</div>';
                        echo '<div>';
                        echo $data[1] . ' mmHg';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="table-row table-odd">';
                        echo '<div>';
                        echo 'Mean Pressure';
                        echo '</div>';
                        echo '<div>';
                        echo $data[2] . ' mmHg';
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
                        echo '<div class="incorrect">No data to display.</div>';

                    }
                    if($ppg_flag == 0){
                        echo '<h1>PPG</h1>';
                        echo '<div class="incorrect">No data to display.</div>';
                    }
                    if($HR_flag == 0){
                        echo '<h1>Heart Rate & Blood Pressure</h1>';
                        echo '<div style="display: flex;flex-direction: column; width:100%">';
                        echo '<div class="table-row" style="margin-top: 2em">';
                        echo '<div>';
                        echo 'Heart Rate';
                        echo '</div>';
                        echo '<div class="incorrect">';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                    }
                        echo '<div class="table-row table-odd">';
                        echo '<div>';
                        echo 'Systolic';
                        echo '</div>';
                        echo '<div class="incorrect">';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="table-row">';
                        echo '<div>';
                        echo 'Diastolic';
                        echo '</div>';
                        echo '<div class="incorrect">';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="table-row table-odd">';
                        echo '<div>';
                        echo 'Mean Pressure';
                        echo '</div>';
                        echo '<div class="incorrect">';
                        echo 'No data available.';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';

                }

                echo '</div>';

            }
        }
        echo '</div>';
    } else {
        echo '<script type="text/javascript">'.
                'loginPrompt();'.
                '</script>';
    }
    