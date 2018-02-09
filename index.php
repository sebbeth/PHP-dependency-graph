<?php

include_once 'PhpScopeQuery.php';

$searcher = new PhpScopeQuery();

if (isset($_POST['name'])) {
  echo $searcher->test($_POST['name']);

}


?>


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
<body>

  <style>

  .section {
margin:30px;
padding:20px;
background-color: #e6e6e6;

  }

  </style>

    <!-- INPUT PAGE -->
    <div class="container text-center">
      <div class="card section">
      <h1 class="text-center">PHP Scope Query</h1>
      <h2>Description</h2>
      <br><br>
      <div class="row">
        <div class="col-3">
        </div>
        <div class="col-6">
          <form action="" method="post">
            <div class="form-group">
              <label for="name">File, class or function name</label>
              <input type="text" class="form-control" id="name" name="name"  placeholder="file.php">
            </div>
            <div class="form-group">
              <label for="type">Input type</label>
              <select class="form-control" id="type" name="type">
                <option>File</option>
                <option>Class</option>
                <option>Function</option>
              </select>
            </div>
            <button type="submit" class="btn btn-lg btn-primary">Search</button>
          </form>
        </div>
        <div class="col-3">
        </div>
      </div>
    </div>
    <!-- /INPUT PAGE -->

    <? if (isset($_POST['name'])) { // Results page ?>

    <!-- RESULTS PAGE -->
      <br><br><br>
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
    <!-- /RESULTS PAGE -->

  <? }?>

</div>










  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
