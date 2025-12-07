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
        <h2 style="color: #FFC800;">Collaboration pour un Noël joyeux</h2>
        <p>
            <strong>L' INSTI's Women in Action</strong> et la <strong>Chorale de l'Aumônerie Saint Augustin de Lokossa</strong> unissent leurs forces pour offrir des sourires aux enfants de l’orphelinat de Nabouba cette Noël !
        </p>
        <p>
            Nous acceptons vos <strong>dons en nature</strong> : vivres, cahiers, livres, vêtements… ou <strong>dons en espèces</strong> pour contribuer à leur bonheur.
        </p>
        <a href="{{route('payment.create')}}" class="cta-btn">Faire un don</a>
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
<section class="partenaires">
    <h1>Nos partenaires</h1>
    <div id="carouselExampleDark" class="carousel carousel-dark slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="10000">
      <img src="{{asset('images/2-1.png')}}" class="d-block w-50" alt="Partenaire 1">
      <div class="carousel-caption d-none d-md-block">
        
        <p>INSTI's Women in Action</p>
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="2000">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Second slide label</h5>
        <p>Some representative placeholder content for the second slide.</p>
      </div>
    </div>
    
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</section>
@endsection
