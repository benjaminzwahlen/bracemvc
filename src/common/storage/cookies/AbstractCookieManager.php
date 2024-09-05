<?php
namespace Benjaminzwahlen\Brace\common\storage\cookies;

use Benjaminzwahlen\Brace\common\Cipher;

abstract class AbstractCookieManager
{

    protected static array $cache = array();

    private static ?string $encryptionKey = null;
    private static ?string $cookieDomain = null;
    private static ?string $cookieSecure = null;
    private static ?string $cookieExpirationTime = null;


    public static function init($encryptionKey, $cookieDomain, $cookieSecure, $cookieExpirationTime)
    {
        AbstractCookieManager::$encryptionKey = $encryptionKey;
        AbstractCookieManager::$cookieDomain = $cookieDomain;
        AbstractCookieManager::$cookieSecure = $cookieSecure;
        AbstractCookieManager::$cookieExpirationTime = $cookieExpirationTime;
    }

    public static function fetch(string $cookieKey): ?AbstractCookie
    {
        if (!isset($_COOKIE[$cookieKey]))
            return null;

        if (!array_key_exists($cookieKey, AbstractCookieManager::$cache)) {
            AbstractCookieManager::$cache[$cookieKey] = unserialize(Cipher::decrypt(
                $_COOKIE[$cookieKey],
                AbstractCookieManager::$encryptionKey
            ));
        }
        return AbstractCookieManager::$cache[$cookieKey];
    }


    public static function store(AbstractCookie $cookie)
    {
        AbstractCookieManager::$cache[$cookie->getKey()] = $cookie;
        $str = Cipher::encrypt(serialize($cookie), AbstractCookieManager::$encryptionKey);
        setcookie(
            $cookie->getKey(),
            $str,
            AbstractCookieManager::$cookieExpirationTime,
            "/",
            AbstractCookieManager::$cookieDomain,
            AbstractCookieManager::$cookieSecure
        );
        $_COOKIE[$cookie->getKey()] = $str;
    }



    public static function destroy(string $cookieKey)
    {
        if (isset($_COOKIE[$cookieKey])) {
            setcookie(
                $cookieKey,
                "",
                time() - 3600,
                "/",
                AbstractCookieManager::$cookieDomain,
                AbstractCookieManager::$cookieSecure
            );
            unset($_COOKIE[$cookieKey]);
            unset(AbstractCookieManager::$cache[$cookieKey]);
        }
    }
}
