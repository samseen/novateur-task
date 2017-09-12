<?php
require_once './vendor/autoload.php';

$helperLoader = new SplClassLoader('Helpers', './vendor');
$mailLoader   = new SplClassLoader('SimpleMail', './vendor');

$helperLoader->register();
$mailLoader->register();

use Helpers\Config;

$config = new Config;
$config->load('./config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = stripslashes(trim($_POST['form-name']));
    $address   = stripslashes(trim($_POST['form-address']));
    $dob   = stripslashes(trim($_POST['form-dob']));
    $position = stripslashes(trim($_POST['form-position']));
    $pattern = '/[\r\n]|Content-Type:|Bcc:|Cc:/i';

    if (preg_match($pattern, $name) || preg_match($pattern, $address) || preg_match($pattern, $position)) {
        die("Header injection detected");
    }


    if (!empty($name) && !empty($address) && !empty($dob) && !empty($position)) {
         // Connect to the database
         $dbc = mysqli_connect('localhost', 'root', '', 'registrationdb')
            or die("Error connection to the database");

        // Write to the database
        $query = "INSERT INTO registration_table(name, address, dob, position)" .
        "VALUES ('$name', '$address', '$dob', '$position')";

        mysqli_query($dbc, $query)
            or die("Error querying database");

         $dataSaved = true;     
    } else {         
        $hasError = true;
    } 
} 
?>
<!DOCTYPE html> 
<html> 
    <head>     
        <title>Simple PHP Registration Form</title> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta charset="utf-8">     
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head> 
    <body>     
        <div class="jumbotron"> 
            <div class="container">
                <h1>Novateur Registration Task</h1> 
                <p>A Simple Registration Form developed in PHP with HTML5 Form validation.</p>
            </div>     
        </div>     
        <?php if(!empty($dataSaved)): ?>         
            <div class="col-md-6 col-md-offset-3"> 
            <div class="alert alert-success text-center">
                <?php echo $config->get('messages.success'); ?>
                <button type="submit" id="ajaxButton" class="btn btn-default"><?php echo $config->get('fields.btn-retrieve'); ?></button>

                <div id="result"></div>
            </div>
            </div>     
            <?php else: ?> 
            <?php if(!empty($hasError)): ?>
                <div class="col-md-5 col-md-offset-4"> 
                    <div class="alert alert-danger text-center"><?php echo $config->get('messages.error'); ?></div>
                </div>         
        <?php endif; ?>

        <div class="col-md-6 col-md-offset-3">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="application/x-www-form-urlencoded" id="contact-form" class="form-horizontal" method="post">
                <div class="form-group">
                    <label for="form-name" class="col-lg-2 control-label"><?php echo $config->get('fields.name'); ?></label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="form-name" name="form-name" placeholder="<?php echo $config->get('fields.name'); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="form-address" class="col-lg-2 control-label"><?php echo $config->get('fields.address'); ?></label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="form-address" name="form-address" placeholder="<?php echo $config->get('fields.address'); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="form-date" class="col-lg-2 control-label"><?php echo $config->get('fields.dob'); ?></label>
                    <div class="col-lg-10">
                        <input type="date" class="form-control" id="form-dob" name="form-dob" min="1970-01-01" max="2000-01-01" placeholder="<?php echo $config->get('fields.dob'); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="form-position" class="col-lg-2 control-label"><?php echo $config->get('fields.position'); ?></label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="form-position" name="form-position" placeholder="<?php echo $config->get('fields.position'); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-default"><?php echo $config->get('fields.btn-send'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <script type="text/javascript" src="public/js/contact-form.js"></script>
    <script type="text/javascript">
        new ContactForm('#contact-form');
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script type="text/javascript">

 $(document).ready(function() {

    $("#ajaxButton").click(function() {                

      $.ajax({    
        type: "GET",
        url: "load.php",             
        dataType: "html",                   
        success: function(response){                    
            $("#result").html(response); 
            //alert(response);
        }

    });
});
});

</script>
</body>
</html>
