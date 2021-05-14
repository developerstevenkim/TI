<?php
include("./inc_header.php");
include("./SearchResult.php");

$searchResult = new SearchResult();

$searchResult->creatingNewsletterTable();

echo "<hr>";

$searchResult->creatingDrugTable();


include("./inc_footer.php");
?>
