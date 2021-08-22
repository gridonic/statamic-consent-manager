<?php

namespace Gridonic\StatamicConsentManager;

class ConsentManager
{
    const STORAGE_LOCAL = 'local';
    const STORAGE_SESSION = 'session';

    private $storage = self::STORAGE_LOCAL;
    /** @var ConsentGroup[] */
    private $groups = [];

    private function __construct(array $groups, string $storage)
    {
        if (!in_array($storage, [self::STORAGE_LOCAL, self::STORAGE_SESSION])) {
            throw new \LogicException("Storage must either be 'local' or 'session', '${storage}' given");
        }
        $this->groups = $groups;
        $this->storage = $storage;
    }

    public static function fromConfig(array $config): ConsentManager {
        $groups = collect($config['groups'] ?? [])->map(function ($group, $id) {
            $scripts = collect($group['scripts'] ?? [])->map(function($script) {
                $appendTo = $script['appendTo'] ?? Script::APPEND_HEAD;
                $environments = $script['environments'] ?? [];
                return new Script($script['tag'], $appendTo, $environments);
            })->all();

            return (new ConsentGroup($id))
                ->setRequired($group['required'] ?? false)
                ->setConsented($group['consented'] ?? false)
                ->setScripts($scripts);
        })->all();

        $storage = $config['storage'] ?? self::STORAGE_LOCAL;

        return new ConsentManager($groups, $storage);
    }

    public function toJson(): array
    {
        return collect($this->groups)->map(function (ConsentGroup $group) {
            return $group->toJson();
        })->values()->all();
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getStorage(): string
    {
        return $this->storage;
    }
}
