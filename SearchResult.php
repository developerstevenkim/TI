<?php
# SearchResult class
final class SearchResult
{
    private $url;
    private $param;
    private $json;
    private $news_arr;
    private $drug_arr;

    function __construct($input) {
        // set all attributes only if the param is from search
        if (isset($input)) {
            $this->param = $input;
            $this->url = "https://dev.tiapp.org/api/v1/api.php?s=$this->param";
            // $this->url = "http://localhost:8080/api/v2/api.php?s=$this->param";
            $response = file_get_contents($this->url);
            $response = utf8_decode($response);
            $this->json = json_decode($response);
            $this->news_arr = $this->json->newsletters;
            $this->drug_arr = $this->json->drugs;
        } else {
            $this->param = "hi";
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

    // to get newsletter as array
    public function getNewsletterArr()
    {
        // attributes list -> letter_num, topic, url
        return $this->news_arr;
    }

    // to get drug as array
    public function getDrugArr()
    {
        // attributes list -> id, code, name, parent_id
        return $this->drug_arr;
    }

    // create newsletter
    public function creatingNewsletterTable()
    {
        $echo_stmt = "<h3>Related Newsletters</h3>";
        if (count($this->news_arr) == 0) {
            $echo_stmt .= "<h4>No related newsletters has been found</h4>";
        } else {
            $echo_stmt .= "<table width='100%' class='table table-striped'>\n";
            $echo_stmt .= "<tr><th>News#</th>".
                "<th>Topic</th>".
                "<th>URL</th>\n";
            foreach($this->news_arr as $news){
                $echo_stmt .= "<tr><td>$news->letter_num</td>";
                $echo_stmt .= "<td>$news->topic</td>";
                $echo_stmt .= "<td>$news->url</td></tr>";
            }
            $echo_stmt .= "</table>\n";
        }
        echo $echo_stmt;
    }

    // create drug table
    public function creatingDrugTable()
    {
        $echo_stmt = "<h3>Related Drugs</h3>";
        if (count($this->drug_arr) == 0) {
            $echo_stmt .= "<h4>No related drugs has been found</h4>";
        } else {
            $echo_stmt .= "<table width='100%' class='table table-striped'>\n";
            $echo_stmt .= "<tr><th>Drug#</th>".
                "<th>Code</th>".
                "<th>Name</th>".
                "<th>Parent_id</th>\n";
            foreach($this->drug_arr as $drug){
                $echo_stmt .= "<tr><td>$drug->id</td>";
                $echo_stmt .= "<td>$drug->code</td>";
                $echo_stmt .= "<td>$drug->name</td>";
                $echo_stmt .= "<td>$drug->parent_id</td></tr>";
            }
            $echo_stmt .= "</table>";
        }
        echo $echo_stmt;
    }
}

?>