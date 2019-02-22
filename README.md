SomeSN
======

An old (and very incomplete) social networking site. Also lazily renamed.

IMPORTANT:
------
This project was originally being developed back in 2014 and halted completely in the beginning of 2016. The scripts have not been maintained for around a year and a half at the time of editing this README.md file and there is no plan to continue maintaining. With that said, this repository will not accept requests of any kind involving improvements to the actual project. If you wish to fix things yourself then feel free to fork or do whatever you need to do.
<br>
It is <b>not</b> recommended to use the scripts from this project in its entirety unless you're willing to address the issues. More on that later.

What does this have?
------
&bull; Account management page -- specify image url for avatar (simply put into the img src -- don't do this), "group" (the idea was to have some group system but that never came to be), website url, biography;
<br>
&bull; Kind of working store -- currently only lists available store applications;
<br>
&bull; Store application "launcher" -- checks if you "own" a store application and provides the data the applications may require to function;
<br>
&bull; Chat -- w/ multiple rooms, decent flood protection & commands;
<br>
&bull; Leaderboard -- lists users in a descending order based on the amount of points they had obtained;
<br>
&bull; Login, register, and logout (of course);
<br>
&bull; Safelink -- whitelist or blacklist links that get automatically hyperlinked;
<br>
&bull; Security page -- show stats about things such as login attempts. Actual logging functionality not implemented;
<br>
&bull; Staff page -- suspend, unsuspend, supports GET & POST to specify the username to manage;
<br>
&bull; Status -- supports comments and removal of posts;
<br>
&bull; Suspension system -- suspension times are there (refer to staff page) but not functional (checking of the remaining time still needs to be implemented);
<br>
&bull; "Terminal" -- a somewhat more advanced looking tool for the higher up (currently to check account information);
<br>
&bull; Terms of Service -- really doesn't need an explaination;
<br>
&bull; Profiles -- supports CSS styling (probably not a good idea), comments, online status;
<br>
&bull; BBCodes -- bold, italic, strike out, ...;
<br>
&bull; Online list -- lists the users who were online within a certain amount of time;
<br>
&bull; Menu -- there is a menu that goes down with a transition (hopefully) smoothly. This would have notifications, shortcuts, and so forth.
<br>
<br>
I decided to leave in 'privtest/index.php' just in case it's useful to someone.

What issues are there?
------
&bull; Not PSR-2 compliant;
<br>
&bull; Not so modular -- need to change more files manually;
<br>
&bull; The code used for authentication is hardcoded into each script that requires it rather than being included -- same for suspension checks, BBCode, time functions and other things that may be repeated.
<br>
<br>
Due to how specific the issues can be and the amount there is, it was decided to add them in form of comments in the appropriate scripts. Please note that to save time that I did not bother repeating comments in the scripts that have the exact same issues (such as the ones involving the inclusion of other scripts). So if you see an issue but don't see it being commented on, it has most likely been commented on in a different script.

What to do to improve this:
------
&bull; Make it more modular and better organise where the code should be;
<br>
&bull; Remove code that should not be there (e.g. htmlentities for input that would not be outputted);
<br>
&bull; Better password protection;
<br>
&bull; Keep the code clean-looking and have it to be PSR-2 compliant at the very least;
<br>
&bull; Redo how authentication is done -- an authentication key would be better than a username and password in cookies;
<br>
&bull; Replace 'include' with 'require' in scripts and check if the files attempted to be included can be accessed;
<br>
&bull; Remove TOR checks but leave it for the register and replace it with a working one -- it got killed off some time ago;
<br>
&bull; Use sockets for the chat;
<br>
&bull; A few other things that aren't worth listing.

Why is this up in a repository if it has so many issues?
------
There's a few reasons.
<br>
&bull; To show how I improved by pointing out (possible) issues in past projects as well as solutions;
<br>
&bull; There could be ideas, code, or other information others might benefit from. Heck, maybe someone could create an improved version of this for others to use practically.
