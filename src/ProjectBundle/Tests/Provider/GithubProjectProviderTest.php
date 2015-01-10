<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Provider;

use OpsCopter\DB\ProjectBundle\Provider\GithubProjectProvider;

class GithubProjectProviderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider validUris
     */
    public function testValidUris($uri, $normalized, $type) {
        $provider = new GithubProjectProvider();
        $this->assertTrue($provider->isValidUri($uri), sprintf('%s URL is valid', $type));
    }

    /**
     * @dataProvider validUris
     */
    public function testNormalizeValidUris($uri, $normalized, $type) {
        $provider = new GithubProjectProvider();
        $this->assertEquals($normalized, $provider->normalizeUri($uri), sprintf('%s URL is normalized', $type));
    }

    public function validUris() {
        return array(
            array('http://github.com/foo/bar', 'https://github.com/foo/bar', 'Basic HTTP'),
            array('http://github.com/foo-bar/baz', 'https://github.com/foo-bar/baz', 'Basic HTTP with special characters'),
            array('https://github.com/foo/bar', 'https://github.com/foo/bar', 'Basic HTTPS'),
            array('git://github.com/foo/bar', 'https://github.com/foo/bar', 'Basic GIT'),
            array('http://baz@github.com/foo/bar', 'https://github.com/foo/bar', 'Basic Auth'),
        );
    }

    /**
     * @dataProvider invalidUris
     */
    public function testInvalidUris($uri, $type) {
        $provider = new GithubProjectProvider();
        $this->assertFalse($provider->isValidUri($uri), sprintf('%s URL is invalid', $type));
    }

    /**
     * @dataProvider invalidUris
     * @expectedException \InvalidArgumentException
     */
    public function testNormalizeInvalidUris($uri) {
        $provider = new GithubProjectProvider();
        $provider->normalizeUri($uri);
    }

    public function invalidUris() {
        return array(
            array('http://google.com/foo/bar', 'Invalid domain'),
            array('ftp://github.com/foo/bar', 'Invalid protocol'),
            array('http://github.com/foo bar/baz', 'Invalid characters'),
        );
    }
}
