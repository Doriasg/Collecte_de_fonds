<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Soutien - Paiement Sécurisé</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<div class="container">

    <header class="header">
        <h1><i class="fas fa-heart"></i> Soutenez notre projet</h1>
        <p>Votre contribution nous aide à continuer notre mission.</p>
    </header>


    <main class="grid">

        <form id="paymentForm" class="card">
            <h2><i class="fas fa-credit-card"></i> Informations de paiement</h2>

            <label>Montant :</label>
            <div class="amount-box">

                <div class="amount-option" data-amount="1000">1 000 FCFA</div>
                <div class="amount-option" data-amount="5000">5 000 FCFA</div>
                <div class="amount-option" data-amount="10000">10 000 FCFA</div>
                <div class="amount-option" data-amount="20000">20 000 FCFA</div>

                <input type="hidden" id="amount" value="1000" name="amount">

            </div>

            <label>Montant personnalisé :</label>
            <input type="number" id="customAmount" placeholder="Entrez un montant">
            

            <label>Nom</label>
            <input type="text" name="full_name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Téléphone</label>
            <input type="tel" name="phone" required>

            <button type="submit" id="submitBtn">
                <i class="fas fa-lock"></i> Payer <span id="amountDisplay">(1000 FCFA)</span>
            </button>

        </form>


        <aside class="infos card">
            <h3>Méthodes acceptées</h3>
            <p><i class="fab fa-cc-mastercard"></i> Carte bancaire</p>
            <p><i class="fas fa-mobile"></i> Mobile Money</p>
        </aside>

    </main>

</div>

<script src="./script.js"></script>

</body>
<script>
    document.querySelectorAll('.amount-option').forEach(option => {
    option.addEventListener('click', function(){
        document.querySelectorAll('.amount-option').forEach(opt=>{
            opt.classList.remove('selected');
        })
        this.classList.add('selected');

        let amount = this.getAttribute('data-amount');
        document.getElementById('amount').value = amount;
        document.getElementById('amountDisplay').innerText = `(${amount} FCFA)`
        document.getElementById('customAmount').value = "";
    })
})

document.getElementById('customAmount').addEventListener('input', function(){
    document.querySelectorAll('.amount-option').forEach(opt=>{
        opt.classList.remove('selected');
    })
    document.getElementById('amount').value = this.value;
})

</script>
</html>
<style>
    body{
    background:#f3f6ff;
    font-family:'Roboto', sans-serif;
    padding:30px;
}

.container{
    max-width:1100px;
    margin:auto;
}

.header h1{
    font-size:34px;
    text-align:center;
    color:#222;
}

.grid{
    display:flex;
    gap:30px;
    margin-top:30px;
}

.card{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,.05);
    flex:1;
}

.card h2{
    margin-bottom:20px;
    font-size:22px;
    color:#444;
}

label{
    display:block;
    margin-top:15px;
    margin-bottom:6px;
    font-weight:bold;
    color:#444;
}

input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
}

.amount-box{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.amount-option{
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    cursor:pointer;
    transition:.2s;
}

.amount-option:hover{
    background:#F3DE2C;
}

.amount-option.selected{
    background:#F3DE2C;
    color:white;
    border-color:#F3DE2C;
}

button{
    width:100%;
    margin-top:25px;
    padding:14px;
    border:none;
    font-size:16px;
    background:#F3DE2C;
    color:white;
    border-radius:10px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#0037d6;
}

</style>