<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\enums\Environment;

interface RouterInterface
{
    public function match(string $path, string $methodString, bool $isAjax_, Environment $env): ?Route;
}
