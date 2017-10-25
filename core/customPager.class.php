<?php

class CustomPager {
    private $num_pages;
    private $page;
    private $max_page;
    private $num_results;

    public function getNumPages(){
        return $this->num_pages;
    }
    
    public function setNumPages($num_pages){
        $this->num_pages = $num_pages;
    }
    
    public function getPage(){
        return $this->page;
    }
    
    public function setPage($page){
        $this->page = $page;
    }
    
    public function getMaxPerPage(){
        return $this->max_page;
    }
    
    public function setMaxPerPage($max_page){
        $this->max_page = $max_page;
    }
    
    public function getNumResults(){
        return $this->num_results;
    }
    
    public function setNumResults($num_results){
        $this->num_results = $num_results;
    }    
    
}
    
    
