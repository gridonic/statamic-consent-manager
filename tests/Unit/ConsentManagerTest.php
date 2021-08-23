<?php

namespace Gridonic\StatamicConsentManager\Tests\Unit;

use Gridonic\StatamicConsentManager\ConsentManager;
use PHPUnit\Framework\TestCase;

class ConsentManagerTest extends TestCase
{
    public function test_fails_when_given_invalid_storage_type()
    {
        $this->expectException(\LogicException::class);

        ConsentManager::fromConfig([
            'storage' => 'invalid_storage',
            'groups' => []
        ]);
    }

    public function test_instance_from_config()
    {
        $manager = ConsentManager::fromConfig([
            'storage' => 'local',
            'groups' => [
                'necessary' => [
                    'required' => true,
                    'consented' => true,
                    'scripts' => [
                        [
                            'tag' => '<script>alert("hello");</script>',
                            'appendTo' => 'body',
                        ],
                    ],
                ],
                'marketing' => [
                    'required' => false,
                    'consented' => false,
                    'scripts' => [],
                ],
            ],
        ]);

        $this->assertEquals('local', $manager->getStorage());
        $this->assertCount(2, $manager->getGroups());

        $necessaryGroup = $manager->getGroup('necessary');
        $this->assertTrue($necessaryGroup->isRequired());
        $this->assertTrue($necessaryGroup->isConsented());
        $this->assertCount(1, $necessaryGroup->getScripts());
        $this->assertEquals('body', $necessaryGroup->getScripts()[0]->getAppendTo());
        $this->assertEquals('<script>alert("hello");</script>', $necessaryGroup->getScripts()[0]->getTag());

        $marketingGroup = $manager->getGroup('marketing');
        $this->assertFalse($marketingGroup->isRequired());
        $this->assertFalse($marketingGroup->isConsented());
        $this->assertCount(0, $marketingGroup->getScripts());
    }
}
