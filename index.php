<?php
session_start();

/* ---------- ACCOUNT ---------- */
abstract class Account{
    public $username,$plan;

    function __construct($u,$p){
        $this->username=$u;
        $this->plan=$p;
    }

    abstract function ads();
}

class Free extends Account{
    function __construct($u){
        parent::__construct($u,'Free');
    }
    function ads(){ return true; }
}

class Premium extends Account{
    function __construct($u){
        parent::__construct($u,'Premium');
    }
    function ads(){ return false; }
}

/* ---------- USERS ---------- */
$users=[
    'free_user'=>new Free('free_user'),
    'premium_user'=>new Premium('premium_user')
];

/* ---------- LOGIN ---------- */
if(isset($_POST['login'])){
    if(isset($users[$_POST['username']]) && $_POST['password']=='123'){
        $_SESSION['user']=$users[$_POST['username']];
        header("Location:index.php");
        exit;
    }
    $error="Wrong credentials";
}

if(isset($_GET['logout'])){
    session_destroy();
    header("Location:index.php");
    exit;
}

$user=$_SESSION['user']??null;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mini Spotify</title>

<link rel="stylesheet" href="style.css">
</head>

<body>

<?php if(!$user): ?>

<div class="login">
<form class="box" method="POST">
    <h2>🎧 Mini Spotify</h2>

    <input type="text" name="username" value="free_user" required>
    <input type="password" name="password" value="123" required>

    <button name="login">Login</button>

    <small>
        free_user / 123<br>
        premium_user / 123
    </small>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
</form>
</div>

<?php else: ?>

<div class="app">

<header>
    <div class="logo">🎵 Mini Spotify</div>
    <div>
        <?= $user->username ?> (<?= $user->plan ?>)
        <a class="logout" href="?logout=1">Logout</a>
    </div>
</header>

<div class="player">

    <div class="now">
        <img id="cover" src="images/Kanibalismo.jpg">

        <div class="info">
            <h2 id="title">Select a Song</h2>
            <p id="artist">Artist</p>

            <div class="controls">
                <button id="prev">⏮</button>
                <button id="play">▶</button>
                <button id="next">⏭</button>
            </div>

            <div class="progress">
                <span id="cur">0:00</span>
                <input type="range" id="bar">
                <span id="dur">0:00</span>
            </div>
        </div>
    </div>

    <?php if($user->ads()): ?>
        <div class="ad">🔔 Premium users have no ads</div>
    <?php endif; ?>

</div>

<div class="playlist">
    <h3>Popular Songs</h3>
    <div id="list"></div>
</div>

</div>

<audio id="audio"></audio>

<script>
class Spotify{
    constructor(){
        this.audio=document.getElementById('audio');
        this.i=0;
        this.playing=false;

        this.songs=[
            {title:"Pag-Ibig ay Kanibalismo",artist:"fitterkarma",cover:"images/Kanibalismo.jpg",src:"songs/Kanibalismo.mp3"},
            {title:"Primadonna",artist:"Marina",cover:"images/Primadonna.jpg",src:"songs/Primadonna.mp3"},
            {title:"Still Into You",artist:"Paramore",cover:"images/Still_into_You.jpg",src:"songs/Still_into_You.mp3"},
            {title:"Jealous",artist:"Nick Jonas",cover:"images/Jealous.jpg",src:"songs/Jealous.mp3"},
            {title:"Miss Miss",artist:"Rob Daniel",cover:"images/Miss_miss.jpg",src:"songs/Miss_miss.mp3"}
        ];

        document.getElementById('list').innerHTML =
        this.songs.map((s,i)=>`
            <div class="song" onclick="app.select(${i})">
                <img src="${s.cover}">
                <div>
                    <h4>${s.title}</h4>
                    <p>${s.artist}</p>
                </div>
            </div>
        `).join('');

        this.events();
    }

    select(i){
        this.i=i;
        let s=this.songs[i];

        this.audio.src=s.src;
        title.innerText=s.title;
        artist.innerText=s.artist;
        cover.src=s.cover;

        document.querySelectorAll('.song')
        .forEach((e,n)=>e.classList.toggle('active',n==i));

        <?php if($user->ads()): ?>
        if(confirm("Watch ad to play?")) this.playSong();
        <?php else: ?>
        this.playSong();
        <?php endif; ?>
    }

    playSong(){
        this.audio.play();
        this.playing=true;
        play.innerText='⏸';
    }

    pauseSong(){
        this.audio.pause();
        this.playing=false;
        play.innerText='▶';
    }

    events(){
        play.onclick=()=>{
            if(!this.audio.src) return;
            this.playing?this.pauseSong():this.playSong();
        };

        next.onclick=()=>this.select((this.i+1)%this.songs.length);
        prev.onclick=()=>this.select((this.i-1+this.songs.length)%this.songs.length);

        this.audio.ontimeupdate=()=>{
            bar.value=(this.audio.currentTime/this.audio.duration)*100||0;
            cur.innerText=this.time(this.audio.currentTime);
        };

        this.audio.onloadedmetadata=()=>{
            dur.innerText=this.time(this.audio.duration);
        };

        bar.oninput=e=>{
            this.audio.currentTime=(e.target.value/100)*this.audio.duration;
        };

        this.audio.onended=()=>next.click();
    }

    time(s){
        let m=Math.floor(s/60);
        s=Math.floor(s%60);
        return `${m}:${String(s).padStart(2,'0')}`;
    }
}

const app=new Spotify();
</script>

<?php endif; ?>

</body>
</html>
