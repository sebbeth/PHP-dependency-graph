<?php


class PhpDependencyGraphGenerator {

  const outputFileName = 'Report.html';


  function generateGlobalGraph() {
  }

  function fileSearchGraph() {


    $input = 'dbconfig.php';
    echo 'Search for: ' . $input . PHP_EOL;
  $resultArray = $this->recursiveStringSearch($input,'./test');

    var_dump($resultArray);



    $output = '

    <!doctype html>
    <html lang="en">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Scope Query</title>
    </head>

    <script>
  function test() {';



   $output .= 'var jArray= JSON.parse('.json_encode($resultArray).')';

    $output .= 'for(var i=0;i<6;i++){
      alert("Hello\nHow are you?");
  }
}
  </script>


    <body onload="test()">
    <style>
    .section {
      margin:30px;
      padding:20px;
      background-color: #e6e6e6;
    }
    </style>

    <div class="card section">
    <div class="card-body">
    <h2 class="text-center">Search results</h2>

    </div>
    </div>
    <div class="row">
    <div class="col-3">
    </div>
    <div class="col-6">
    </div>
    <div class="col-3">
    </div>
    </div>
    <div class="row">
    </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
    </html>
    ';



    $myfile = fopen(self::outputFileName, "w") or die("Unable to create file");
    fwrite($myfile, $output);
    fclose($myfile);


    echo 'Results saved to file: Report.html' . PHP_EOL;

  }


  private function recursiveStringSearch($search,$rootDirectory) {


    // https://stackoverflow.com/questions/19971428/recursively-search-all-directories-for-an-array-of-strings-in-php
    $output = array();
      $searchArray = array($search);
    $pattern = implode('\|', $searchArray);
    $command = "grep -r -l '$pattern' $rootDirectory";
    $output = array();
    exec($command, $output);
    foreach ($output as $match) {
        array_push($output,$match);
    }
    return $output;

  }





}


?>
