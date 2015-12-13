"use strict";

var chat = {};
chat.username;
chat.updateHistoryInterval;
chat.updatePeriod = 1500; // how often to run the interval function

$(document).ready(function() {
    showAppropriateMenu();
    setUpMainMenuEventHandlers();
});

/**
 * @post based on session state, either the login menu or the
 * resume menu has been shown
 */
function showAppropriateMenu() {
    $.getJSON(
        "session.php",
        {
            name: "teehee",
        },
        function(json) {
            if (json.scriptError) {
                var message = json.scriptErrorMessage;
                alert(message);
                console.log(message);
            }
            else if (json.connected) {
                // There is an active session; ask the user if
                // she wants to continue it
                chat.username = json.name;
                $("#session-name").html(chat.username);
                $("#session").show(0);
            }
            else {
                // There's no active session; show login screen
                $("#login").show(0);
            }
        }
    );
}

/**
 * @post event handlers for the three menus (including the hidden ones)
 * have been set up
 */
function setUpMainMenuEventHandlers() {
    $("#create-cancel").click(function(e){
        $("#create").hide(0);
        $("#login").show(0);
        e.preventDefault();
    });

    $("#create-create").click(function(e) {
        var username = $("#create-name").val();
        var password = $("#create-pw").val();
        $("#create-pw").val("");

        // Check if username or password is too long
        if (username.length > 40)
            alert("Username is too long");
        else if (password.length > 100)
            alert("Password is too long");
        else {
            // Have the server create the account if unique username or note
            // error to user otherwise
            $.getJSON(
                "create-user.php",
                {
                    name: username,
                    pw: password
                },
                function(json, status) {
                    if (json.success) {
                        chat.username = username;
                        takeUserToChatRoom();
                    }
                    else {
                        alert("Name already taken!");
                    }
                }
            );
        }

        e.preventDefault();
    });

    $("#login-create").click(function(e){
        $("#login").hide(0);
        $("#create").show(0);
        e.preventDefault();
    });

    $("#login-login").click(function(e) {
        var username = $("#login-name").val();
        $.getJSON(
            "login.php",
            {
                name: username,
                pw: $("#login-pw").val()
            },
            function(json, status) {
                if (json.success) {
                    chat.username = username;
                    takeUserToChatRoom();
                }
                else if (json.scriptError) {
                    // Report the error from the PHP script
                    var message = json.scriptErrorMessage;
                    alert(message);
                    console.log(message);
                }
                else {
                    $("#login-pw").val("");
                    alert("Wrong username or password");
                }
            }
        );
    });

    $("#session-continue").click(function(e) {
        takeUserToChatRoom();
        e.preventDefault();
    });

    $("#session-logout").click(function(e) {
        $.getJSON("logout.php");
        $("#session").hide(0);
        $("#login").show(0);
        e.preventDefault();
    });
}

/**
 * @post main menu has been hidden; chat room has been shown;
 * any other appropriate functions have been called to help
 * set up the chat room
 */
function takeUserToChatRoom() {
    $("#main-menu").hide(0);
    $("#chat-room").show(0);
    setUpChatRoomEventHandlers();
    chat.updateHistoryInterval =
        setInterval(updateChatHistory, chat.updatePeriod);
}

/**
 * @post event handlers for the chat room have been set up
 */
function setUpChatRoomEventHandlers() {
    $("#chat-room-submit").click(function(e) {
        $.getJSON("send-message.php",
        {
            user: chat.username,
            message: $("#chat-input").val()
        },
        function(json, status) {
            if (json.scriptError) {
                var message = json.scriptErrorMessage;
                alert(message);
                console.log(message);
            }
        });
        $("#chat-input").val("");
    });
}

/**
 * @post the new chat messages have been obtained and shown to this
 * user
 */
function updateChatHistory() {
    $.getJSON("update-chat-history.php",
        function(json, status) {
            if (json.scriptError) {
                var message = json.scriptErrorMessage;
                console.log(message);
            }
            else {
                console.log(json);
                for (var i in json)
                    $("#chat-history").append("<li>" + json[i]["name"] +
                        " said: " + json[i]["message"]);
            }
        });
}