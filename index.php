<?php
include("./inc_header.php");
include("./SearchResult.php");
?>
<table style="width:100%; margin-top:10px;">
      <tr>
            <td>
                  <h1 style="margin:0px;">Search</h1>
            </td>
            <td>
                  <img src="./images/TI_logo.png" height="50px" style="float:right; margin-right:50px;">
            </td>
      </tr>
</table>
<form class="form-inline my-2 my-lg-0" style="margin:10px" method="POST" >
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="search" name="search_input">
      <button class="btn btn-small btn-primary" type="submit" name="search">Search</button>
      <button class="btn btn-small btn-warning" name="clean" onClick="history.go(0);">Clear</button>
      <hr/>
</form>

<?php
$input = "";

if(isset($_POST['search'])){ //check if form was submitted
      $input = $_POST['search_input']; //get input text

      $searchResult = new SearchResult($input);
      $message = $searchResult->getMessage();
      echo "<h4 style=text-align:center;>Showing result for
                   <span style='color:red'>$input</span>.";
      echo "<h4 style=text-align:center;> $message </h4>";
      $searchResult->creatingNewsletterTable();
      echo "<hr/>";
      $searchResult->creatingDrugTable();
      echo "<hr/>";
      $searchResult->creatingPharmacareTable();
      echo "<hr/>";
      $searchResult->creatingAdvTable();

      
}
?>
<?php include("./inc_footer.php"); ?>