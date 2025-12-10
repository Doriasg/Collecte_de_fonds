@extends('layouts.users')

@section('content')

<section class="hero">
    <div class="hero-text">

        <h1>Collecte de dons</h1>

        <p>
            Aidons les enfants de l’orphelinat de Nabouba à grandir dans la dignité.<br>
            Ensemble, changeons des vies.
        </p>

        <a href=" {{route('payment.create')}}" class="cta-btn">Faites un don</a>
    </div>

    <img src="{{asset('images/enfant.png')}}" class="hero-img" alt="Enfant">

</section>
<section class="collab-section">
    <div class="container">
        <h2 style="color: #FFC800;">C'est quoi Mission sourire ?</h2>
        
          <strong>Mission Sourires</strong>  est une action solidaire menée pour offrir de la joie, du soutien et un peu de douceur aux enfants de l’orphelinat de Nabouba, surtout pendant la période de Noël.

À travers des cadeaux, des vivres, des fournitures scolaires, des vêtements et d’autres dons, nous voulons leur rappeler qu’ils comptent, qu’ils sont aimés et qu’ils méritent de sourire comme tous les enfants du monde.

C’est aussi une occasion de sensibiliser, d’encourager l’entraide et de montrer qu’ensemble, nous pouvons changer quelque chose — même avec un geste simple.</p>
       
        <p class="vote-text">
            Votre soutien compte ! <strong>Faites un don et participez à changer des vies !</strong>
        </p>
    </div>
</section>

<section class="countdown-section">
    <div class="countdown-container">
        <h2>Fin de la collecte dans :</h2>
        <div id="countdown">
            <div class="time-box">
                <span id="days">00</span>
                <p>Jours</p>
            </div>
            <div class="time-box">
                <span id="hours">00</span>
                <p>Heures</p>
            </div>
            <div class="time-box">
                <span id="minutes">00</span>
                <p>Minutes</p>
            </div>
            <div class="time-box">
                <span id="seconds">00</span>
                <p>Secondes</p>
            </div>
        </div>
    </div>
</section>
<section class="partenaires" >
    <h1>Nos partenaires</h1>
    <div class="partenaires-img"><div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class=" carousel-item active " data-bs-interval="2000">
      <img src="{{asset('images/2-1.png')}}" class="d-block images w-100 " alt="INSTI's Women in Action">
    </div>
    <div class="carousel-item" data-bs-interval="2000">
      <img src="{{asset('images/DGlink.png')}}" class="d-block w-100 images" alt="DGlink">
    </div>
    <div class="carousel-item">
      <img src="{{asset('images/chorale.jpg')}}" class="d-block w-100 images" style="max-width: 500px;" alt="Chorale Ste Cathérine de Sienne">
    </div>
  </div>
  <button class="carousel-control-prev black" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden" style="color: red;">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div></div>
    
</section>
@endsection
<style>
    /* ---------- GLOBAL ---------- */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
   
    color: #1a1919;
}

