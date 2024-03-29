<?php
      //Includes
      include "../../../../sources/TChart.php";

      // Get Values from form
      if(isset($_REQUEST["path"]))
        $path = $_REQUEST['path'];
      if(isset($_REQUEST["header"]))
        $header = $_REQUEST["header"];
      if(isset($_REQUEST["seriestitle"]))
        $seriestitle = $_REQUEST["seriestitle"];
      if(isset($_REQUEST["pointindex"]))
        $pointindex = $_REQUEST["pointindex"];
      if(isset($_REQUEST["labels"]))
        $labels = $_REQUEST["labels"];

      // Assign Header text
      $chart1 = new TChart(600,450);
      $chart1->getHeader()->setText("Text Export Demo");

      // Add Series to the Chart
      $bar = new Bar($chart1->getChart());
      $bar->fillSampleValues();

      $chart1->render("chart1.png");
      $rand=rand();
      print '<font face="Verdana" size="2">Text Export Format is allowed<p>';
      print '<img src="chart1.png?rand='.$rand.'"><p>';         

      // Save Chart to text
      if(isset($_REQUEST['submit'])) {
          if ($path!="") {
            if (realpath($path)) {
              $chart1->getExport()->getData()->getText()->setIncludeHeader(isset($header));
              $chart1->getExport()->getData()->getText()->setIncludeSeriesTitle($seriestitle);
              $chart1->getExport()->getData()->getText()->setIncludeIndex(isset($pointindex));
              $chart1->getExport()->getData()->getText()->setIncludeLabels(isset($labels));

              $chart1->getChart()->getExport()->getData()->getText()->save($path."\TChart.txt");
              echo "The Chart has been exported correctly !";
            }
          }
          else
          {
              echo "Correct path must be entered ! ";
          }
      }
?>

<html><body>
<font face="Verdana" size="2">
  <br />
<form method="post" action="TextExport.php">
  <p>Select text export options:</p>
  Include Header: <input type="checkbox" name="header" value="header"  /><br />
  Include Series Title: <input type="checkbox" name="seriestitle" value="seriestitle"  /><br />
  Include Point Index: <input type="checkbox" name="pointindex" value="pointindex"  /><br />
  Include Labels: <input type="checkbox" name="labels" value="labels"  /><br /><br />
  Path:  <input name="path" type="text" value="c:\temp" />
  <input type="submit" name="submit" value="Save To Text">
</form>
</font>
</body></html>