<?php

namespace Gridonic\StatamicConsentManager\Tests\Unit;

use Gridonic\StatamicConsentManager\ConsentGroup;
use Gridonic\StatamicConsentManager\Script;
use PHPUnit\Framework\TestCase;

class ConsentGroupTest extends TestCase
{
    public function test_to_json()
    {
        $group = (new ConsentGroup('group_id'))
            ->setRequired(true)
            ->setConsented(false)
            ->addScript(new Script('<script>content</script>', 'head'));

        $json = $group->toJson();

        $this->assertEquals('group_id', $json['id']);
        $this->assertTrue($json['required']);
        $this->assertFalse($json['consented']);
        $this->assertCount(1, $json['scripts']);
    }
}
