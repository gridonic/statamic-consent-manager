<?php

namespace Gridonic\StatamicConsentManager;

class Script
{
    const APPEND_HEAD = 'head';
    const APPEND_BODY = 'body';

    private $tag;
    private $appendTo;

    public function __construct(string $tag, string $appendTo)
    {
        if (!in_array($appendTo, [self::APPEND_BODY, self::APPEND_HEAD])) {
            throw new \LogicException("appendTo must be 'head' or 'body', '${appendTo}' given");
        }

        $this->tag = $tag;
        $this->appendTo = $appendTo;
    }

    public function parse(): array
    {
        $parsed = [
            'appendTo' => $this->appendTo,
            'src' => $this->parseSource(),
            'type' => $this->parseType(),
            'referrerpolicy' => $this->parseReferrerPolicy(),
            'content' => base64_encode($this->parseContent()),
            'async' => $this->parseAsync(),
            'defer' => $this->parseDefer(),
        ];

        $parsed['id'] = $this->createUniqueId($parsed);

        return $parsed;
    }

    public function getTag()
    {
        return $this->tag;
    }

    private function parseScriptTag()
    {
        preg_match('/^<script[^>]*>/', $this->tag, $matches);

        return $matches[0] ?? null;
    }

    private function parseSource()
    {
        preg_match('/src="([^"]*)"/', $this->parseScriptTag(), $matches);

        return $matches[1] ?? null;
    }

    private function parseType()
    {
        preg_match('/type="([^"]*)"/', $this->parseScriptTag(), $matches);

        return $matches[1] ?? null;
    }

    private function parseReferrerPolicy()
    {
        preg_match('/referrerpolicy="([^"]*)"/', $this->parseScriptTag(), $matches);

        return $matches[0] ?? null;
    }

    private function parseContent()
    {
        preg_match('/^<script[^>]*>(.*)<\/script>$/', $this->tag, $matches);

        return $matches[1] ?? null;
    }

    private function parseAsync(): bool
    {
        return (bool)preg_match('/async/', $this->parseScriptTag());
    }

    private function parseDefer(): bool
    {
        return (bool)preg_match('/defer/', $this->parseScriptTag());
    }

    private function createUniqueId(array $parsed): string
    {
        return md5(
            $parsed['appendTo'] .
            $parsed['src'] .
            $parsed['type'] .
            $parsed['referrerpolicy'] .
            $parsed['content'] .
            $parsed['async'] .
            $parsed['defer']
        );
    }
}
