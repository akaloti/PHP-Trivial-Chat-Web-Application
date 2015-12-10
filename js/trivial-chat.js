"use strict";

var username;

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
                username = json.name;
                $("#session-name").html(username);
                $("#session").show(0);
            }
            else {
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
    $("#create-create").click(function(e) {
        var username = $("#create-name").val();
        var password = $("#create-pw").val();

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
                        takeUserToChatRoom();
                    }
                    else {
                        alert("Name already taken!");
                        $("#create-pw").val("");
                    }
                }
            );
        }

        e.preventDefault();
    });

    $("#login-login").click(function(e) {
        $.getJSON(
            "login.php",
            {
                name: $("#login-name").val(),
                pw: $("#login-pw").val()
            },
            function(json, status) {
                if (json.success) {
                    takeUserToChatRoom();
                }
                else if (json.scriptError) {
                    // Report the error from the PHP script
                    var message = json.scriptErrorMessage;
                    alert(message);
                    console.log(message);
                }
                else {
                    alert("Wrong username or password");
                    $("#login-pw").val("");
                }
            }
        );
    });

    $("#session-continue").click(function(e) {
        takeUserToChatRoom();
        e.preventDefault();
    });
}

/**
 * @post main menu has been hidden; chat room has been shown
 */
function takeUserToChatRoom() {
    $(".screen").hide(0);
    $("#chat-room").show(0);
}