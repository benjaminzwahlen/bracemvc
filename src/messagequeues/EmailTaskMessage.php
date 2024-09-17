<?php

namespace benjaminzwahlen\bracemvc\messagequeues;

class EmailTaskMessage extends AbstractTaskMessage
{

    public int $emailId;

    public function __construct(string $path_, $emailId_)
    {
        parent::__construct($path_);
        $this->emailId = $emailId_;
    }
}
