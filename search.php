<?php
include("./inc_header.php");
include("./SearchResult.php");
?>
<h1>Search</h1>
<form class="form-inline my-2 my-lg-0" style="margin:10px" method="POST" >
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="search" name="search_input">
      <button class="btn btn-small btn-primary" type="submit" name="search">Search</button>
      <button class="btn btn-small btn-warning" name="clean" onClick="history.go(0);">Clear</button>
</form>

<?php
$input = "";

if(isset($_POST['search'])){ //check if form was submitted
      $input = $_POST['search_input']; //get input text

      $searchResult = new SearchResult($input);

      $searchResult->creatingNewsletterTable();

      echo "<hr>";

      $searchResult->creatingDrugTable();

}

function clearInput() {
      $input = "";
}

?>

<?php include("./inc_footer.php"); ?>