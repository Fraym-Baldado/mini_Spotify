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

    function ads(){
        return true;
    }
}

class Premium extends Account{
    function __construct($u){
        parent::__construct($u,'Premium');
    }

    function ads(){
        return false;
    }
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
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Mini Spotify</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI;
}

body{
    background:#fff;
    min-height:100vh;
    display:flex;
    justify-content:center;
    padding:15px;
}

/* LOGIN */

.login{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.75);
    display:grid;
    place-items:center;
}

.box{
    width:100%;
    max-width:340px;
    background:#132129;
    color:#fff;
    padding:25px;
    border-radius:25px;
    text-align:center;
}

.box input,
.box button{
    width:100%;
    padding:12px;
    margin:8px 0;
    border:none;
    border-radius:30px;
}

.box button{
    background:#1db954;
    color:#fff;
    cursor:pointer;
    font-weight:700;
}

/* APP */

.app{
    width:100%;
    max-width:650px;
    padding:14px;
    border-radius:35px;
    background:linear-gradient(135deg,#08131d,#17313c,#2ecc71);
    border:2px solid rgba(255,255,255,.15);
    box-shadow:0 10px 30px rgba(0,0,0,.35);
    color:#fff;
}

header,
.player,
.playlist{
    background:rgba(0,0,0,.22);
    border:1px solid rgba(255,255,255,.08);
    border-radius:28px;
    padding:18px;
    margin-bottom:15px;
}

header,
.now,
.controls,
.progress,
.song{
    display:flex;
    align-items:center;
}

header{
    justify-content:space-between;
}

.logo{
    font-size:1.6rem;
    font-weight:700;
    color:#1ed760;
}

.logout{
    background:#1db954;
    color:#fff;
    text-decoration:none;
    padding:8px 14px;
    border-radius:20px;
    font-size:.9rem;
}

/* PLAYER */

.now{
    gap:18px;
}

#cover{
    width:180px;
    height:180px;
    object-fit:cover;
    border-radius:22px;
}

.info{
    flex:1;
}

#title{
    font-size:1.5rem;
    margin-bottom:6px;
}

#artist{
    opacity:.8;
    margin-bottom:20px;
}

.controls{
    gap:12px;
    margin-bottom:20px;
}

.controls button{
    width:55px;
    height:55px;
    border:none;
    border-radius:50%;
    background:rgba(255,255,255,.15);
    color:#fff;
    cursor:pointer;
    font-size:1.1rem;
}

#play{
    width:72px;
    height:72px;
    background:#1db954;
    font-size:1.6rem;
}

.progress{
    gap:10px;
}

.progress input{
    width:100%;
}

.ad{
    background:#1db954;
    margin-top:15px;
    padding:10px;
    border-radius:12px;
    text-align:center;
    font-size:.9rem;
}

/* PLAYLIST */

.playlist h3{
    margin-bottom:15px;
}

.song{
    gap:12px;
    padding:10px;
    border-radius:16px;
    margin-bottom:10px;
    background:rgba(255,255,255,.08);
    cursor:pointer;
    transition:.2s;
}

.song:hover,
.active{
    background:#1db954;
}

.song img{
    width:60px;
    height:60px;
    border-radius:12px;
    object-fit:cover;
}

.song p{
    opacity:.8;
    font-size:.85rem;
}

/* PHONE */

@media(max-width:600px){

    .app{
        padding:10px;
        border-radius:28px;
    }

    header,
    .now{
        flex-direction:column;
        text-align:center;
    }

    #cover{
        width:190px;
        height:190px;
    }

    #title{
        font-size:1.2rem;
    }

    .controls{
        justify-content:center;
    }

    .controls button{
        width:48px;
        height:48px;
    }

    #play{
        width:65px;
        height:65px;
    }

    .song img{
        width:55px;
        height:55px;
    }
}

</style>
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

    <?php
    if(isset($error))
        echo "<p style='color:#ff6b6b;margin-top:10px'>$error</p>";
    ?>

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

<!-- PLAYER -->

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

<!-- PLAYLIST -->

<div class="playlist">

    <h3>Popular Songs</h3>

    <div id="list"></div>

</div>

</div>

<audio id="audio"></audio>

<script>

class Spotify{

    constructor(){

        this.audio=audio;
        this.i=0;
        this.playing=false;

        this.songs=[

            {
                title:"Pag-Ibig ay Kanibalismo",
                artist:"fitterkarma",
                cover:"images/Kanibalismo.jpg",
                src:"songs/Kanibalismo.mp3"
            },

            {
                title:"Primadonna",
                artist:"Marina",
                cover:"images/Primadonna.jpg",
                src:"songs/Primadonna.mp3"
            },

            {
                title:"Still Into You",
                artist:"Paramore",
                cover:"images/Still_into_You.jpg",
                src:"songs/Still_into_You.mp3"
            },

            {
                title:"Jealous",
                artist:"Nick Jonas",
                cover:"images/Jealous.jpg",
                src:"songs/Jealous.mp3"
            },

            {
                title:"Miss Miss",
                artist:"Rob Daniel",
                cover:"images/Miss_miss.jpg",
                src:"songs/Miss_miss.mp3"
            }
        ];

        list.innerHTML=this.songs.map((s,i)=>`

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
        .forEach((e,n)=>
            e.classList.toggle('active',n==i)
        );

        <?php if($user->ads()): ?>

        if(confirm("Watch ad to play?"))
            this.playSong();

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

            this.playing
            ? this.pauseSong()
            : this.playSong();
        };

        next.onclick=()=>this.select(
            (this.i+1)%this.songs.length
        );

        prev.onclick=()=>this.select(
            (this.i-1+this.songs.length)%this.songs.length
        );

        this.audio.ontimeupdate=()=>{

            bar.value=
            (this.audio.currentTime/
            this.audio.duration)*100||0;

            cur.innerText=
            this.time(this.audio.currentTime);
        };

        this.audio.onloadedmetadata=()=>
            dur.innerText=this.time(this.audio.duration);

        bar.oninput=e=>
            this.audio.currentTime=
            (e.target.value/100)*this.audio.duration;

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
