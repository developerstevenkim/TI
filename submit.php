<?php
include("./inc_header.php");
include("./inc_db_params.php");
?>

<h1>Search Result</h1>

<?php
$search = $_GET['s'];
if (isset($search)) {

    mysqli_select_db($conn, $db_name);

    if ($conn !== FALSE) {
        $SQLstring = "SELECT * FROM Drug WHERE NM LIKE '%$search%'";

        // echo "<h1 style='font-size: large;'>";
        // echo "SQL: <mark>$SQLstring </mark>";
        // echo "</h1>";

        // Describe Drug
        // +-------------+--------------+------+-----+---------+-------+
        // | Field       | Type         | Null | Key | Default | Extra |
        // +-------------+--------------+------+-----+---------+-------+
        // | ID          | int(11)      | NO   | PRI | NULL    |       |
        // | CD          | varchar(255) | YES  |     | NULL    |       |
        // | NM          | varchar(255) | YES  |     | NULL    |       |
        // | TYPE        | varchar(255) | YES  |     | NULL    |       |
        // | PARENT_CD   | varchar(255) | YES  |     | NULL    |       |
        // | PARENT_ID   | int(11)      | YES  |     | NULL    |       |
        // | UNIT_OF_MSR | varchar(255) | YES  |     | NULL    |       |
        // +-------------+--------------+------+-----+---------+-------+

        if ($QueryResult = @mysqli_query($conn, $SQLstring)) {
            echo "<table width='100%' class='table table-striped'>\n";
            echo "<tr><th>Drug ID</th>".
                 "<th>CD</th>".
                 "<th>NM</th>".
                 "<th>TYPE</th>".
                 "<th>PARENT_CD</th>".
                 "<th>PARENT_ID</th>".
                 "<th>UNIT_OF_MSR</th>\n";
            while ($Row = mysqli_fetch_array($QueryResult, MYSQLI_NUM)) {
                 echo "<tr><td>{$Row[0]}</td>";
                 echo "<td>{$Row[1]}</td>";
                 echo "<td>{$Row[2]}</td>";
                 echo "<td>{$Row[3]}</td>";
                 echo "<td>{$Row[4]}</td>";
                 echo "<td>{$Row[5]}</td>";
                 echo "<td>{$Row[6]}</td>";
                 echo "</tr>\n";
            };
            echo "</table>\n";
    
            // echo "<p>Your query returned the above "
            //      . mysqli_num_rows($QueryResult)
            //      . " rows and ". mysqli_num_fields($QueryResult)
            //      . " columns.</p>";
    
            mysqli_free_result($QueryResult);
       }
    
    }
    mysqli_close($conn);
}
?>

<br />
<?php include("./inc_footer.php"); ?>