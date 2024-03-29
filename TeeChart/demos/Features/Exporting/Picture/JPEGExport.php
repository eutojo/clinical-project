<?php
      //Includes
      include "../../../../sources/TChart.php";

      // Get Values from form
      if(isset($_REQUEST["path"]))
        $path = $_REQUEST['path'];

      // Assign Header text
      $chart1 = new TChart(600,450);
      $chart1->getHeader()->setText("JPEG Export Demo");

      // Add Series to the Chart
      $volume = new Volume($chart1->getChart());
      $volume->fillSampleValues();
      $volume->getMarks()->setVisible(true);
 
      // Save Chart to text
      if(isset($_REQUEST['submit'])) {
          if ($path!="") {
            if (realpath($path)) {
              $chart1->doInvalidate();
              $chart1->getChart()->getExport()->getImage()->getJPEG()->save($path."\\TChart.jpg");
              echo "The Chart has been exported correctly !<br>";
            }
          }
          else
          {
              echo "Correct path must be entered ! ";
          }
      }
          
      $chart1->render("chart1.png");      
      $rand=rand();
      print '<font face="Verdana" size="2">JPEG Export Format<p>';
      print '<img src="chart1.png?rand='.$rand.'"><p>';               
?>

<html><body>
<font face="Verdana" size="2">
  <br />
<form method="post" action="JPEGExport.php">
  Path:  <input name="path" type="text" value="c:\temp" />
  <input type="submit" name="submit" value="Save To JPEG">
</form>
</font>
</body></html>