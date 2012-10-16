<?php

namespace FSC\HateoasBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

class ControllerTest extends TestCase
{
    public function testGetPostXml()
    {
        $client = $this->createClient();
        $client->request('GET', '/api/posts/2?_format=xml');

        $response = $client->getResponse(); /**  */

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
'<?xml version="1.0" encoding="UTF-8"?>
<post id="2">
  <title><![CDATA[How to create awesome symfony2 application]]></title>
  <link rel="self" href="http://localhost/api/posts/2"/>
</post>
',
        $response->getContent());
    }

    public function testGetUserPostsXml()
    {
        $client = $this->createClient();
        $client->request('GET', '/api/users/42/posts?_format=xml');

        $response = $client->getResponse(); /**  */

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<result page="1" limit="1" total="2">
  <link rel="self" href="http://localhost/api/users/42/posts?page=1&amp;limit=1"/>
  <link rel="first" href="http://localhost/api/users/42/posts?page=1&amp;limit=1"/>
  <link rel="last" href="http://localhost/api/users/42/posts?page=2&amp;limit=1"/>
  <link rel="next" href="http://localhost/api/users/42/posts?page=2&amp;limit=1"/>
  <entry id="2">
    <title><![CDATA[How to create awesome symfony2 application]]></title>
    <link rel="self" href="http://localhost/api/posts/2"/>
  </entry>
</result>
',
            $response->getContent());
    }
}