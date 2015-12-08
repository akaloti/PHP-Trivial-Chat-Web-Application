"use strict";

$(document).ready(function() {
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
                        alert("Success!");
                    }
                    else {
                        alert("Name already taken!");
                    }
                }
            )
        }

        e.preventDefault();
    });
});