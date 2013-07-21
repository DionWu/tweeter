tweeter
=======

This is my 2nd PHP/MySQL project : constructing a twitter clone from scratch.

/Structure/
    /Pages/
    Login_page.php : where you begin for registration/login
    main_page.php : after login/reg it takes you here
    profile.php : your profile, can view your tweets
    others_profile.php : page to view other people's profiles and their tweets

    /Files/
    function.php : all the main functions you need to add tweets / follow people
    function.js : intro level ajax, looking to learn more in my next project
    header.php : basic connection to db
    tweeter.css / tweeter.sql : self explanatory



/Learning Lessons/

1. First project I've ever had to manage multiple php files as well as incorporating multiple js/css files! Quite the experience learning how to insert variables as parameters and NOT defining global variables as it is a pain debugging

2. Learned a little bit about ajax and the wonders it can bring and the holes it fills as php is not enough

3. Learned some neat tricks with preparing stmts with an unknown number of variables, who to bindParam an array, and finally clarified how to call foreach() loops on array results returned from $pdo->execute() calls.

4. Learned how to convert timezones & why it's best practice to have users pick their own timezone as opposed to trying to auto-detect it (error prone!)

5. Learned that with ajax calls to php functions, you have to re-initialize the connection in the function!

6. Sessions! Learned about superglobals, but still need some more work into this

7. Refined some security concepts against mysql injections





/Things to learn in the future/
1. AJAX/JQuery & ways of better implementation (DEFINITELY THIS)

2. How to make more efficient / clean code

3. Managing bigger and more complex MySQL databases

4. Using MySQL functions that are inherently in the language

5. Be more comfortable with file management.




/Things I couldn't do this time around/
1. Very lost on hashtagging / @username incorporation - looking to add these functionalities in later as my knowledge grows.

2. PDO OOP. Learned about it 3/4 way through and became mind-numbing to switch. Looking to start next project out using this method of coding (it might aide in my implementation of AJAX with JQuery)




/ Hope you all enjoy! /


========
End