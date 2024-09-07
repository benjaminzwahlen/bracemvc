<?php

namespace benjaminzwahlen\bracemvc\interceptors;

use benjaminzwahlen\bracemvc\Request;

interface InterceptorInterface
{
    public function intercept(Request $request);
}
