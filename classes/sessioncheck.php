<?php

/**
 * SessionCheck Class
 *
 * SessionCheck, as the name suggests, does some logging in the PHP $_SESSION variable. 
 * It is used by EverVote to check an IP address' usage and interval to prevent manipulation.
 *
 * @author Robert Vogt <robert.vogt@mind.ch>
 * @package mind
 * @subpackage evervote
 * @copyright Copyright 2012 MIND Kommunikation GmbH <www.mind.ch>
 * @version 0.1
 */
class SessionCheck implements VoteCheck
{
    /**
     * Singleton Instance
     * 
     * @var SessionLog $instance SessionLog Instance
     */
    private static $instance;

    /**
     * Map with call-count, timestamp and a state for an IP address
     *
     * @var Array $map
     */
    private $map;

    /**
     * Number of votable posts
     * 
     * @var int $posts
     */
    private $posts;

    /**
     * Client's IP address
     *
     * @var string $ip
     */
    private $ip;

    /**
     * STATE_NORMAL is the state if everthing's fine
     *
     * @var int STATE_NORMAL
     */
    const STATE_NORMAL = 1;

    /**
     * STATE_HARMFUL is the state if something's wrong
     *
     * @var int STATE_HARMFUL
     */
    const STATE_HARMFUL = 2;

    /**
     * Starts session and syncs the attribute with the $_SESSION-property
     *
     * @todo Write votable posts
     */
    private function __construct()
    {
        session_start();

        if (!isset($_SESSION['EVERVOTE_MAP']))
        {
            $_SESSION['EVERVOTE_MAP'] = array(
                );
        }

        $this->map = $_SESSION['EVERVOTE_MAP'];

        // TODO: Set $this->posts to number of votable posts!
        $this->posts = 9999;
    }

    /**
     * Singleton - Creates if needed an returns instance
     *
     * @return SessionLog Instance
     */
    public function getInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Setter for $ip-member variable
     *
     * @param string $ip
     * @return void
     */
    public function setIP($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Add Log entry and check for validity
     *
     * Adds or updates a log entry
     * 
     * @return bool true|false true if STATE_NORMAL, false if STATE_HARMFUL
     */
    public function runCheck()
    {

        $ip = $this->ip;

        if (!isset($this->map[$ip]))
        {
            $this->map[$ip] = array(
                'calls' => 0,
                'lastcall' => 0,
                'state' => self::STATE_NORMAL
                );
        }

        $this->map[$ip]['calls'] = (int) $this->map[$ip]['calls'] + 1;

        if ($this->map[$ip]['lastcall'] > 0)
        {
            $timestamp = time();
            if ((int) $timestamp - (int) $this->map[$ip]['lastcall'] < 5)
            {
                $this->map[$ip]['state'] = self::STATE_HARMFUL;
            }
            else
            {
                $this->map[$ip]['state'] = self::STATE_NORMAL;
            }
        }
        else
        {
            $this->map[$ip]['state'] = self::STATE_NORMAL;
        }

        $this->map[$ip]['lastcall'] = time();

        if ($this->map[$ip]['calls'] > $this->posts)
        {
            $this->map[$ip]['state'] = self::STATE_HARMFUL;
        }

        $_SESSION['EVERVOTE_MAP'] = $this->map;

        if ($this->map[$ip]['state'] === self::STATE_NORMAL)
        {
            return self::STATE_NORMAL;
        }

        return self::STATE_HARMFUL;
    }
}