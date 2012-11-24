<p>Post <em><?php echo $postName; ?></em> has <strong id="evervote-count"><?php echo $evervotes; ?></strong> Votes</p>
<?php
    $tracker = new VoteTracker($postID);
    $tracker->setIP($_SERVER['REMOTE_ADDR']);

    if ($tracker->hasVote() !== VoteTracker::NO_VOTES)
    {
        ?>
        <button disabled="disabled">EverVote</button>
        <?php
    }
    else
    {
        ?>
        <button disabled="disabled" data-post-id="<?php echo $postID; ?>" id="evervote-btn">EverVote</button>
        <?php
    }
?>