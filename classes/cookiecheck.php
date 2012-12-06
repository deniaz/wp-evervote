<?php

class CookieCheck implements VoteCheck
{

    private $postID;

    const COOKIE_PREFIX = '__evervoted-server-';

    const NEW_VOTE = 10;

    const ALREADY_VOTED = 20;

    public function __construct($postID)
    {
        $this->postID = $postID;
    }

    public function runCheck()
    {
        $key = self::COOKIE_PREFIX;

        $key = md5($key . $this->postID);

        if (isset($_COOKIE[$key]))
        {
            return self::ALREADY_VOTED;
        }
        
        $expire = time() + 60*60*24*365;
        setcookie($key, true, $expire);
        return self::NEW_VOTE;
    }
}