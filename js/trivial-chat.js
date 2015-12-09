"use strict";

$(document).ready(function() {
    $("#chat-room").hide(0);

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
                        $("#beginning").hide(0);
                        $("#chat-room").show(0);
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
                    $("#beginning").hide(0);
                    $("#chat-room").show(0);
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
});