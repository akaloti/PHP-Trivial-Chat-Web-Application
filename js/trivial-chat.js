"use strict";

var chat = {};
chat.updateHistoryInterval;
chat.updatePeriod = 500; // how often to run the interval function
chat.lastMessageId = 0;

var ENTER_KEY_PRESS = 13;

$(document).ready(function() {
    showAppropriateMenu();
    setUpMainMenuEventHandlers();
    setUpChatRoomEventHandlers();
});

/**
 * @param message the error message
 */
function showPhpError(message) {
    var errorMessage = "PHP Error: " + message;
    alert(errorMessage);
    console.log(errorMessage);
}

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
            if (json.scriptError)
                showPhpError(json.scriptErrorMessage);
            else if (json.connected) {
                // There is an active session; ask the user if
                // she wants to continue it
                $("#session-name").html(json.name);
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
 * User's attempt to create a new account has been handled
 */
function submitCreateUser() {
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
                    chat.lastMessageId = parseInt(json.lastId);
                    takeUserToChatRoom();
                }
                else {
                    alert("Name already taken!");
                }
            }
        );
    }
}

/**
 * @post user's attempt at logging in has been accepted or rejected
 */
function submitLoginAttempt() {
    var username = $("#login-name").val();
    $.getJSON(
        "login.php",
        {
            name: username,
            pw: $("#login-pw").val()
        },
        function(json, status) {
            if (json.scriptError)
                showPhpError(json.scriptErrorMessage);
            else if (json.success) {
                chat.lastMessageId = parseInt(json.lastId);
                takeUserToChatRoom();
            }
            else {
                $("#login-pw").val("");
                alert("Wrong username or password");
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

    // Two ways to create a user: click button or press Enter in
    // either text field
    $("#create-create").click(submitCreateUser);
    $("#create-name, #create-pw").keypress(function(e) {
        if (e.keyCode === ENTER_KEY_PRESS)
            submitCreateUser();
    });

    $("#login-create").click(function(e){
        $("#login").hide(0);
        $("#create").show(0);
        e.preventDefault();
    });

    // Two ways to login: click button or press Enter in either
    // text field
    $("#login-login").click(submitLoginAttempt);
    $("#login-name, #login-pw").keypress(function(e) {
        if (e.keyCode === ENTER_KEY_PRESS)
            submitLoginAttempt();
    });

    $("#session-continue").click(function(e) {
        $.getJSON(
            "continue-session.php",
            function(json, status) {
                if (json.scriptError)
                    showPhpError(json.scriptErrorMessage);
                else
                    chat.lastMessageId = parseInt(json.lastId);
            }
        );
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
    chat.updateHistoryInterval =
        setInterval(updateChatHistory, chat.updatePeriod);
}

/**
 * @post client's message has been sent to server
 */
function sendMessage() {
    $.getJSON("send-message.php",
        {
            message: $("#chat-input").val()
        },
        function(json, status) {
            if (json.scriptError)
                showPhpError(json.scriptErrorMessage);
        });

    // Clear message input field
    $("#chat-input").val("");
}

/**
 * @post event handlers for the chat room have been set up
 */
function setUpChatRoomEventHandlers() {
    // Set up the two ways to send a message
    $("#chat-room-submit").click(sendMessage);
    $("#chat-input").keypress(function(e) {
        if (e.keyCode === ENTER_KEY_PRESS)
            sendMessage();
    });

    $("#chat-room-logout").click(logoutChatRoom);
}

/**
 * @post user's logging out of chatroom has been properly handled
 */
function logoutChatRoom() {
    $("#chat-history").empty();

    // Get out of the chat room
    $.getJSON("logout.php");
    $("#chat-room").hide(0);
    clearInterval(chat.updateHistoryInterval);

    // Properly set up main menu
    $(".screen").hide(0);
    $("input").val("");
    $("#main-menu").show(0);
    showAppropriateMenu();
}

/**
 * @post the new chat messages have been obtained and shown to this
 * user; "last message id" has been updated
 */
function updateChatHistory() {
    $.getJSON(
        "update-chat-history.php",
        {
            lastId: chat.lastMessageId
        },
        function(json, status) {
            if (json.scriptError)
                showPhpError(json.scriptErrorMessage);
            else {
                // If an id was returned, then update client's
                // "last message id"; otherwise, shouldn't update
                if (json.lastId)
                    chat.lastMessageId = parseInt(json.lastId);

                // Show the user the messages
                var messages = json.messages;
                for (var i in messages)
                    $("#chat-history").append("<li>" + messages[i]["name"] +
                        " said: " + messages[i]["message"]);
            }
        });
}