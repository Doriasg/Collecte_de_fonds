@extends('layouts.users')

@section('content')

<section class="hero">
    <div class="hero-text">

        <h1>Collecte de dons</h1>

        <p>
            Aidons les enfants de l’orphelinat de Nabouba à grandir dans la dignité.<br>
            Ensemble, changeons des vies.
        </p>

        <a href=" {{route('paiement')}}" class="cta-btn">Faites un don</a>
    </div>

    <img src="{{asset('images/enfant.png')}}" class="hero-img" alt="Enfant">

</section>
<section class="collab-section">
    <div class="container">
        <h2 style="color: #FFC800;">C'est quoi Mission sourire ?</h2>
        <p>
          <strong>Mission Sourires</strong>  est une action solidaire menée pour offrir de la joie, du soutien et un peu de douceur aux enfants de l’orphelinat de Nabouba, surtout pendant la période de Noël.

À travers des cadeaux, des vivres, des fournitures scolaires, des vêtements et d’autres dons, nous voulons leur rappeler qu’ils comptent, qu’ils sont aimés et qu’ils méritent de sourire comme tous les enfants du monde.

C’est aussi une occasion de sensibiliser, d’encourager l’entraide et de montrer qu’ensemble, nous pouvons changer quelque chose — même avec un geste simple.</p>
        <a href="{{route('paiement')}}" class="cta-btn">Cotas actuel</a>
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
