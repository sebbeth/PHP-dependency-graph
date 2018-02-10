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

      <link href="graph/style.css" rel="stylesheet" />

      <script src="graph/cytoscape.min.js"></script>
      <script src="https://cdn.rawgit.com/cpettitt/dagre/v0.7.4/dist/dagre.min.js"></script>
      <script src="https://cdn.rawgit.com/cytoscape/cytoscape.js-dagre/1.5.0/cytoscape-dagre.js"></script>
    </head>

    <body>
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
        <div class="col-6 bg-warning" style="height: 400px">
          <div id="cy"></div>
        </div>
        <div class="col-3">
        </div>
      </div>
      <div class="row">
      </div>
      <!-- Graph -->
    </div>

    <script>

    var cy = window.cy = cytoscape({
      container: document.getElementById("cy"),

      boxSelectionEnabled: false,
      autounselectify: true,
      layout: {
        name: "dagre"
      },
      style: [
        {
          selector: "node",
          style: {
            "content": "data(id)",
            "text-opacity": 0.5,
            "text-valign": "center",
            "text-halign": "right",
            "background-color": "#11479e"
          }
        },

        {
          selector: "edge",
          style: {
            "curve-style": "bezier",
            "width": 4,
            "target-arrow-shape": "triangle",
            "line-color": "#9dbaea",
            "target-arrow-color": "#9dbaea"
          }
        }
      ],
      elements: {
        nodes: [
        ';
          $output .= '{ data: { id: "' . 'File' . '"} },';
          $output .= '{ data: { id: "' . 'File2' . '"} },';

        $output .= '],
        edges: [';
        $output .= '{ data: { source: "' .'File' . '", target: "' .'File2' .  '" } },';


        $output .= ']
      },
    });

    </script>

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
