"use strict";

$(document).ready(function() {
    $("#create-create").click(function(e) {
        $.getJSON(
            "create-user.php",
            {
                name: $("#create-name").val(),
                pw: $("#create-pw").val()
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
        e.preventDefault();
    });
});