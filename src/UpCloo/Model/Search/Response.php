<?php 
class UpCloo_Model_Search_Response
{
    private $_count;
    private $_start;
    
    private $_docs;
    private $_facets;
    private $_ranges;
    private $_suggests;
    
    /**
     *Generate a model starting from an XML structure
     * 
     * @param SimpleXMLElement $root
     * @return UpCloo_Model_Search_Response
     * @throws UpCloo_Model_Exception In case of errors
     */
    public static function fromResponse($root)
    {
        if (@count($root->message) > 0) {
            throw new UpCloo_Model_Exception((string)$root->message, (int)$root->code);
        }
        
        $model = new UpCloo_Model_Search_Response();
        
        $model->_count = 0;
        $model->_start = 0;
        $model->_docs = array();
        $model->_facets = array();
        $model->_ranges = array();
        $model->_suggests = array();
        
        if ($root != null) {
            $attr = $root->docs->attributes();
            $model->_count = (int)$attr["count"];
            $model->_start = (int)$attr["start"];
            
            $model->_docs = array();
            foreach ($root->docs->doc as $doc) {
                $attr = $doc->attributes();
            
                $m = array();
                $m["id"] = (string)$attr["id"];
                foreach ($doc as $key => $value) {
                    $m[$key] = (string)$value;
                }
                
                $model->_docs[] = $m;
            }
            
            foreach ($root->suggestions->suggest as $suggest) {
                $attr = $suggest->attributes();
                $name = (string)$attr["name"];
                
                foreach ($suggest->proposal as $proposal) {
                    $model->_suggests[$name][] = (string)$proposal;
                }
            }
            
            if (property_exists($root->facets, "facet")) {
                foreach ($root->facets->facet as $element) {
                    $attr = $element->attributes();
                    $name = (string)$attr["name"];
                    $model->_facets[$name] = array();
                    foreach ($element->element as $el) {
                        $attr = $el->attributes();
                        $count = (int)$attr["count"];

                        $model->_facets[$name][(string)$el] = $count;
                    }
                }
            }
            
            if (property_exists($root->facets->ranges, "range")) {
                foreach ($root->facets->ranges->range as $range) {
                    $attr = $range->attributes();
                    $name = (string)$attr["name"];
                    $model->_ranges[$name] = array();
                    foreach ($range->element as $r) {
                        $attr = $r->attributes();
                        $count = (int)$attr["count"];
                        
                        $model->_ranges[$name][(string)$r] = $count;
                    }
                }
            }
        }
        
        return $model;
    }
    
    public function getSuggestions()
    {
        return $this->_suggests;
    }
    
    /**
     * Get number of objects involved in
     * this search query.
     * 
     * @return int Number of objects (remotely)
     */
    public function getCount()
    {
        return $this->_count;
    }
    
    /**
     * Where you are (position)
     * 
     * @return int your index position
     */
    public function getStart()
    {
        return $this->_start;
    }
    
    /**
     * Docs found
     * 
     * @return UpCloo_Model_Base A simple data model
     */
    public function getDocs()
    {
        return $this->_docs;
    }
    
    public function getFacets()
    {
        return $this->_facets;
    }
    
    public function getRanges()
    {
        return $this->_ranges;
    }
}