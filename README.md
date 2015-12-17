# PHP-Trivial-Chat-Web-Application

About
-----

This is a trivial chat application made with PHP, MySQL, JSON,
JavaScript, jQuery, CSS, and HTML. I wrote it in the December of my
freshman year in college.

A demonstrative YouTube video can be found here:
https://www.youtube.com/watch?v=1X3_LPSLCB4

Features
--------

A client can log in by: 1) entering existing username and password,
2) creating a new user, or 3) resuming a session. Usernames and passwords
are stored in an SQL table.

Submitted messages are also stored in an SQL table. Care is taken to
ensure each client receives the messages he/she was present for.

Releases
--------

The latest release is: v0.1

Please see the releases section under this repository on GitHub
to download the latest release.

How to Run
----------

Use any local server that supports PHP and MySQL with PDO (PHP Data
Objects). I used WampServer.

Use of Git Hooks
----------------

To prevent trailing whitespace, I've enabled the pre-commit Git hook.
However, this change can't be saved in the repository. To make this
change, navigate to the directory .git/hooks, and rename "pre-commit.sample"
to "pre-commit".

Acknowledgements
----------------

"jQuery Game Development Essentials" by Selim Arsever helped by introducing
me to the many ways in which I used PHP scripts in this program, such
as logging in and checking the user's session. The main menu in this
application is based on the one discussed by the aforementioned book
in its seventh chapter.

"Jump Start PHP" by Callum Hopkins helped by introducing me to WampServer
and PHPMyAdmin and by showing me how to use PDO for communicating
with MySQL databases.

W3Schools was an immensely helpful resource.

Author
------

That is me, Aaron Kaloti.

Contact Information
-------------------

My email address: aarons.7007@gmail.com

My YouTube channel (in which I demonstrate my finished applications):
https://www.youtube.com/channel/UCHhcIcXErjijtAI9TWy7wNw/videos