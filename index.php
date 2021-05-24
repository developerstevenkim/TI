<?php
include("./inc_header.php");
include("./SearchResult.php");
?>

<table style="width:100%; margin-top:10px;">
      <tr>
            <td>
                  <h2 style="margin:0px;">Therapeutics Initiative Assistant</h2>
            </td>
            <td>
                  <img src="./images/TI_logo.png" height="50px" style="float:right; margin-right:50px;">
            </td>
      </tr>
</table>
</br>
<form class="form-inline" style="margin:10px" method="POST" >
      <input class="form-control" style="width: 15vw;" type="search" placeholder="Ex: A02BC02, or pantoprazole" aria-label="Search" id="search" name="search_input">
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
      // $searchResult->creatingDrugTable();
      // echo "<hr/>";
      $searchResult->creatingAdvTable();
      echo "<hr/>";
      $searchResult->creatingPharmacareTable();
      echo "<hr/>";
      // $searchResult->creatingHealthCanadaTable();
}
?>
<?php include("./inc_footer.php"); ?>