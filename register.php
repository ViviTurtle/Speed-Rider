

<?php


session_start();
if(!isset($_SESSION['userRegister']) || empty($_SESSION['userRegister'])) {
    header('Location: ./login.php');
    exit;
} else {
    $UserInf = $_SESSION['userRegister'];
}

// connection to the database and start the session
    include("./includes/header.php");
    require("./includes/common.php");

echo $UserInf;

if(!empty($_POST)) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phonenumber'];

    $query = "UPDATE T_USER SET FNAME='$fname', LNAME='$lname', PRIM_PHONE='$phone' WHERE USERNAME='$UserInf'";

    if ($db->query($query)) {
        header("location:login.php?msg=success#tologin");

    } else {
        die("Failed to run query.");
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


                <div id="register" class="animate form">
                    <form method="post" autocomplete="on">
                        <h2> Sign up </h2>
                        <p>
                            <label for="usernamesignup" class="uname" data-icon="u">First Name</label>
                            <input id="fname" name="fname" required="required" type="text" placeholder="Gobo" />
                        </p>
                        <p>
                            <label for="lname" class="uname" data-icon="u" > Last Name</label>
                            <input id="lname" name="lname" required="required" type="text" placeholder="Fraggle"/>
                        </p>
                        <p>
                            <label for="phonenumber" class="youpasswd" data-icon="p">Phone Number </label>
                            <input id="phonenumber" name="phonenumber" required="required" type="tel" placeholder="4125556688"/>
                        </p>

                        <?php    if (isset($_GET["msg"]) && $_GET["msg"] == 'exist') {
                            echo '<p class = "rowError">This username or email has been registered.</p>';
                        } ?>

                        <p class="signin button">
                            <input type="submit" id="signinbutton" value="Submit"/>
                        </p>



                    </form>
                </div>

            </div>
        </div>
    </section>

</header>

<!-- jQuery -->
<script src="./js/jquery-1.12.3.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="./js/bootstrap.min.js"></script>

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