a {
    text-decoration: none;
    color: inherit;
}
.hero h1{
    font-size: 100px;
}
/* ---------- NAVBAR ---------- */
.glass-nav {
    position: sticky;
    top: 0;
    width: 100%;
    z-index: 100;
    
    background: rgba(255, 255, 255, 0.5);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 5%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.navbar{
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}
.logo {
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(90deg, #F3DE2C, #D68D25);
    -webkit-background-clip: text;
    color: transparent;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 2rem;
}

.nav-links a {
    position: relative;
    font-weight: 500;
    transition: 0.3s;
}

.nav-links a::after {
    content: "";
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 0;
    height: 2px;
    background: #F3DE2C;
    transition: width 0.3s;
}

.nav-links a:hover::after {
    width: 100%;
}

/* Bouton Mobile Money */
.mobilemoney-btn {
    padding: 8px 20px;
    border-radius: 8px;
    background: linear-gradient(90deg, #FFC800, #F3DE2C);
    font-weight: 700;
    color: #000;
    transition: 0.3s;
}
.mobilemoney-btn:hover {
    filter: brightness(0.85);
}

/* ---------- HERO ---------- */
.hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 4rem 5%;
    gap: 2rem;
    flex-wrap: wrap;
     background: #fff8e1;
}



.hero-text h1 {
    font-size: 4rem;
    font-weight: bold;
    margin: 0;
    font-family: 'Playwrite USA Traditional Guides', serif;
}

.hero-text p {
    font-size: 1.3rem;
    margin: 1rem 0;
}

.cta-btn {
    display: inline-block;
    margin-top: 1.5rem;
    background: #F3DE2C;
    color: #000;
    font-weight: bold;
    padding: 12px 28px;
    border-radius: 8px;
    transition: 0.3s;
}
main a{
    text-decoration: none;
}
.cta-btn:hover {
    filter: brightness(0.9);
}

.hero-img {
    width: 400px;
    max-width: 100%;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 950px) {
    .hero {
        flex-direction: column-reverse;
        text-align: center;
        gap: 2rem;
    }

    .hero-img {
        width: 300px;
    }

    .nav-links {
        flex-direction: column;
        gap: 1rem;
        display: none;
    }

    .nav-links.active {
        display: flex;
    }
}
.countdown-section {
    padding: 4rem 5%;
    text-align: center;
    color: #1a1919;
}

.countdown-section h2 {
    font-size: 2.5rem;
    margin-bottom: 2rem;
    font-weight: bold;
}

#countdown {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.time-box {
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(8px);
    border-radius: 12px;
    padding: 1.5rem 2rem;
    min-width: 100px;
    transition: transform 0.3s ease;
}

.time-box:hover {
    transform: scale(1.05);
}

.time-box span {
    display: block;
    font-size: 2.5rem;
    font-weight: bold;
}

.time-box p {
    margin: 0;
    font-size: 1.1rem;
    color: #D68D25;
}
.time-box span {
    font-size: 60px !important;
}
.collab-section {
    background-color: #fcfae8;
    padding: 4rem 5%;
    text-align: center;
    color: #1a1919;
}

.collab-section h2 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 1.5rem;
}

.collab-section p {
    font-size: 1.2rem;
    line-height: 1.6;
    margin: 1rem 0;
}

.collab-section .cta-btn {
    margin-top: 2rem;
    background: linear-gradient(90deg, #FFC800, #F3DE2C);
    color: #000;
    padding: 12px 28px;
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}

.collab-section .cta-btn:hover {
    filter: brightness(0.85);
}

.vote-text {
    margin-top: 1.5rem;
    font-size: 1.1rem;
    font-weight: 500;
    color: #1a1919;
}

/* Responsive */
@media (max-width: 768px) {
    .collab-section h2 {
        font-size: 2rem;
    }

    .collab-section p {
        font-size: 1.5rem;
    }

    .collab-section .cta-btn {
        padding: 10px 20px;
        font-size: 1.5rem;
    }
}
.partenaires{
    text-align: center;
    

}
nav a{
    text-decoration: none;
}
.partenaires-img{
display: flex;
    align-items: center;
    justify-content: center;
}
.images{
    width: 100%;
    height: 30%;
    
}

#carouselExampleDark{
    display: flex;
    align-items: center;
}
footer{
    text-align: center;
    padding: 1rem 0;
    background-color: #fcfae8;
    color: #1a1919;
}
/* ------- RESPONSIVE PAYMENT PAGE ------- */

@media (max-width: 1024px) {

    .payment-card {
        padding: 20px !important;
    }
}

@media (max-width: 900px) {
    .grid {
        display: block !important;
    }

    .payment-card {
        margin-bottom: 2rem;
    }

    .payment-card h2 {
        text-align: center;
    }

    .amount-option {
        padding: 14px !important;
    }

    .space-y-6 {
        margin-top: 2rem;
    }
}

@media (max-width: 600px) {

    body {
        padding: 10px !important;
    }

    .payment-card, 
    .bg-white,
    .bg-gradient-to-r {
        padding: 15px !important;
    }

    input, button {
        font-size: 14px !important;
    }

    .text-4xl {
        font-size: 1.8rem !important;
    }
}

@media (max-width:480px){

    .amount-option{
        font-size: 14px;
        padding: 10px;
    }

    button {
        padding: 12px !important;
        font-size: 14px !important;
    }
}


</style>