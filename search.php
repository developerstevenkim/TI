<?php
include("../inc_header.php");

?>
<h1>Search</h1>
<form class="form-inline my-2 my-lg-0" style="margin:10px" method="POST" action="./search_result.php">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="search" name="search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
</form>

<?php include("../inc_footer.php"); ?>