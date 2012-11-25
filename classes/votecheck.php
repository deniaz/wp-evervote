<?php

/**
 * VoteCheck interface
 *
 * Defines an interface for all security-/validity-checks
 *
 * @author Robert Vogt <robert.vogt@mind.ch>
 * @package mind
 * @subpackage evervote
 * @copyright Copyright 2012 MIND Kommunikation GmbH <www.mind.ch>
 * @version 0.1
 * @abstract
 */
interface VoteCheck
{
    public function runCheck();
}