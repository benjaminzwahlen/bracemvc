<?php
namespace Benjaminzwahlen\Brace\common\storage\session;

class User implements AbstractSessionVar
{
    public bool $isLoggedIn = false;
    public bool $isAdmin = false;
    public string $emailAddress;
    public string $userName;
    public int $id = 0;
    public string $remoteAddr;
    public string $sessionId;


    public function __construct($id = 0)
    {
        $this->id = $id;
        if ($id != 0)
            $this->isLoggedIn = true;
    }


    public static function getKey(): string
    {
        return "user";
    }
}
