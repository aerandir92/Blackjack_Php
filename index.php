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
if(!isset($_SESSION['fiveCharlie'])) $_SESSION['fiveCharlie'] = False;
if(!isset($_SESSION['soft17'])) $_SESSION['soft17'] = False;
if(!isset($_SESSION['message'])) $_SESSION['message'] = "Have fun!";
if(!isset($_SESSION['size'])) $_SESSION['size'] = 4;
if(!isset($_SESSION['maxbet'])) $_SESSION['maxbet'] = 10000000;

if(!isset($_SESSION['account'])) $_SESSION['account'] = 1000000000;
if(!isset($_SESSION['playerMoney'])) $_SESSION['playerMoney'] = 100000000;
if(!isset($_SESSION['maxSplits'])) $_SESSION['maxSplits'] = 3;
if(!isset($_SESSION['aceHitSplit'])) $_SESSION['aceHitSplit'] = False;
if(!isset($_SESSION['aceReSplit'])) $_SESSION['aceReSplit'] = True;
if(!isset($_SESSION['double'])) $_SESSION['double'] = True;
if(!isset($_SESSION['doubleType'])) $_SESSION['doubleType'] = '9-11';
if(!isset($_SESSION['doubleAfterSplit'])) $_SESSION['doubleAfterSplit'] = True;

if(!isset($_SESSION['acceptNewRound'])) $_SESSION['acceptNewRound'] = True;



$owner = $_SESSION['owner'];
$fiveCharlie = $_SESSION['fiveCharlie'];
$soft17 = $_SESSION['soft17'];
$message = $_SESSION['message'];
$size = $_SESSION['size'];
$maxbet = $_SESSION['maxbet'];
$account = $_SESSION['account'];
$playerMoney = $_SESSION['playerMoney'];
$maxSplits = $_SESSION['maxSplits'];
$aceHitSplit = $_SESSION['aceHitSplit'];
$aceReSplit = $_SESSION['aceReSplit'];
$double = $_SESSION['double'];
$doubleType = $_SESSION['doubleType'];
$doubleAfterSplit = $_SESSION['doubleAfterSplit'];

if(!isset($_SESSION['playing'])) $_SESSION['playing'] = False;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/blackjack.css">
    <script src="js/blackjack.js"></script>
</head>
<body>
<section id="info" class="game-section">
    <div id="welcome">
        <p>Welcome to <?php echo $owner ?>'s blackjack! This casino has <?php echo '$'.$account ?> left.</p>
    </div>
    <div id="settings">
        <ul>
            <li>You can't bet more than <?php echo '$'.$maxbet ?></li>
            <li title="A Five-card Charlie is when you get 5 cards without going above a sum of 21.">
            <?php
            if ($fiveCharlie) echo 'You can win on a "Five-card Charlie". </li>';
            else echo 'You <b>can\'t</b>win on a "Five-card Charlie". </li>';

            echo '<li title="Soft 17 is when the sum is 17, but with an ace that counts as 11. Making it soft since it can also count as 7.">';
            if ($soft17) echo 'The dealer <b>have to</b> draw on a "soft 17". </li>';
            else echo 'The dealer <b>can\'t</b> draw on a "soft 17". </li>';

            echo '<li title="The number of decks may alter your winning chance.">';
            if ($size > 1) echo 'This casino uses ' . $size . ' decks</li>';
            else echo 'This casino uses 1 deck</li>';

            echo '<li title="If the two first cards in a hand have equal value it legal to split those two cards into separate hands with separat bets and results">';
            if($maxSplits > 1 || $maxSplits < 1) echo 'You are allowed to split ' . $maxSplits . ' times</li>';
            else echo 'You are allowed to split ' . $maxSplits . ' time</li>';

            echo '<li title="Re-splitting aces are situations where you started with two aces, then split, then receive another ace on one of those hands">';
            if($aceReSplit) echo 'You can re-split aces.</li>';
            else echo 'You <b>can\'t</b> re-split aces.</li>';

            echo '<li>';
            if($aceHitSplit) echo 'You can hit after splitting aces</li>';
            else echo 'You <b>can\'t</b> hit after splitting aces. Splitting aces will cause an automatic end of round.</li>';

            echo '<li title="In the beginning of a hand, when you have two cards, you can choose do double your bet. This will give you one more card and then the hand ends.">';
            if($double){
                if($doubleType === 'any') echo 'You can double down on any cards.</li>';
                elseif($doubleType === '9-11') echo 'You can only double on sums between 9 and 11</li>';
                elseif($doubleType === '10-11') echo 'You can only double on sums between 10 and 11</li>';
            }
            else echo 'You <b>can\'t</b> double down.</li>';

            if($double){
                echo '<li title="After you have split your current hand, you can also double it after receiving the second card.">';
                if($doubleAfterSplit) echo 'You can double down after a split</li>';
                else echo 'You <b>can\'t</b> double down after a split</li>';
            }

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
</section>
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
<section id="startGame" class="game-section">
    <form method="POST" onsubmit="cripple();">
        <div>
            <p>You have <?php echo '$'.$playerMoney ?> </p>
            <span>Bet: $</span>
            <input type="number" id="bet" name="bet" min="100" max="<?php echo $maxbet ?>" />
            <?php
            if(isset($_SESSION['blackjackError'])) {
                echo '<p>'.$_SESSION['blackjackError'].'</p>';
                unset($_SESSION['blackjackError']);
            }
            ?>
        </div>
        <div>
            <button type="submit" name="start" id="start" value="start">Play!</button>
        </div>
    </form>
</section>

<?php
}
else {
    include('blackjack.php');
?>
    <section id="game" class="game-section">
        <?php echo $_SESSION['printedCards']; ?>
        <div id="errors">
            <?php
            if(isset($_SESSION['blackjackError'])) {
                echo '<p>'.$_SESSION['blackjackError'].'</p>';
                unset($_SESSION['blackjackError']);
            }
            ?>
        </div>
        <div id="money">
            <h5>You money: <?php echo '$'.$_SESSION['playerMoney'] ?></h5>
        </div>
    </section>
<?php
}
?>
</body>
</html>