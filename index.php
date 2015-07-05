<?php
/**
 * Created by PhpStorm.
 * User: Øyvind
 * Date: 05.07.2015
 * Time: 15.26
 */
session_start();

//Test data
$_SESSION['owner'] = 'aerandir92';
if(!isset($_SESSION['fiveCharlie'])) $_SESSION['fiveCharlie'] = True;
if(!isset($_SESSION['soft17'])) $_SESSION['soft17'] = False;
if(!isset($_SESSION['message'])) $_SESSION['message'] = "Have fun!";
if(!isset($_SESSION['size'])) $_SESSION['size'] = 2;
if(!isset($_SESSION['maxbet'])) $_SESSION['maxbet'] = 1000000;

if(!isset($_SESSION['account'])) $_SESSION['account'] = 1000000000;
if(!isset($_SESSION['playerMoney'])) $_SESSION['playerMoney'] = 100000000;


$owner = $_SESSION['owner'];
$fiveCharlie = $_SESSION['fiveCharlie'];
$soft17 = $_SESSION['soft17'];
$message = $_SESSION['message'];
$size = $_SESSION['size'];
$maxbet = $_SESSION['maxbet'];
$account = $_SESSION['account'];
$playerMoney = $_SESSION['playerMoney'];

if(!isset($_SESSION['playing'])) $_SESSION['playing'] = False;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <div id="welcome">
        <p>Welcome to <?php echo $owner ?>'s blackjack! This casino has <?php echo '$'.$account ?> left.</p>
    </div>
    <div id="settings">
        <ul>
            <li>You can't bet more than <?php echo '$'.$maxbet ?></li>
            <?php
            if ($fiveCharlie) echo '<li>You can win on a "Five-card Charlie". </li>';
            else echo '<li>You <b>can\'t</b>win on a "Five-card Charlie". </li>';

            if ($soft17) echo '<li>The dealer <b>have to</b> draw on a "soft 17". </li>';
            else echo '<li>The dealer <b>can\'t</b> draw on a "soft 17". </li>';

            if ($size > 1) echo '<li>This casino uses ' . $size . ' decks</li>';
            else echo '<li>This casino uses 1 deck</li>';
            ?>
        </ul>
    </div>
    <div id="message">
        <p>
            A little message from the owner: <pre><?php echo $message ?></pre>
        </p>
    </div>
    <br />
    <a href="cp.php" target="_blank">Controllpanel</a>
<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['bet']) && $_POST['bet'] >= 100 && $_POST['bet'] <= $maxbet){
        //Game starting
        $_SESSION['playing'] = True;
        $_SESSION['newRound'] = True;
    }
}

if(!$_SESSION['playing']) {
//Not in a game, section
?>
    <form method="POST">
        <div>
            <p>You have <?php echo '$'.$playerMoney ?> </p>
            <span>Bet: $</span>
            <input type="number" id="bet" name="bet" min="100" max="<?php echo $maxbet ?>" />
            <?php
            if(isset($_SESSION['error'])) {
                echo '<p>'.$_SESSION['error'].'</p>';
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <div>
            <input type="submit" name="start" id="start" value="Play!"/>
        </div>
    </form>

<?php
}
else {
    //echo $_POST['bet'];
    include('blackjack.php');
    echo $_SESSION['printedCards'];
?>
    <form method="POST">
        <?php
            if(!$_SESSION['endGame']) {
        ?>
            <button type="submit" name="hit" id="hit" value="hit">Nytt kort</button>
            <button type="submit" name="check" id="check" value="check">Stå</button>
        <?php
            }
        else {
            echo '<p>';
            if ($_SESSION['result'] === 'Player') {
                echo '<b>You won!</b>';
            } elseif ($_SESSION['result'] === 'Dealer') {
                echo '<b>You lost!</b>';
            } elseif ($_SESSION['result'] === 'Push') {
                echo '<b>Draw!</b>';
            } elseif ($_SESSION['result'] === 'Charlie') {
                echo '<b>Five-card Charlie!</b> You win 3 times your bet!';
            } elseif ($_SESSION['result'] === 'Blackjack') {
                echo '<b>Blackjack!</b> You win 2.5 times your bet!';
            }
            echo '</p>';
            ?>
            <input type="number" id="bet" name="bet" min="100" max="<?php echo $maxbet ?>"
                   value="<?php echo $_SESSION['bet'] ?>"/>
            <button type="submit" name="again" id="again" value="again">Play again</button>
        <?php
        }
        ?>
    </form>
    <h5>You money: <?php echo '$'.$_SESSION['playerMoney'] ?></h5>
<?php
}
?>
</body>
</html>