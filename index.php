<?php
interface Playable {
    function play();
    function pause();
}

abstract class Account implements Playable {
    protected $username, $plan;

    function __construct($username, $plan) {
        $this->username = $username;
        $this->plan = $plan;
    }

    function getInfo() {
        return "<p>User: {$this->username}</p><p>Plan: {$this->plan}</p>";
    }
}

class Free extends Account {
    function __construct($username) { parent::__construct($username, "Free"); }
    function play() { return "<p class='ads'>Playing music... (Ad included)</p>"; }
    function pause() { return "<p>Music paused</p>"; }
}

class Premium extends Account {
    function __construct($username) { parent::__construct($username, "Premium"); }
    function play() { return "<p class='noads'>Playing music... (No Ads)</p>"; }
    function pause() { return "<p>Music paused</p>"; }
}

$users = [
    'Free' => new Free("Daoming Si"),
    'Premium' => new Premium("Shan Chai")
];
?>

<!DOCTYPE html>
<html>
<head>
<title>Mini Spotify</title>
<style>
body{margin:0;display:flex;justify-content:center;align-items:center;height:100vh;background:#fff;font-family:Arial;}
.phone{background:linear-gradient(135deg,#0f2027,#203a43,#2ecc71);border-radius:30px;width:360px;padding:20px;text-align:center;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,.5);}
h1{color:#1db954;margin-bottom:20px;}
.box{background:rgba(0,0,0,.3);border-radius:15px;padding:15px;margin:15px 0;}
.ads{color:#ff6b6b;}
.noads{color:#2ecc71;}
button{padding:8px 12px;margin:5px;border:none;border-radius:5px;cursor:pointer;background:#1db954;color:#fff;}
button:hover{background:#17a74a;}
</style>
</head>
<body>

<div class="phone">
    <h1>🎧 Mini Spotify</h1>

    <?php foreach($users as $type => $user): ?>
        <div class="box">
            <h3><?= $type ?> User</h3>
            <?= $user->getInfo(); ?>
            <div id="<?= strtolower($type) ?>Status"></div>
            <button onclick="document.getElementById('<?= strtolower($type) ?>Status').innerHTML='<?= addslashes($user->play()) ?>'">▶ Play</button>
            <button onclick="document.getElementById('<?= strtolower($type) ?>Status').innerHTML='<?= addslashes($user->pause()) ?>'">⏸ Pause</button>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>