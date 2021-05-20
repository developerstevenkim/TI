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
    private $pharma_arr;
    private $health_arr;
    private $message;

    function __construct($input) {
        // set all attributes only if the param is from search
        if (isset($input)) {
            $this->param = $input;
            $this->param = rawurlencode($this->param);
            // $this->url = "https://dev.tiapp.org/api/v2/api.php?s=$this->param";
            $this->url = "http://localhost:8080/api/v2/api.php?s=$this->param";
            $response = file_get_contents($this->url);
            $response = utf8_decode($response);
            $this->json = json_decode($response);
            $this->drug_arr = $this->json->drugs;
            $this->news_arr = $this->json->newsletters;
            $this->adv_arr = $this->json->advisories;
            $this->pharma_arr = $this->json->BC_pharmacare;
            $this->health_arr = $this->json->HealthCanada;
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

    // to get pharmacare as array
    public function getPharmaArr()
    {
        // attributes-> id, country, published_date, type, title, risk, url, included_drugs
        return $this->pharma_arr;
    }

    // to get health Canada as array
    public function getHealthArr()
    {
        // attributes-> id, country, published_date, type, title, risk, url, included_drugs
        return $this->health_arr;
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
            
            while(($element = current($table_column)) !== FALSE) {
                $current_column = key($table_column);
                array_push($column_names, key($table_column));
                $current_column = strtoupper($current_column);
                if ($current_column == "URL"
                 || $current_column == "SPECIAL AUTHORITY LINK"
                 || $current_column == "INCLUDED_DRUGS"
                 || $current_column == "MAX_DAYS_SUPPLY"
                 || $current_column == "HISTORY_DATE") {
                    $echo_stmt .= "<th>$current_column</th>";
                } else {
                    $echo_stmt .= "<th>$current_column</th>";
                }
                next($table_column);
            }
            $echo_stmt .= "</tr>";

            // add all lines
            foreach($table as $item){
                $echo_stmt .= "<tr>";
                foreach($column_names as $column) {
                    $current_column = $item->{$column};
                    if ($column == "url" || $column == "Special Authority Link") {
                        $echo_stmt .= "<td><a href='$current_column' title='$current_column' target='_blank'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' class='bi bi-link' viewBox='0 0 16 16'>
                        <path d='M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z'/>
                        <path d='M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z'/>
                        </svg>
                        </a></td>";
                    } else {
                    $echo_stmt .= "<td>$current_column</td>";
                    }
                    
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
        $this->{$str_table}->$CA = (array)$this->{$str_table}->$CA;
        $this->{$str_table}->$US = (array)$this->{$str_table}->$US;
        $this->{$str_table}->$UK = (array)$this->{$str_table}->$UK;
        $this->{$str_table}->$AUS = (array)$this->{$str_table}->$AUS;
        $combined_array = array_merge($this->{$str_table}->$CA, $this->{$str_table}->$US, $this->{$str_table}->$UK, $this->{$str_table}->$AUS);
        $this->{$str_table} = $combined_array;
        return $this->creatingTable($item, $str_table);
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

    // create health canada
    public function creatingHealthCanadaTable()
    {
        echo $this->creatingTable("Health Canada", "health_arr");
    }

}

?>