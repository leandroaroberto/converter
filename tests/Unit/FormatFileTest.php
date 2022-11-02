<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Import;
use App\Services\FormatFile;
use App\Services\UrlInfo;

class FormatFileTest extends TestCase
{

    private $hotels;

    public function setup()
    {
        parent::setup();
        $this->hotels = factory(\App\Import::class,10)->make();
    }

    /**
     * test if after FormatName check, return is not empty.
     *
     * @return void
     */
    public function testNameIsNotEmpty()
    {  
        foreach($this->hotels as $hotel)
        {
            $name = FormatFile::formatName($hotel->name);            
            $this->assertNotEmpty($name);            
        }                 
    }

    /**
     * test if non-ascii names is considered after FormatName
     * 
     *
     * @return void
     */
    public function testNameNotAscii()
    { 
        $nonAsciiChars = ['£','¥','€','©','®','™','†','‡','§','¶','°','·','…','–','—','±','×','÷','¼','⅓','½','⅔','¾','μ','π','←','↑','→','↓','↔','↵','⇐','⇑','⇒','⇓','⇔','♠','♣','♥','♦','⚽','',''];
        foreach($nonAsciiChars as $ascii)
        {
            $this->assertEquals('0', FormatFile::formatName($ascii));
        }        
    }

    /* test if stars is valid: >=0 and <=5
     *
     * @return void
     */
    public function testIfStarsIsValid()
    {   
        foreach($this->hotels as $hotel)
        {  
            if($hotel->stars >=0 && $hotel->stars <=5)
            {
                $this->assertNotEquals(-1, FormatFile::formatStars($hotel->stars));   
            }
            else {
                $this->assertEquals(-1, FormatFile::formatStars($hotel->stars));
            }
        } 
    }

     /* test if stars is numeric
     *
     * @return void
     */
    public function testIfStarsIsNumeric()
    {   
        foreach($this->hotels as $hotel)
        {   
            $this->assertEquals(-1, FormatFile::formatStars($hotel->name));
        }        
    }


     /* test if url is valid
     *
     * @return void
     */
    public function testIfUrlIsValid()
    {           
        $urlsToCheck = array (
            (object) [
                "url"=> "http://www.paucek.com/search.htm",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.farina.org/blog/categories/tags/about.html",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.garden.com/list/home.html",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://reichmann.de/main/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.rousseau.fr/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://the.com/register/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://vaillant.com/list/app/faq/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.begue.fr/search/register/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://hotel.com/index/",
                "isValid"=> false,
            ],
            
            (object) [
                "url"=> "http://premier.de/about/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "ttp://ondricka.com/search/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://the.org/category/",
                "isValid"=> true,
            ],
            (object) [
                "url"=> "http://www.martini.net/main.asp",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://schneider.fr/index/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.premier.com/login/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.martinelli.net/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.jockel.de/explore/tags/main/category.asp",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://www.lockman.info/main/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://the.biz/",
                "isValid"=> true,
            ],
            //this one takes long time to execute
            // (object) [
            //     "url"=> "http://www.the.net/blog/category/tag/author/",
            //     "isValid"=> false,
            //],
            (object) [
                "url"=> "http://www.hotel.com/index/",
                "isValid"=> false,
            ],
            (object) [
                "url"=> "http://benard.com/",
                "isValid"=> true,
            ],
            (object) [
                "url"=> "https://the-red.de/",
                "isValid"=> true,
            ],
        );

        //to check url headers
        //$uri_info = new UrlInfo($urlsToCheck[11]->url);
        //print_r($uri_info->getHeader());
        

        foreach ($urlsToCheck as $urls) {
            $this->assertEquals($urls->isValid, FormatFile::checkUrl($urls->url));
        }
    }



}