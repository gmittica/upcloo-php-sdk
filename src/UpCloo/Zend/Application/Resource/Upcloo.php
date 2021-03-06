<?php 
/**
 * 
 * The UpCloo Resource for Zend Bootstapper
 *
 * @author Walter Dal Mut
 * @package UpCloo_Zend_Application_Resource
 * @license MIT
 *
 * Copyright (C) 2011 Walter Dal Mut, Gabriele Mittica
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class UpCloo_Zend_Application_Resource_UpCloo
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Init the UpCloo library into a Zend App
     * 
     * (non-PHPdoc)
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init()
    {
        $options = $this->getOptions();
        
        if (
            !array_key_exists('username', $options) || 
            !array_key_exists('sitekey', $options) || 
            !array_key_exists('password', $options)) {
            throw new UpCloo_Zend_Application_Resource_Exception("You must set the username, password and sitekey");
        }
         
        //Get the instance
        $instance = UpCloo_Manager::getInstance();
        $instance->setCredential(
            $options["username"],
            $options["sitekey"],
            $options["password"]
        );
        
        //Override the default client
        if (array_key_exists("client", $options)) {
            $classname = "UpCloo_Client_{$options["client"]}";
            
            $client = null;
            if (class_exists($classname)) {
                $client = new $classname;
            } else {
                throw new RuntimeException("Class with name {$classname} not exists. Unable to boot UpCloo.");
            }
            
            $instance->setClient($client);
        }
        
        //Attach the storage
        if (array_key_exists("storage", $options)) {
            $storage = trim($options["storage"]);
            
            $instance->useStorage($storage);
        }
        
        return $instance;
    }
}