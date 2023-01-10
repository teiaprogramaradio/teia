<?php


session_start();
 
if(isset($_GET['logout'])){    
     
    //Simple exit message
    $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
     
    session_destroy();
    header("Location: transmissao.php"); //Redirect the user
}
 
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}
 
function loginForm(){
    echo
    '<div id="containerT">
    <div id="audio-player-container">
            <audio> 
                <source src="https://teiaposer.out.airtime.pro/teiaposer_a" type="audio/ogg">
                <source src="https://teiaposer.out.airtime.pro/teiaposer_a" type="audio/mpeg">
            </audio>
            <button id="play-icon"></button>
            <span id="current-time" class="time">0:00</span>
            <input type="range" id="seek-slider" max="5000" value="0">
            <input type="range" id="volume-slider" max="100" value="100">
            <button id="mute-icon"></button>
            <output id="volume-output">100</output>
        </div>
    <div id="center">
    
    
    <div id="loginform">
    <p>Please enter your name to continue!</p>
    <form action="transmissao.php" method="post">
      <label for="name">Name &mdash;</label>
      <input type="text" name="name" id="name" />
      <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
  </div>
  <div id="menu_buttons">
            
            <div class="menu_logo" id=""><a href="index.html"><img src="images/logo.png" class="logo"></a></div>
            <div class="menu_buttons" id="menu_transmissao"><a href="transmissao.php" ><span style="font-family: NHaasGrotesk-Italic">-LIVE-</span></a></div>   
            <div class="menu_buttons" id="menu_arquivo"><a href="arquivo.html" >ARCHIVE</a></div>
            <div class="menu_buttons" id="menu_zine"><a href="zine.html" >ZINE</a></div>
            <div class="menu_buttons" id="menu_sobre"><a href="teia.html" >ABOUT</a></div>
        </div>
        </div>
        </div>
        <script type="module" src="./player.js"></script>
  
  ';
}
 
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
 
        <title>TEIA Rádio</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="index.css" />
        <link rel="stylesheet" href="./player.css">
</head>
<body onresize="resetChanges()">
    <script src="jquery-3.3.1.js"></script>
    <?php
    if(!isset($_SESSION['name'])){
        loginForm();
    }
    else {
    ?>
        <div id="containerT">
            <div id="audio-player-container">
            <audio> 
                <source src="https://teiaposer.out.airtime.pro/teiaposer_a" type="audio/ogg">
                <source src="https://teiaposer.out.airtime.pro/teiaposer_a" type="audio/mpeg">
            </audio>
            <button id="play-icon"></button>
            <span id="current-time" class="time">0:00</span>
            <input type="range" id="seek-slider" max="5000" value="0">
            <input type="range" id="volume-slider" max="100" value="100">
            <button id="mute-icon"></button>
            <output id="volume-output">100</output>
        </div>
        
        
        <div id="center">
        <div id="issue_image" style="background-image: url(images/issue/25.png);"></div>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
            </div>
            
 
            <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
            </div>
 
            <form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // jQuery Document
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });
 
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request
 
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div
 
                            //Auto-scroll           
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                            }   
                        }
                    });
                }
 
                setInterval (loadLog, 2500);
 
                $("#exit").click(function () {
                    var exit = confirm("Are you sure you want to end the session?");
                    if (exit == true) {
                    window.location = "transmissao.php?logout=true";
                    }
                });
            });
        </script>
        <div id="menu_buttons">
            <div class="menu_logo" id=""><a href="index.html"><img src="images/logo.png" class="logo"></a></div>
            <div class="menu_buttons" id=""><a href="transmissao.php"><span style="font-family: NHaasGrotesk-Italic">-LIVE-</span></a></div>
            <div class="menu_buttons" id=""><a href="arquivo.html">ARCHIVE</a></div>
            <div class="menu_buttons" id=""><a href="zine.html">ZINE</a></div>
            <div class="menu_buttons" id=""><a href="teia.html">ABOUT</a></div>
        </div>
            </div>
        </div>
        <script type="module" src="./player.js"></script>
    </body>
</html>
<?php
}
?>