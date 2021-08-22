<?php

namespace Gridonic\StatamicConsentManager;

class ConsentGroup
{
    private $id;
    private $required = false;
    private $consented = false;
    private $scripts = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'required' => $this->required,
            'consented' => $this->consented,
            'scripts' => collect($this->scripts)->map(function (Script $script) {
                return $script->parse();
            })->values()->all(),
        ];
    }

    public function addScript(Script $script): ConsentGroup
    {
        $this->scripts[] = $script;

        return $this;
    }

    public function setScripts(array $scripts): ConsentGroup
    {
        $this->scripts = $scripts;

        return $this;
    }

    public function setRequired(bool $required): ConsentGroup
    {
        $this->required = $required;

        return $this;
    }

    public function setConsented(bool $consented): ConsentGroup
    {
        $this->consented = $consented;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isConsented(): bool
    {
        return $this->consented;
    }

    public function getScripts(): array
    {
        return $this->scripts;
    }
}
