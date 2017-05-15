<?php
/***********************************
// Launch WD plus option for password reminder or messages etc
// cdelfs@delfsengineering.ca 
// https://fmhelp.filemaker.com/docs/16/en/fmwd/index.html
***********************************/
// ----- CONFIG -------
$fms = 'myFmsBox.com' ;
$fms = 'some server' ;
$db = 'myTestDB'; // no .fmp12
$layout = 'Log' ; // the script is run from this layout
$xmlUser = 'someUser' ; // this account is just needed to run the resert and reminder handler script(s)
$xmlPass = 'somePass' ;
$script = 'Reset_Password'; // script for handling 
$webd = 'http://myWebdirectURL/fmi/webd#myDB' ; // may not be same as FMS
$key = "";
$val ="";
if (isset($_REQUEST['r']) && $_REQUEST['r']=='post') {
    
    // Regular login, forward credentials
    //Forward POST  to web direct
// Return this html and have browser do redirect    
echo <<< EOT
<!DOCTYPE html>
<html>
    <body onload="document.forms[0].submit()">
        <form action="<?php $webd ?>" method="post">
            <?php foreach( $_POST as $key => $val ): ?>
                <input type="hidden" name="<?= htmlspecialchars($key, ENT_COMPAT, 'UTF-8') ?>" value="<?= htmlspecialchars($val, ENT_COMPAT, 'UTF-8') ?>">
            <?php endforeach; ?>
        </form>
    </body>
</html>
EOT;
    die();
    
} else if (isset($_REQUEST['r'])) {
    // message to update or remind was passed so lets forward that by calling FMS XML and passign details
    
    // This is the JSON object that will be passed to the script
    $params = [
        user=>$_REQUEST['user'],
        pwd=>$_REQUEST['pwd'],
        r=>$_REQUEST['r']
    ];
        
    $params = urlencode(json_encode($params));
    
    $url = 'http://'.$xmlUser.':'.$xmlPass.'@'.$fms.'/fmi/xml/fmresultset.xml?-db='.$db.'&-lay='.$layout.'&-findany&-script='.$script.'&-script.param='.$params;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    
    echo ('Reqest has been sent to FMS, sit tight and $H1t will happen.');
    die();
} 
?>
<!DOCTYPE html>
<html>

<body>
    
    
<form action="wd_login.php">
    <h2>Login Form</h2>
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="user" >
    <br><br>
    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="pwd" >
    <input type="hidden" name="r" value="post" id="r" >
    <button type="submit">Login</button>
</form>

<form action="wd_login.php">
    <h2>Forgotten Form (calls script)</h2>
    <label><b> Username</b></label>
    <input type="text" placeholder="Enter Username" name="user" >
    <br>
    <input type="hidden" name="r" value="forgot" id="r" >
    <button type="submit">Forgot PWD</button>
</form>


</body>
   
</html>
