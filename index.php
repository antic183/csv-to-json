<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>csv-to-json</title>
  </head>
  <body>
    <?php
    if (isset($_GET['save'])) {
      define('CSV_DELIMITER', ';');
      $tmpDataArray = [];

      //read csv content
      $fileContent = file_get_contents('daten-csv.csv');
      //convert german csv-charset in utf-8
      $convertedContent = iconv("ISO-8859-1", "UTF-8", $fileContent);

      //red csv lines
      $data = str_getcsv($convertedContent, PHP_EOL);
      //remove csv title row
      array_shift($data);

      //generate temporary array
      $data = array_map(function($row) use (&$tmpDataArray) {
        $aTmpRow = str_getcsv($row, CSV_DELIMITER);

        $tmpKey = trim($aTmpRow[0]);
        array_shift($aTmpRow);

        $tmpDataArray[$tmpKey] = $aTmpRow;
      }, $data);


      // generate json from temporary array
      $jsonData = json_encode($tmpDataArray, JSON_HEX_TAG | JSON_HEX_AMP);
      // write json file
      file_put_contents('data.json', $jsonData);
    } else if (isset($_GET['read'])) {
      //read json file
      $tmpData = file_get_contents('data.json');
      //json to std class
      $jsonData = json_decode($tmpData);
      //show object
      debug($jsonData);
    }

    function debug($toShow) {
      echo '<pre>';
      print_r($toShow);
      echo '</pre>';
    }
    ?>
  </body>
</html>
