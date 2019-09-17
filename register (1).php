<?php
session_start();
// Include config file
include "config.php";
$referer = $_GET['refer'];
// Define variables and initialize with empty values
$fname = $username = $email = $phone = $irefer =  $password = $confirm_password = "";
$fname_err = $username_err =  $email_err = $phone_err = $password_err = $confirm_password_err = "";
 
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    
    // Validate First Name
    if(empty(trim($_POST["fname"]))){
        $fname_err = "Please enter your first name.";     
    } else{
        $fname = trim($_POST["fname"]);
    }
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $link->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }

    
    // Validate Email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email address.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = $link->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "This Email address is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }

    // Validate Phone Number
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter your phone.";     
    } else{
        $phone = trim($_POST["phone"]);
    }
    
   
      $irefer = trim($_POST['refer']);
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    
    // Check input errors before inserting in database
    if(empty($fname_err) && empty($username_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty( $confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (Fullname, email, phone,username,refer, password) VALUES (?, ?, ?, ?,?, ?)";
        
         
        if($stmt = $link->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss", $param_fname,  $param_email, $param_phone,$param_username, $param_refer,  $param_password);
            
            // Set parameters
            $param_fname = $fname;
            $param_email = $email;
            $param_phone = $phone;
            $param_username = $username;
            $param_refer = $irefer;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            //define email variables
            $to_email = 'abasinkanga@btcmultiplierinvest.net';
            $subject = 'Registration Details';
            $message = $fname . " ," . " " . $email . "," . " " . $phone ;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                //send email to admin
            mail($to_email,$subject,$message);
                // Redirect to success page
                header("location: success.php");
            } else{
                echo("Error description: " .mysqli_error($link));
            }
        }
        
    }
    
    // Close connection
    $link->close();
}
?>





 
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="description" content="Bitcoin Investment platform">
    <meta name="keywords" content="Cryptocurrency, bitcoin, bitcoin landing, blockchain, crypto trading ">
    <meta name="author" content="Charles">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin Investment</title>
    <!-- Goole Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet"> 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/assets/bootstrap.min.css">
    <link rel="stylesheet" href="css/assets/font-awesome.min.css">
    <link rel="stylesheet" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" href="css/assets/magnific-popup.css">
    <link rel="stylesheet" href="css/assets/jquery.countdown.css"> 
    <link rel="stylesheet" href="css/assets/animate.css">
    <link rel="stylesheet" href="css/assets/owl.carousel.css">
    <link rel="stylesheet" href="css/assets/owl.theme.css">
    <!-- preloader css-->    
    <link rel="stylesheet" href="css/assets/preloader.css">
    <!-- main style-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cryptonio.css">
    <link rel="stylesheet" href="css/responsive.css">
                            
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!-- Preloader -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<header id="header" class="header header-01">
    <!-- START Navbar -->
    <nav class="navbar navbar-expand-md navbar-light bg-faded cripto_nav">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <!--<a class="navbar-brand" data-scroll href="index-03.html"><img src="images/logo.png" alt="logo"></a>-->

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a data-scroll href="index.html" class="nav-link active">Home</a></li>
                    <li class="nav-item"><a data-scroll href="#about_cryptonic" class="nav-link">Investment Plans</a></li>
                    <li class="nav-item"><a data-scroll href="#contact_us" class="nav-link">Contact</a></li>
                </ul>
            </div>
             <div class="language">
                <a href="register.php" class="token" style="margin-right:20px">Sign Up</a>
                <a href="login.php" class="token" style="margin-right:20px">Login</a>
                
            </div>
        </div>
    </nav>
    
     <div class="col-sm-12 col-md-6" style="margin-left:10%;margin-top:10%">
         <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Registration Form</h1>
        
      </div>
      
      <div class="form-label-group">
          <span style="color:red;"><?php echo $fname_err; ?></span>
           <label for="inputFullname">Full Name</label>
        <input type="text" id="inputfullname" name="fname" class="form-control" placeholder="Enter Your FullName" required autofocus>
      </div>
      <div class="form-label-group">
          <label for="inputEmail">Email address</label>
          <span style="color:red;"><?php echo $email_err; ?></span>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
      </div>
      <div class="form-label-group">
          <label for="inputusername">Username</label>
          <span style="color:red;"><?php echo $username_err; ?></span>
        <input type="text" id="inputusername" name="username" class="form-control" placeholder="Enter Username" required autofocus>
      </div>
           
        <div class="form-label-group">
            <label for="inputphone">Phone Number</label>
            <span style="color:red;"><?php echo $phone_err; ?></span>
        <input type="text" id="inputphone" name="phone" class="form-control" placeholder="Enter Your Whatsapp Phone Number" required autofocus>
      </div>
       <div class="form-label-group">
            <label for="inputrefer">Who refered You</label>
        <input type="text" id="inputrefer" class="form-control"  value="<?php echo $referer?>" name="refer" disabled autofocus>
      </div>

      <div class="form-label-group">
           <label for="inputPassword">Password</label>
           <span style="color:red;"><?php echo $password_err; ?></span>
        <input type="password" id="inputPassword" name ="password" class="form-control" placeholder="Password" required>
       
      </div>
      
      <div class="form-label-group">
           <label for="inputPassword">Confirm Password</label>
           <span style="color:red;"><?php echo $confirm_password_err; ?></span>
        <input type="password" id="inputPassword" name="confirm_password" class="form-control" placeholder="Password" required>
       
      </div>

      <button class="btn btn-lg btn-primary btn-block" type="submit" style="margin-top:10px;">Sign up</button>
      </form>
            </div>

</header> <!-- End Header -->





<footer id="footer" class="footer">
    <div class="container">
        
        <div class="copyright">
            <p>Copyright &copy; 2019, Designed By <span>FullStack Ltd</span></p>            
        </div>         
    </div>      
</footer><!-- ./ End Footer Area-->

    <!-- JavaScript Files -->
    <script src="js/assets/jquery-3.2.1.min.js"></script>
    <script src="js/assets/popper.min.js"></script>
    <script src="js/assets/bootstrap.min.js"></script>
    <script src="js/assets/jquery.sticky.js"></script>
    <script src="js/assets/isotope.pkgd.min.js"></script>
    <script src="js/assets/jquery.magnific-popup.min.js"></script>
    <script src="js/assets/jquery.countdown.js"></script>
    <script src="js/assets/owl.carousel.min.js"></script>     
    <script src="js/assets/jquery.downCount2.js"></script>
    <script src="js/assets/wow.min.js"></script>  
    <script src="js/assets/particles.js"></script>
    <script src="js/assets/app3.js"></script>  
    <script src="js/assets/smooth-scroll.js"></script>   
    <script src="js/assets/wow.min.js"></script>          
    <script src="js/custom.js"></script>
</body>
</html>
