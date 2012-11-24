<?php

/**
 * VoteTracker Class
 *
 * Tracks whether a client has voted for a specific post.
 * Data is saved 
 *
 * @author Robert Vogt <robert.vogt@mind.ch>
 * @package mind
 * @subpackage evervote
 * @copyright Copyright 2012 MIND Kommunikation GmbH <www.mind.ch>
 * @version 0.1
 */
class VoteTracker
{

    /**
     * Current Post ID
     * 
     * @var int $postID
     */
    private $postID;

    /**
     * IP Address
     *
     * @var string $ip
     */
    private $ip;

    /**
     * Facebook User ID
     * 
     * @var string|int $fb
     */
    private $fb;

    /**
     * Twitter User ID
     *
     * @var string|int $twitter
     */
    private $twitter;

    /**
     * Prefix which is used for meta data
     * 
     * @var string META_KEY
     */
    const META_KEY = 'ev-tracker-';

    /**
     * Status Code for no votes
     *
     * @var int NO_VOTES
     */
    const NO_VOTES = 0;

    /**
     * Status Code if a vote is found for the current IP
     *
     * @var int IP_HAS_VOTED
     */
    const IP_HAS_VOTED = 10;

    /**
     * Status Code if a vote is found for the FB User
     *
     * @var int FB_USER_HAS_VOTED
     */
    const FB_USER_HAS_VOTED = 20;

    /**
     * Status Code if a vote is found for the Twitter User
     *
     * @var int TWT_USER_HAS_VOTED
     */
    const TWT_USER_HAS_VOTED = 30;

    /**
     * VoteTracker Constructor
     *
     * @param int $postID 
     */
    public function __construct($postID)
    {
        $this->postID = $postID;
    }

    /**
     * Setter for the $ip-property
     *
     * @param string $ip
     * @return void
     */
    public function setIP($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Setter for the $fb-property
     *
     * @param string|int $userID
     * @return void
     */
    public function setFacebook($userID)
    {
        $this->fb = $userID;
    }

    /**
     * Setter vor the $twitter-proerty
     *
     * @param string|int $userID
     * @return void
     */
    public function setTwitter($userID)
    {
        $this->twitter = $userID;
    }

    /**
     * Registers the IP to the Post
     *
     * @return void
     */
    public function registerIP()
    {
        if (!isset($this->ip))
        {
            throw new LogicException("IP address needs to be set before registration.");
        }

        $key = self::META_KEY . md5($this->ip);
        add_post_meta($this->postID, $key, true);
    }

    /**
     * Registers the FB User ID to the Post
     *
     * @return void
     * @todo implement
     */
    public function registerFacebook()
    {
        throw new BadMethodCallException(__METHOD__ . " not yet implemented.");
    }

    /**
     * Registers the Twitter User ID to the Post
     *
     * @return void
     * @todo implement
     */
    public function registerTwitter()
    {
        throw new BadMethodCallException(__METHOD__ . " not yet implemented.");
    }

    /**
     * Checks if the current user has voted for the post
     * 
     * Searches for:
     * 1. IP
     * 2. FB User ID
     * 3. Twitter User ID
     *
     * @return int either NO_VOTES, IP_HAS_VOTED, FB_USER_HAS_VOTED or TWT_USER_HAS_VOTED
     */
    public function hasVote()
    {
        if (isset($this->ip))
        {
            $ipKey = self::META_KEY . md5($this->ip);
            $metaByIP = get_post_meta($this->postID, $ipKey);

            if (!empty($metaByIP))
            {
                return self::IP_HAS_VOTED;
            }
        }

        if (isset($this->fb))
        {
            $fbKey = self::META_KEY . md5($this->fb);
            $metaByFB = get_post_meta($this->postID, $fbKey);

            if (!empty($metaByFB))
            {
                return self::FB_USER_HAS_VOTED;
            }
        }

        if (isset($this->twitter))
        {
            $twtKey = self::META_KEY . md5($this->twitter);
            $metaByTwt = get_post_meta($this->postID, $twitter);

            if (!empty($metaByTwt))
            {
                return self::TWT_USER_HAS_VOTED;
            }
        }

        return self::NO_VOTES;
    }
}