<?php

namespace Gridonic\StatamicConsentManager\Tags;

use Gridonic\StatamicConsentManager\ConsentGroup;
use Gridonic\StatamicConsentManager\ConsentManager;
use Statamic\Tags\Tags;

class ConsentManagerTags extends Tags
{
    private $consentManager;

    protected static $handle = 'consent_manager';

    public function __construct(ConsentManager $consentManager)
    {
        $this->consentManager = $consentManager;
    }

    public function index()
    {
        return view('statamic_consent_manager::js_minified', [
            'groups' => json_encode($this->consentManager->toJson()),
            'storage_type' => $this->consentManager->getStorage(),
        ]);
    }

    public function groups()
    {
        return $this->parseLoop($this->consentManager->toJson());
    }
}
