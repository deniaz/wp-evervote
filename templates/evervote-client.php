<!-- Yes, I am THAT lazy. But you should override this anyways. -->
<style>
.evervote-inner
{
    background: #efefef;
    color: #999;
    width: 10%;
    padding: 20px;
    text-align: center;
    border: 1px solid #999;
    border-radius: 8px;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    -o-border-radius: 8px;
    margin: 20px;
}

.evervote-inner button
{
    color: white;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    background-color: #FAA732;
    background-image: -moz-linear-gradient(top, #FBB450, #F89406);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#FBB450), to(#F89406));
    background-image: -webkit-linear-gradient(top, #FBB450, #F89406);
    background-image: -o-linear-gradient(top, #FBB450, #F89406);
    background-image: linear-gradient(to bottom, #FBB450, #F89406);
    background-repeat: repeat-x;
    border-color: #F89406 #F89406 #AD6704;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffbb450', endColorstr='#fff89406', GradientType=0);
    filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
    display: inline-block;
    padding: 4px 12px;
    margin-bottom: 0;
    font-size: 14px;
    line-height: 20px;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border-radius: 4px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -o-border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    width: 90%;
    border-width: 1px;
}

.evervote-inner button:active
{
    -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.evervote-inner button[disabled]
{
    cursor: default;
    background-image: none;
    opacity: 0.65;
    filter: alpha(opacity=65);
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    color: white;
    background-color: #F89406;
}
</style>

<div id="evervote-container">
    <div class="evervote-inner">
        <div class="votes">
            <p>Votes for <strong><?php  echo $postName; ?></strong></p>
            <p id="evervote-count"><?php echo $evervotes; ?></p>
        </div>
        <div class="action">
            <?php
                $tracker = new PostMetaCheck($postID);
                $tracker->setIP($_SERVER['REMOTE_ADDR']);

                if ($tracker->runCheck() !== PostMetaCheck::NO_VOTES)
                {
                    ?>
                    <button disabled="disabled">Vote!</button>
                    <?php
                }
                else
                {
                    ?>
                    <button disabled="disabled" data-post-id="<?php echo $postID; ?>" id="evervote-btn">Vote!</button>
                    <?php
                }
            ?>
       </div>
    </div>
</div>