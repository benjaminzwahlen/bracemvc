<?php

use benjaminzwahlen\bracemvc\Router;

use function PHPUnit\Framework\assertEquals;



class RouterTest extends \PHPUnit\Framework\TestCase
{

    public function testRouterToken()
    {
        assertEquals(true, Router::pathMatches("","")["matched"]);

        assertEquals(true, Router::pathMatches("/test","/test/")["matched"]);

        assertEquals(true, Router::pathMatches("/test/bla","/test/bla")["matched"]);

        assertEquals(false, Router::pathMatches("/test","/test/123")["matched"]);

        $match = Router::pathMatches("/test/123","/test/{token}");
        assertEquals(true, $match["matched"]);
        assertEquals("123", $match["tokenArray"]["token"]);



        $match = Router::pathMatches("/test/123/asdasd/abc","/test/{token}/asdasd/{id}");
        assertEquals(true, $match["matched"]);
        assertEquals("123", $match["tokenArray"]["token"]);
        assertEquals("abc", $match["tokenArray"]["id"]);




    }
}
