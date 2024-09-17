<?php

namespace benjaminzwahlen\bracemvc\messagequeues;

abstract class AbstractTaskMessage
{
    public string $path;

    public function __construct(string $path_)
    {
        $this->path = $path_;
    }
}
