<?php
namespace Benjaminzwahlen\Brace\common\storage\session;

abstract class AbstractSessionManager
{

    public static function init()
    {
        session_start();
    }


    protected static array $cache = array();


    public static function fetch(string $sessionVarKey): ?AbstractSessionVar
    {
        if (!isset($_SESSION[$sessionVarKey]))
            return null;

        if (!array_key_exists($sessionVarKey, AbstractSessionManager::$cache)) {
            AbstractSessionManager::$cache[$sessionVarKey] =
                $_SESSION[$sessionVarKey];
        }
        return AbstractSessionManager::$cache[$sessionVarKey];
    }

    public static function store(AbstractSessionVar $sessionVar)
    {
        AbstractSessionManager::$cache[$sessionVar->getKey()] = $sessionVar;
        $_SESSION[$sessionVar->getKey()] = $sessionVar;
    }

    public static function destroy(string $sessionVarKey)
    {
        if (isset($_SESSION[$sessionVarKey])) {
            unset($_SESSION[$sessionVarKey]);
            unset(AbstractSessionManager::$cache[$sessionVarKey]);
        }
    }



    /*
    * USER methods
    */
    public static function storeUser(User $user)
    {
        return AbstractSessionManager::store($user);
    }

    public static function fetchUser(): ?User
    {
        return AbstractSessionManager::fetch(User::getKey());
    }

    public static function destroyUser()
    {
        return AbstractSessionManager::destroy(User::getKey());
    }
}
