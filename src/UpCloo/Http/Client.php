<?php 
class UpCloo_Http_Client
{
    const PUT = 'PUT';
    const GET = 'GET';
    const POST = 'POST';
    const DELETE = "DELETE";
    
    const UPCLOO_USER_AGENT = 'UpCloo-SDK-1.0';
    
    const TIMEOUT = 3;
    
    private $_uri;
    private $_rawData;
    
    /**
     * Define max connection timeout
     * 
     * @var int Max get timeout
     */
    private $_timeout;
    
    public function __construct($uri = false)
    {
        if ($uri) {
            $this->setUri($uri);
        }
        
        $this->setTimeout(self::TIMEOUT);
    }
    
    /**
     * Get default timeout connection
     * 
     * @return number Seconds for connection timeout
     */
    public function getTimeout()
    {
    	return $this->_timeout;
    }
    
    /**
     * Set default connection timeout in seconds
     * 
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
    	$timeout = intval($timeout);
    	$this->_timeout = $timeout;
    }
    
    public function setUri($uri)
    {
        $this->_uri = $uri;
    }
    
    public function getUri()
    {
        return $this->_uri;
    }
    
    public function setRawData($rawData)
    {
        $this->_rawData = $rawData;
    }
    
    public function getRawData()
    {
        return $this->_rawData;
    }
    
    /**
     * Request for a page
     * 
     * @param string $method
     * @return UpCloo_Http_Response
     * 
     * @todo refactor this method completely. 
     */
    public function request($method = null)
    {
        if (!$method) {
            $method = self::GET;
        }
        
        if (!$this->getUri()) {
            throw new UpCloo_Http_Exception('No valid URI has been passed to the client');
        }
        
        $response = new UpCloo_Http_Response();
        $uri = $this->getUri();
        
        if ($method == self::PUT || $method == self::POST || $method == self::DELETE) {
            /* raw post on curl module */
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL,            $uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST,           1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,     $this->getRawData());
            curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);
            curl_setopt($ch, CURLOPT_USERAGENT,      self::UPCLOO_USER_AGENT);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->getTimeout());
            
            $result=curl_exec ($ch);
            $headers = curl_getinfo($ch);
            curl_close($ch);
            
            $status = $headers["http_code"];
            
            $response->setBody($result);
            $response->setStatus($status);
        } else {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            
            $result=curl_exec ($ch);
            $headers = curl_getinfo($ch);
            curl_close($ch);
            
            $status = $headers["http_code"];
            
            $response->setBody($result);
            $response->setStatus($status);
        }
        
        return $response;
    }
}