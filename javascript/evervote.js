(function($, EverVoteConfig) {

    var Debug = {
        isEnabled: false,
        log: function(msg) {
            if (this.isEnabled && window.console != null) {
                console.log(msg);
            }
        }
    };
    
    function EverVote(config) {
        Debug.log("-- Hi from EverVote!");

        this.button = config.button;
        this.countElement = config.count;

        // Test whether user is allowed to vote on this post
        this.addListeners();
    };

    EverVote.prototype.addListeners = function() {
        var that = this;

        $(this.button).removeAttr('disabled');
        $(this.button).live('click', {'scope': that}, that.createAjaxRequest);
    };

    EverVote.prototype.createAjaxRequest = function(scope) {
        var that = scope.data.scope,
            btn = $(this);

        Debug.log('-- EverVote Button was clicked');

        $.ajax({
            type: 'POST',
            url: EverVoteConfig.ajaxUrl,
            data: {
                'action': 'evervote',
                'postID': btn.attr('data-post-id'),
                'everVoteNonce': EverVoteConfig.everVoteNonce
            },
            success: function(data, status, xhr) {
                $(that.countElement).text(data.votes);
                $(that.button).attr('disabled', 'disabled');

                Debug.log('-- AJAX Call succeeded')
                Debug.log(data);
            },
            error: function(xhr, status, error) {
                Debug.log('-- AJAX Call failed.');
                Debug.log('--- Status: ' + status);
                Debug.log('--- Error: ' + error);
            }
        });
    };

    $(document).ready(function() {
        if ($('#evervote-btn').length > 0) {
            var evervote = new EverVote({
                button: $('#evervote-btn'),
                count: $('#evervote-count')
            });
        }
    });
}
)(jQuery, EverVoteConfig);