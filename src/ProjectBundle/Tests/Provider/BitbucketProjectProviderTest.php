<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Provider;

use OpsCopter\DB\ProjectBundle\Provider\BitbucketProjectProvider;

class BitbucketProjectProviderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider validUris
     */
    public function testValidUris($uri, $normalized, $type) {
        $provider = new BitbucketProjectProvider();
        $this->assertTrue($provider->isValidUri($uri), sprintf('%s URL is valid', $type));
    }

    /**
     * @dataProvider validUris
     */
    public function testNormalizeValidUris($uri, $normalized, $type) {
        $provider = new BitbucketProjectProvider();
        $this->assertEquals($normalized, $provider->normalizeUri($uri), sprintf('%s URL is normalized', $type));
    }

    public function validUris() {
        return array(
            array('http://bitbucket.com/foo/bar', 'https://bitbucket.com/foo/bar', 'basic HTTP'),
            array('https://bitbucket.com/foo/bar', 'https://bitbucket.com/foo/bar', 'basic HTTPS'),
            array('git://bitbucket.com/foo/bar', 'https://bitbucket.com/foo/bar', 'Basic GIT'),
            array('http://user@bitbucket.com/foo/bar', 'https://bitbucket.com/foo/bar', 'Basic Auth'),
            array('http://u5er:pa55word@bitbucket.com/foo/bar', 'https://bitbucket.com/foo/bar', 'Basic Auth'),
            array('git@bitbucket.com:foo/bar.git', 'https://bitbucket.com/foo/bar', 'Basic SSH'),
        );
    }

    /**
     * @dataProvider invalidUris
     */
    public function testInvalidUris($uri, $type) {
        $provider = new BitbucketProjectProvider();
        $this->assertFalse($provider->isValidUri($uri), sprintf('%s URL is invalid', $type));
    }

    /**
     * @dataProvider invalidUris
     * @expectedException \InvalidArgumentException
     */
    public function testNormalizeInvalidUris($uri, $type) {
        $provider = new BitbucketProjectProvider();
        $provider->normalizeUri($uri);
    }

    public function invalidUris() {
        return array(
            array('http://google.com/foo/bar', 'Invalid domain'),
            array('ftp://bitbucket.com/foo/bar', 'Invalid protocol'),
            array('http://bitbucket.com/foo bar/bar', 'Invalid characters'),
            array('http://user:password:bitbucket.com/foo/bar', 'Invalid characters'),
            array('git@user@bitbucket.com:foo/bar.git', 'Basic SSH with invalid username'),
        );
    }
}
