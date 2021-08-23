<?php

namespace Gridonic\StatamicConsentManager\Tests\Unit;

use Gridonic\StatamicConsentManager\Script;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function test_fails_when_giving_invalid_append_to()
    {
        $this->expectException(\LogicException::class);

        new Script('some script tag...', 'footer');
    }

    public function test_parse_script_with_src()
    {
        $parsed = $this->parseScript('<script async src="/src" defer></script>', 'head');

        $this->assertEquals('head', $parsed['appendTo']);
        $this->assertEquals('/src', $parsed['src']);
        $this->assertTrue($parsed['defer']);
        $this->assertTrue($parsed['async']);
        $this->assertNull($parsed['content']);
        $this->assertNull($parsed['referrerpolicy']);
        $this->assertNull($parsed['type']);
    }

    public function test_parse_inline_script()
    {
        $scriptContent = '<script defer async>content referrerpolicy</script>';
        $parsed = $this->parseScript("<script>$scriptContent</script>", 'body');

        $this->assertEquals('body', $parsed['appendTo']);
        $this->assertNull($parsed['src']);
        $this->assertFalse($parsed['defer']);
        $this->assertFalse($parsed['async']);
        $this->assertEquals($scriptContent, base64_decode($parsed['content']));
        $this->assertNull($parsed['referrerpolicy']);
        $this->assertNull($parsed['type']);
    }

    public function test_parse_script_with_referrer_and_type()
    {
        $parsed = $this->parseScript(
            '<script src="/src" referrerpolicy="no-referrer" type="text/javascript"></script>',
            'head'
        );

        $this->assertEquals('head', $parsed['appendTo']);
        $this->assertEquals('/src', $parsed['src']);
        $this->assertFalse($parsed['defer']);
        $this->assertFalse($parsed['async']);
        $this->assertNull($parsed['content']);
        $this->assertEquals('no-referrer', $parsed['referrerpolicy']);
        $this->assertEquals('text/javascript', $parsed['type']);
    }

    public function test_create_unique_ids_for_different_scripts()
    {
        $script1 = new Script('<script>content</script>', 'head');
        $script2 = new Script('<script async>content</script>', 'body');

        $this->assertNotEquals($script1->parse()['id'], $script2->parse()['id']);
    }

    public function test_create_same_id_for_identical_scripts()
    {
        $script1 = new Script('<script async defer src="/src">content</script>', 'head');
        $script2 = new Script('<script async defer src="/src">content</script>', 'head');

        $this->assertEquals($script1->parse()['id'], $script2->parse()['id']);
    }

    private function parseScript($tag, $appendTo): array
    {
        return (new Script($tag, $appendTo))->parse();
    }
}
