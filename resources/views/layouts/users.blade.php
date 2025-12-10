<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Sourires</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bungee+Spice&family=Momo+Trust+Display&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playwrite+US+Trad+Guides&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Tagesschrift&display=swap" rel="stylesheet">
</head>

<body>

<header class="glass-nav">
    <nav class="navbar">
        <h1 class="logo">Mission Sourires</h1>


        <a href="#contact" class="mobilemoney-btn">Contacter</a>
    </nav>
</header>


<main>
    @yield('content')
</main>

<footer id="contact">
    <p>&copy; Doriane ASSOGBA- HOUNDOKINNOU DIègue. Tous droits réservés. <br>
    Contactez nous via whatsApp au 0159090501 ou 0194119476</p>
</footer>
<script>
    // Date cible pour la collecte
    const targetDate = new Date("2025-12-24T00:00:00").getTime();

    const countdown = () => {
        const now = new Date().getTime();
        const distance = targetDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000*60*60));
        const minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
        const seconds = Math.floor((distance % (1000*60)) / 1000);

        document.getElementById("days").innerText = days < 10 ? "0"+days : days;
        document.getElementById("hours").innerText = hours < 10 ? "0"+hours : hours;
        document.getElementById("minutes").innerText = minutes < 10 ? "0"+minutes : minutes;
        document.getElementById("seconds").innerText = seconds < 10 ? "0"+seconds : seconds;

        if(distance < 0){
            clearInterval(timer);
            document.getElementById("countdown").innerHTML = "<p>La collecte a commencé !</p>";
        }
    }

    const timer = setInterval(countdown, 1000);
    countdown();
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
<script>
    function toggleMenu(){
        document.querySelector(".nav-links")
        .classList.toggle("active");
    }
</script>

</html>
