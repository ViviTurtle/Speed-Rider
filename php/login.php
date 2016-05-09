

<?php
// connection to the database and start the session
include("/includes/header.php");
require("/includes/common.php");
// This variable will be used to re-display the user's username


// checks to determine whether the login form has been submitted
// If it has, then the login code is run, otherwise the form is displayed


if(!empty($_POST)) {

    $usern = $_POST['username'];
    $username = $_POST['usernamesignup'];
    if (!empty($usern)) {

        // retreives the user's information from the database using username.
        $query = "SELECT USER_ID, FNAME, USERNAME, SALTED_HASH, USER_TYPE FROM T_USER WHERE USERNAME ='$usern'";


        try {
            // Execute the query against the database
            $stmt = $db->query($query);
            $stmtline = $stmt->fetch_object();
        } catch (PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }

        $login_ok = false;

        // Retrieve the user data from the database.  If $row is false, then the username
        // they entered is not registered.

        if ($stmtline) {
            // Using the password submitted by the user and the salt stored in the database,
            // we now check to see whether the passwords match by hashing the submitted password
            // and comparing it to the hashed version already stored in the database.
            /*$check_password = hash('sha256', $_POST['password'] . $row['SALTED_HASH']);
            for($round = 0; $round < 65536; $round++)
            {
                $check_password = hash('sha256', $check_password . $row['SALTED_HASH']);
                echo $check_password;

            }*/
            if ($stmtline->SALTED_HASH == $_POST['password']) {
                $login_ok = true;
            }


        }

        // If the user logged in successfully, then we send them to the private members-only page
        // Otherwise, we display a login failed message and show the login form again
        if ($login_ok) {

            // This stores the user's data into the session at the index 'user'.
            session_start();
            $_SESSION['userLogged'] = $stmtline;

            // Redirect the user to the private members-only page.
            //   header("Location: private.php");
            // die("Redirecting to: private.php");


            if ($stmtline->USER_TYPE == 'CLIET') {
                header("Location: ./passenger.php");

            } else if ($stmtline->USER_TYPE == 'DRIVR') {
                header("Location: ./driver.php");

            } else if ($stmtline->USER_TYPE == 'ADMIN') {
                header("Location: ./ministr.php");

            }


        } else {
            unset($_POST);
            header("location:login.php?msg=failed#tologin");

        }

    } else if (!empty($username)) {

        $password = $_POST['passwordsignup'];
        $usert = $_POST['usertype'];
        $email = $_POST['emailsignup'];

        // retreives the user's information from the database using username.
        $query = "SELECT * FROM T_USER WHERE USERNAME ='$username' OR EMAIL = '$email'";

        if (mysqli_num_rows($db->query($query))>0) {
            unset($_POST);
            header("location:login.php?msg=exist#toregister");

        } else {
            session_start();
            $_SESSION['userRegister'] = $username;

            $query = "INSERT INTO T_USER (USERNAME, SALTED_HASH, FNAME, LNAME, EMAIL, PRIM_PHONE, USER_TYPE, STATUS_TYPE) VALUES ('$username','$password','NotEntered','NotEntered','$email','00000000','$usert', 'OFFLN')";
            $db->query($query);
            header("location:register.php");


        }


/*


*/



    }




}

?>

<!-- Header -->
<header id="top" class="header">



    <section>
        <div id="containerLogin" >
            <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>
            <div id="wrapper">
                <div id="login" class="animate form">
                    <form  method="post" autocomplete="off">
                        <h1>Log in</h1>
                        <?php    if (isset($_GET["msg"]) && $_GET["msg"] == 'success') {
                            echo '<p class = "rowError">Registration SUCCESS!</p>';
                        } ?>

                        <p>
                            <label for="username" class="uname" data-icon="u" > Your Username </label>
                            <input id="username" name="username" required="required" type="text" placeholder="myusername eg. wadewilson1"/>
                        </p>
                        <p>
                            <label for="password" class="youpasswd" data-icon="p"> Your Password </label>
                            <input id="password" name="password" required="required" type="password" placeholder="eg. X8df!90EO" />
                        </p>
                        <?php    if (isset($_GET["msg"]) && $_GET["msg"] == 'failed') {
                            echo '<p class = "rowError">Wrong Username / Password</p>';
                        } ?>

                        <p class="keeplogin">
                            <input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" />
                            <label for="loginkeeping">Keep me logged in</label>
                        </p>

                        <p class="login button">
                            <input type="submit" value="Login" />
                        </p>

                        <span class="filler"></span>
                        <p class="change_link">
                            Not a member yet ?
                            <a href="#toregister" class="to_register">Join us</a>
                        </p>
                    </form>
                </div>

                <div id="register" class="animate form">
                    <form method="post" autocomplete="on">
                        <h2> Sign up </h2>
                        <p>
                            <label for="usernamesignup" class="uname" data-icon="u">Your username</label>
                            <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="SpeedRacer690" />
                        </p>
                        <p>
                            <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                            <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="gogospeedracer@mail.com"/>
                        </p>
                        <p>
                            <input id="usertype" name="usertype" required="required" value= "CLIET" type="radio" checked><label> Passenger</label></input>
                            <input id="usertype" name="usertype" required="required" value= "DRIVR" type="radio"><label> Driver</label></input>
                        </p>
                        <p>
                            <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                            <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="eg. X8df!90EO"/>
                        </p>
                        <p>
                            <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please confirm your password </label>
                            <input id="passwordsignup_confirm" name="passwordsignup_confirm" onkeyup="checkPasswordMatch(); return false;" required="required" type="password" placeholder="eg. X8df!90EO"/>
                        </p>

                        <p> <span id="confirmMessage" class="confirmMessage"></span> </p>
                        <?php    if (isset($_GET["msg"]) && $_GET["msg"] == 'exist') {
                            echo '<p class = "rowError">This username or email has been registered.</p>';
                        } ?>

                        <p class="signin button">
                            <input type="submit" id="signinbutton" value="Continue"/>
                        </p>

                        <script>

                            function checkPasswordMatch() {
                                //Store the password field objects into variables ...
                                var pass1 = document.getElementById('passwordsignup');
                                var pass2 = document.getElementById('passwordsignup_confirm');
                                //Store the Confimation Message
                                var message = document.getElementById('confirmMessage');
                                //Set the colors
                                var goodColor = "#66cc66";
                                var badColor = "#ff6666";
                                //Compare the values in the password field
                                //and the confirmation field
                                if (pass1.value == pass2.value) {
                                    //The passwords match.
                                    //Set the color to the good color and inform
                                    //the user that they have e ntered the correct password
                                    pass2.style.backgroundColor = goodColor;
                                    message.style.color = goodColor;
                                    message.innerHTML = "Passwords Match!"
                                    $('#signinbutton').prop('disabled', false);
                                } else {
                                    //The passwords do not match.
                                    //Set the color to the bad color and
                                    //notify the user.
                                    pass2.style.backgroundColor = badColor;
                                    message.style.color = badColor;
                                    message.innerHTML = "Passwords Do Not Match!"
                                    $('#signinbutton').prop('disabled', true);
                                }
                            }

                        </script>




                        <p class="change_link">
                            Already a member ?
                            <a href="#tologin" class="to_register"> Go and log in </a>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </section>

</header>

<!-- jQuery -->
<script src="/js/jquery-1.12.3.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
</script>

</body>

</html>
