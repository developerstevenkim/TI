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
      <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="advancedSearch">
            <label class="form-check-label" for="inlineRadio1">Advanced Search</label>
      </div>
      <hr/>
</form>

<?php
$input = "";

if(isset($_POST['search'])){ //check if form was submitted
      $input = $_POST['search_input']; //get input text

      $searchResult = new SearchResult($input);
      if (isset($_POST['inlineRadioOptions'])) {
            $searchResult->advancedUrl();
      }
      $message = $searchResult->getMessage();
      echo "<h4 style=text-align:center;>Showing result for
                   <span style='color:red'>$input</span>.";
      echo "<h4 style=text-align:center;> $message </h4>";
      $searchResult->creatingNewsletterTable();
      echo "<hr/>";

      if (isset($_POST['inlineRadioOptions'])) {
            $echo_stmt = '
            <div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">
                  <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingAdv">
                              <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAdv" aria-expanded="false" aria-controls="collapseAdv">
                                          Advisory data from Safer Project
                                    </a>
                              </h4>
                        </div>

                        <div id="collapseAdv" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingAdv">
                              <div class="panel-body">
            ';
            echo $echo_stmt;
            $searchResult->creatingAdvTable();
            $echo_stmt ='                 </div>
                                    </div>
                              </div>';
            echo $echo_stmt;
            echo "<hr/>";
            $echo_stmt = '
                  <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingPharma">
                              <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePharma" aria-expanded="false" aria-controls="collapsePharma">
                                          Pharmacare Data
                                    </a>
                              </h4>
                        </div>

                        <div id="collapsePharma" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingPharma">
                              <div class="panel-body">
            ';
            echo $echo_stmt;
            $searchResult->creatingPharmacareTable();
            $echo_stmt ='                 </div>
                        </div>
                  </div>
            </div>';
            echo $echo_stmt;
            echo "<hr/>";
      }

      
}
?>
<?php include("./inc_footer.php"); ?>