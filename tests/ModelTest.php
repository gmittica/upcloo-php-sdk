<?php 
/**
 * 
 *
 * Test for model struct
 *
 * @author Walter Dal Mut
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
class ModelTest extends PHPUnit_Framework_TestCase
{
    public function testBaseId()
    {
        $model = new UpCloo_Model_Base(5);
        
        $this->assertEquals(5, $model["id"]);
        
        $model = new UpCloo_Model_Base("testMe");
        
        $this->assertEquals("testMe", $model["id"]);
        
        $model = new UpCloo_Model_Base();
        
        $this->assertNull($model["id"]);
    }
    
    public function testSimpleXml()
    {
        $model = new UpCloo_Model_Base();
        
        $model["title"] = "walter";
        
        $xmlExpected = simplexml_load_string("<model><title><![CDATA[walter]]></title></model>");
        $xmlActual = simplexml_load_string($model->asXml());
        
        $this->assertEquals((string)$xmlExpected->title, (string)$xmlActual->title);
    }
    
    public function testStructXml()
    {
        $model = new UpCloo_Model_Base();
        
        $model["title"] = 'walter';
        $model["tags"] = array("one", "two", "three");
        
        $xmlExpected =simplexml_load_string('<model><title><![CDATA[walter]]></title><tags><element><![CDATA[one]]></element><element><![CDATA[two]]></element><element><![CDATA[three]]></element></tags></model>');
        $xmlActual = simplexml_load_string($model->asXml());

        $this->assertEquals((string)$xmlExpected->title, (string)$xmlActual->title);
        $this->assertEquals((string)$xmlExpected->tags->element[0], (string)$xmlActual->tags->element[0]);
        $this->assertEquals((string)$xmlExpected->tags->element[1], (string)$xmlActual->tags->element[1]);
        $this->assertEquals((string)$xmlExpected->tags->element[3], (string)$xmlActual->tags->element[3]);
    }
    
    public function testXmlValidate()
    {
        $model = new UpCloo_Model_Base();
        
        $model["title"] = "è <> & !/\\";
        
        $expected = "<model><title><![CDATA[è <> & !/\\]]></title></model>";
        $xmlExpected = simplexml_load_string($expected);
        $xmlActual = simplexml_load_string((string)$model);
        $this->assertEquals((string)$xmlExpected->title, (string)$xmlActual->title);
    }
    
    public function testCastString()
    {
        $model = new UpCloo_Model_Base();
        
        $model["title"] = "walter";
        
        $expected = "<model><title><![CDATA[walter]]></title></model>";
        $xmlExpected = simplexml_load_string($expected);
        $xmlActual = simplexml_load_string((string)$model);
        $this->assertEquals((string)$xmlExpected->title, (string)$xmlActual->title);
    }
    
    public function testFromArray()
    {
        $model = array();
        $model["title"] = "walter";
        
        $model = UpCloo_Model_Base::fromArray($model);
        
        $this->assertInstanceOf("UpCloo_Model_Base", $model);

        $this->assertEquals("walter", $model["title"]);
    }
    
    public function testArrayMethods()
    {
        $model = new UpCloo_Model_Base();
        $model["title"] = "walter";
        $model["eg"] = "example";
        
        $this->assertSame(2, count($model));
        
        $model["one"] = "The one element";
        
        $this->assertSame(3, count($model));
        
        $this->assertFalse($model->offsetExists(4));
        
        $model[5] = 'five';
        $this->assertEquals("five", $model[5]);
        
        unset($model[5]);
        $this->assertNull($model[5]);
        
        $model[] = "helloooo";
        $this->assertSame(4, count($model));
        $this->assertSame("helloooo", $model[6]);
    }
}