(function($, EverVoteConfig) {

    var Debug = {
        isEnabled: true,
        log: function(msg) {
            if (this.isEnabled && window.console != null) {
                console.log(msg);
            }
        }
    };

    function Storage(config) {
        Debug.log("-- Init Storage");
        this.identifier = config.postID;
    };

    Storage.prototype.registerClient = function() {
        this.allocateLocalStorage();
        this.allocateSessionStorage();

        this.allocateCookie();

        this.allocateDB();
    };

    Storage.prototype.getObfuscatedKey = function() {
        Debug.log('-- Obfuscated Key.');
        var key = 'evervoted-client-' + this.identifier;
        return md5(key);
    };

    Storage.prototype.allocateLocalStorage = function() {
        if (typeof(Storage) !== 'undefined') {
            window.localStorage.setItem(
                this.getObfuscatedKey(),
                true
                );
            Debug.log('-- Added EverVote to LocalStorage');
        } else {
            Debug.log('-- Browser does not support localStorage.');
        }
    };

    Storage.prototype.allocateSessionStorage = function() {
        if (typeof(Storage) !== 'undefined') {
            window.sessionStorage.setItem(
                this.getObfuscatedKey(),
                true
                );
            Debug.log('-- Added EverVote to SessionStorage');
        } else {
            Debug.log('-- Browser does not support sessionStorage.');
        }
    };

    Storage.prototype.allocateCookie = function() {
        document.cookie = '__ev_' + this.getObfuscatedKey() + '=true';
        Debug.log('-- Added EverVote to Cookie.');
    };

    Storage.prototype.allocateDB = function() {
        if (typeof(window.openDatabase) !== 'undefined') {
            Debug.log("-- Opening DB");
            this.db = openDatabase(
                'evdb',
                '1.0',
                'EverVote Local DB',
                2 * 1024 * 1024
                );

            var postID = this.identifier;
            console.log("HOI RÖBI: " + postID);
            this.db.transaction(function(tx) {
                tx.executeSql("CREATE TABLE IF NOT EXISTS evervotes (wp_post_id)");
                tx.executeSql("INSERT INTO evervotes (wp_post_id) VALUES (?)", [postID]);
            });
        }
    };

    Storage.prototype.checkLocalStorage = function() {
        if (typeof(Storage) !== 'undefined') {
            Debug.log('-- Checking localStorage');
            var value = window.localStorage.getItem(this.getObfuscatedKey());
            if (value !== null) {
                return true;
            }
        }
        return false;
    };

    Storage.prototype.checkSessionStorage = function() {
        if (typeof(Storage) !== 'undefined') {
            Debug.log('-- Checking sessionStorage');
            var value = window.sessionStorage.getItem(this.getObfuscatedKey());
            if (value !== null) {
                return true;
            }
        }
        return false;
    };

    Storage.prototype.checkCookie = function() {
        Debug.log('-- Checking Cookie');
        var cookieKey = '__ev_' + this.getObfuscatedKey();
        if (document.cookie.indexOf(cookieKey) >= 0) {
            return true;
        }
        return false;
    };

    Storage.prototype.checkDB = function() {
        if (typeof(window.openDatabase) !== 'undefined') {
            Debug.log('-- Checking DB');
            this.db = openDatabase(
                'evdb',
                '1.0',
                'EverVote Local DB',
                2 * 1024 * 1024
                );

            this.db.transaction(function(tx) {
                tx.executeSql(
                "SELECT * FROM evervotes",
                [],
                function (tx, results) {
                    var len = results.rows.length, i;
                    // Check if there's sth
                    for (i = 0; i < len; i++) {
                        console.log(results.rows.item(0));
                    }
                });
            });
        }
        return false;
    };

    Storage.prototype.hasVoted = function() {
        if (this.checkLocalStorage()) {
            return true;
        } else if (this.checkSessionStorage()) {
            return true;
        } else if (this.checkCookie()) {
            return true;
        } else if (this.checkDB()) {
            return true;
        }

        return false;
    };

    
    function EverVote(config) {
        Debug.log("-- Hi from EverVote!");

        this.button = config.button;
        this.countElement = config.count;

        // Test whether user is allowed to vote on this post
        if ($(this.button).length > 0) {
            var storage = new Storage({
                postID: $(this.button).attr('id')
            });

            if (!storage.hasVoted()) {
                this.addListeners();
            }
        }
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

                Debug.log('-- AJAX Call succeeded');

                var storage = new Storage({
                    postID: data.postID
                });

                storage.registerClient();

            },
            error: function(xhr, status, error) {
                Debug.log('-- AJAX Call failed.');
                Debug.log('--- Status: ' + status);
                Debug.log('--- Error: ' + error);
                Debug.log('-- Response: ' + xhr.responseText);
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
})(jQuery, EverVoteConfig);