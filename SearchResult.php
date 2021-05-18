<?php
# SearchResult class
final class SearchResult
{
    private $url;
    private $param;
    private $json;
    private $drug_arr;
    private $news_arr;
    private $adv_arr;
    private $noc_arr;
    private $pharma_arr;
    private $message;

    function __construct($input) {
        // set all attributes only if the param is from search
        if (isset($input)) {
            $this->param = $input;
            // $this->url = "https://dev.tiapp.org/api/v2/api.php?s=$this->param";
            $this->url = "http://dev.tiapp.org/api/v2/api.php?s=$this->param";
            $response = file_get_contents($this->url);
            $response = utf8_decode($response);
            $this->json = json_decode($response);
            $this->drug_arr = $this->json->drugs;
            $this->news_arr = $this->json->newsletters;
            $this->adv_arr = $this->json->advisories;
            $this->pharma_arr = $this->json->pharmacare;
            $this->message = $this->json->status->message;
        } else {
            $this->param = "null";
        }
    }

    // test purpose
    public static function testing()
    {
        return "test";
    }
    // to get parameter passed in
    public function getParam()
    {
        return $this->param;
    }

    // to get parameter passed in
    public function getParam2()
    {
        return $this->param;
    }

    // to get json data
    public function getJson()
    {
        return $this->json;
    }

    // to get drug as array
    public function getDrugArr()
    {
        // attributes -> id, code, name, type, parent_id, parent_cd
        return $this->drug_arr;
    }

    // to get newsletter as array
    public function getNewsletterArr()
    {
        // attributes -> letter_num, topic, url
        return $this->news_arr;
    }

    // to get advisory as array
    public function getAdvisoryArr()
    {
        // attributes-> id, country, published_date, type, title, risk, url, included_drugs
        return $this->adv_arr;
    }

    // to get NOC as array
    public function getNocArr()
    {
        // attributes-> drug_cd, noc_num, noc_date, noc_act_stat, noc_eng_reason
        return $this->noc_arr;
    }

    // to get pharmacare as array
    public function getPharmaArr()
    {
        // attributes-> id, country, published_date, type, title, risk, url, included_drugs
        return $this->pharma_arr;
    }

    // to get message
    public function getMessage()
    {
        return $this->message;
    }

    public function creatingTable($item, $str_table) {
        $table_column = $this->{$str_table}[0];
        $table = $this->{$str_table};
        $echo_stmt = "";
        $echo_stmt = "<h3>Related $item</h3>";
        if (count($table) == 0 && $this->param != "null") {
            $echo_stmt .= "<h4>No related $item has been found</h4>";
            return $echo_stmt;
        } else {
            $echo_stmt .= "<table width='100%' class='table table-striped'>\n";

            $column_names = array();
            $echo_stmt .= "<tr>";
            while($element = current($table_column)) {
                $current_column = key($table_column);
                array_push($column_names, key($table_column));
                $current_column = strtoupper($current_column);
                $echo_stmt .= "<th>$current_column</th>";
                next($table_column);
            }
            $echo_stmt .= "</tr>";

            // add all lines
            foreach($table as $item){
                $echo_stmt .= "<tr>";
                foreach($column_names as $column) {
                    $current_column = $item->{$column};
                    $echo_stmt .= "<td>$current_column</td>";
                }
                $echo_stmt .= "</tr>";
            }
            $echo_stmt .= "</table>";
        }
        return $echo_stmt;
    }

    public function creatingAdvisoryTable($item, $str_table) {
        $US = "US";
        $UK = "UK";
        $CA = "CA";
        $AUS = "AUS";
        echo gettype($this);
        echo gettype($this->{$str_table});
        echo gettype($this->{$str_table}->$UK);

        $echo_stmt = "";
        $echo_stmt = "<h3>Related $item</h3>";
        if ($this->param == "null") {
            $echo_stmt .= "<h4>No related $item has been found</h4>";
            return $echo_stmt;
        } 
        else {
            $echo_stmt .= "<table width='100%' class='table table-striped'>\n";
            foreach($this->{$str_table} as $item){
                $echo_stmt .= "<tr>";
                $echo_stmt .= "<td>$item</td>";
                $echo_stmt .= "</tr>";
            }
            $echo_stmt .= "</table>";
        }

        return $echo_stmt;



        // echo gettype($this->{$str_table}->$UK);
        // echo gettype($this->{$str_table}->$UK);
        // $table_column = $this->{$str_table}[0];
        // $table = $this->{$str_table};
        // $echo_stmt = "";
        // $echo_stmt = "<h3>Related $item</h3>";
        // if (count($table) == 0 && $this->param != "null") {
        //     $echo_stmt .= "<h4>No related $item has been found</h4>";
        //     return $echo_stmt;
        // } else {
        //     $echo_stmt .= "<table width='100%' class='table table-striped'>\n";

        //     $column_names = array();
        //     $echo_stmt .= "<tr>";
        //     while($element = current($table_column)) {
        //         $current_column = key($table_column);
        //         array_push($column_names, key($table_column));
        //         $current_column = strtoupper($current_column);
        //         $echo_stmt .= "<th>$current_column</th>";
        //         next($table_column);
        //     }
        //     $echo_stmt .= "</tr>";

        // return $echo_stmt;
    }

    // create drug table
    public function creatingDrugTable() {
        echo $this->creatingTable("Drugs", "drug_arr");
    }

    // create newsletter
    public function creatingNewsletterTable()
    {
        echo $this->creatingTable("Newsletters", "news_arr");
    }

    // create newsletter
    public function creatingAdvTable()
    {
        // Advisory data format is changed from Array to Object ["US", "CA", "UK", "AUS"]
        echo $this->creatingAdvisoryTable("Advisories", "adv_arr");
    }

    // create pharmacare
    public function creatingPharmacareTable()
    {
        echo $this->creatingTable("Pharmacare data", "pharma_arr");
    }
}

?>