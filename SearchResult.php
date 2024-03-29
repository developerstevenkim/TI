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
    private $message;

    function __construct($input) {
        #===============================================
        # Set SearchResult member variables by its URL generated with input parameter
        # if instance is initialized with one parameter, it will find the right url and call setAPI function
        # to set all values for its member variables.
        # comment current url and uncomment localhost to test locally
        #===============================================
        if (isset($input)) {
            $this->param = $input;
            $this->param = rawurlencode($this->param);
            $this->url = "https://dev.tiapp.org/api/v1/api.php?s=$this->param";
            // $this->url = "http://localhost:8080/api/v1/api.php?s=$this->param";
            $this->setAPI($this->url);
            
        } else {
            $this->param = "null";
        }
    }

    #===============================================
    # Set api's attributes by its URL
    #===============================================
    public function setAPI($url)
    {
        $response = file_get_contents($url);
        $response = utf8_decode($response);
        $this->json = json_decode($response);
        $this->drug_arr = $this->json->drugs;
        $this->news_arr = $this->json->newsletters;
        $this->adv_arr = $this->json->advisories;
        $this->pharma_arr = $this->json->BC_pharmacare;
        $this->message = $this->json->status->message;
    }

    #===============================================
    # to get json data
    #===============================================
    public function getJson()
    {
        return $this->json;
    }

    #===============================================
    # to get drug data
    # attributes -> code, name, type, parent_cd
    #===============================================
    public function getDrugArr()
    {
        return $this->drug_arr;
    }

    #===============================================
    # to get newsletter as array
    # attributes -> letter_num, topic, url
    #===============================================
    public function getNewsletterArr()
    {
        return $this->news_arr;
    }

    #===============================================
    # to get advisory as array
    # attributes-> published_date, country, type, title, risk, included_drugs, url
    #===============================================
    public function getAdvisoryArr()
    {
        return $this->adv_arr;
    }

    #===============================================
    # to get pharmacare as array
    # attributes-> din, brand_name, drug_name, plans, max_price, lca_price, max_days_supply, Special Authority Link, istory_date
    #===============================================
    public function getPharmaArr()
    {
        return $this->pharma_arr;
    }
    
    #===============================================
    # to get message
    #===============================================
    public function getMessage()
    {
        return $this->message;
    }

    #===============================================
    # to get URL
    #===============================================
    public function getUrl()
    {
        return $this->url;
    }

    #===============================================
    # to set URL to display more information
    #===============================================
    public function advancedUrl()
    {
        $this->url .= "&ae&p";
        $this->setAPI($this->url);
    }

    #===============================================
    # to create table from user input
    # this function can be used only if second parameter($str_table) contains directly key and value itself
    # parameter : $item      -> String.
    #                           Table's title
    #             $str_table -> String.
    #                           The name of array attributes
    # return : echo statement which contains all the logics to generate table
    #===============================================
    public function creatingTable($item, $str_table) {
        // contains column names
        $table_column = $this->{$str_table}[0];
        $table = $this->{$str_table};

        $echo_stmt = "";

        // if array contains 0 element or parameter is not given, display nothing and return echo statement
        if (count($table) == 0 && $this->param != "null") {
            $echo_stmt .= "<h4>No related $item ";
            $echo_stmt .= substr($item, -1) == "s" ? "have" : "has";
            $echo_stmt .= " been found</h4>";
            return $echo_stmt;
        } else {
            $echo_stmt .= "<table class='table table-striped w-auto'>\n";
            $column_names = array();
            $echo_stmt .= "<tr>";
            
            while(($element = current($table_column)) !== FALSE) {
                $current_column = key($table_column);
                array_push($column_names, key($table_column));
                $echo_stmt .= "<th>$current_column</th>";
                next($table_column);
            }
            $echo_stmt .= "</tr>";

            // add all lines
            foreach($table as $item){
                $echo_stmt .= "<tr>";
                foreach($column_names as $column) {
                    $current_column = $item->{$column};
                    if ($column == "URL" || ($column == "Special Authority Link" && $current_column != "")) {
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

    #===============================================
    # to create advisory table from user input
    # the structure of advisory table is different because array contains subarray(US, UK, CA, AUS)
    # parameter : $item      -> String.
    #                           Table's title
    #             $str_table -> String.
    #                           The name of array attributes
    # return : echo statement which contains all the logics to generate advisory table
    #===============================================
    public function creatingAdvisoryTable($item, $str_table) {
        // merge four arrays into one combined array
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
        $table = $this->{$str_table};
        $echo_stmt = "";

        // if array contains 0 element or parameter is not given, display nothing and return echo statement
        if (count($table) == 0 && $this->param != "null") {
            $echo_stmt .= "<h4>No related $item ";
            $echo_stmt .= substr($item, -1) == "s" ? "have" : "has";
            $echo_stmt .= " been found</h4>";
            return $echo_stmt;
        } else {
            // Array ( [0] => published_date [1] => country [2] => title [3] => risk [4] => included_drugs [5] => url )
            $publish = "published_date";
            $country = "country";
            $title = "title";
            $risk = "risk";
            $included_drugs = "included_drugs";
            $url = "url";
            $_id = 0;

            // add all lines
            $echo_stmt .= '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
            foreach ($table as $item) {
                // each advisory should have different id value to make it expandable
                $echo_stmt .= '<div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                <h5>
                                <a style="display: inline; vertical-align: middle;" role="button" data-toggle="collapse" data-parent="#accordion" href="#'.$_id.'" aria-expanded="false" aria-controls="collapseOne">
                                ';
                $echo_stmt .= $item->$title . "</a>&nbsp;&nbsp;";
                $echo_stmt .= "<a style='display: inline; vertical-align: middle;' href='{$item->$url}' title='{$item->$url}' target='_blank'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' class='bi bi-link float-right' viewBox='0 0 16 16'>
                        <path d='M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z'/>
                        <path d='M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z'/>
                        </svg>
                        </a>";
                $echo_stmt .= '</h5></div>
                                <div id='.$_id.' class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">';
                // increment id value by 1
                $_id = $_id + 1;
                $echo_stmt .= "</li><li><strong>";
                $echo_stmt .= $item->$risk;
                $echo_stmt .= "</strong>";
                $echo_stmt .= "</li><li>";
                $echo_stmt .= $item->$country;
                // some advisory has 'included_drugs' column. If so, display it
                if ($item->$included_drugs != "") {
                    $echo_stmt .= "</li><li>Included drugs: ";
                    $echo_stmt .= $item->$included_drugs;
                }
                $echo_stmt .= "<li>Published in ";
                $echo_stmt .= $item->$publish;
                $echo_stmt .= "</li></div></div></div>";
            }
            $echo_stmt .= "</div>";
        }
    return $echo_stmt;
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

    // create pharmacare data
    public function creatingPharmacareTable()
    {
        echo $this->creatingTable("Pharmacare data", "pharma_arr");
    }
}

?>
