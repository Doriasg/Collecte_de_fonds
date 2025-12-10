<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paiement en ligne</title>

<link rel="stylesheet" href="/css/payment.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

<div class="container">

    <div class="card">

        <div class="header">
            <h1><i class="fas fa-lock"></i> Paiement Sécurisé</h1>
            <p>Remplissez le formulaire pour procéder au paiement</p>
        </div>

        <form action="{{ route('payment.process') }}" method="POST" class="form">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-money-bill-wave"></i> Montant (FCFA)</label>
                <input type="number" name="amount" value="{{ old('amount',1000) }}">
            </div>

            <div class="row">

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Prénom</label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nom</label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}">
                </div>

            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-phone"></i> Téléphone</label>
                <div class="phone">
                    <span>+229</span>
                    <input type="text" name="phone" placeholder="90123456">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-alt"></i> Description</label>
                <textarea name="description"></textarea>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-credit-card"></i> Procéder au paiement
            </button>

            <p class="secure">
                <i class="fas fa-shield-alt"></i>
                Paiement 100% sécurisé par FedaPay
            </p>
                  
            <a class="secure" href="{{ route('home') }}">
                Retourner à l'accueil
            </a>

        </form>

    </div>

</div>

</body>
</html>
<style>
    body{
    background:#fafafa;
    font-family: 'Roboto', sans-serif;
}

.container{
    max-width:500px;
    margin:auto;
    padding:40px 15px;
}

.card{
    background:#fff;
    border-radius:10px;
    box-shadow:0 3px 15px rgba(0,0,0,0.1);
    overflow:hidden;
}

.header{
    background:#F3DE2C;
    padding:20px;
    text-align:center;
}

.header h1{
    margin:0;
    color:#111;
}

.header p{
    font-size:14px;
    color:#333;
}

.form{
    padding:25px;
}

.form-group{
    margin-bottom:18px;
}

label{
    font-weight:bold;
    font-size:14px;
    margin-bottom:6px;
    display:block;
    color:#333;
}

input, textarea{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    font-size:15px;
}

.row{
    display:flex;
    gap:12px;
}

.phone{
    display:flex;
}

.phone span{
    background:#eee;
    padding:12px;
    border:1px solid #ddd;
    border-right:none;
    border-radius:8px 0 0 8px;
}

.phone input{
    border-radius:0 8px 8px 0;
}

.btn{
    width:100%;
    background:#F3DE2C;
    color:#000;
    padding:14px;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    font-weight:bold;
    transition:.2s;
}

.btn:hover{
    background:#e2cd24;
}

.secure{
    text-align:center;
    margin-top:15px;
    font-size:13px;
    color:#444;
}

</style>