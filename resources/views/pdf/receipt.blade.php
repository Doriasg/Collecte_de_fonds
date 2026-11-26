<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Reçu de Contribution – Mission Sourire</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #ffffff;
        }

        /* ===== CADRE EXTÉRIEUR ===== */
        .outer-border {
            border: 3px solid #1a1a2e;
            margin: 10px;
            padding: 0;
        }

        .inner-border {
            border: 1px solid #c8a800;
            margin: 4px;
            padding: 0;
        }

        /* ===== EN-TÊTE ===== */
        .header {
            background: #1a1a2e;
            padding: 18px 24px 14px 24px;
            position: relative;
        }

        .header-top {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 25%;
        }

        .header-center {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 50%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 25%;
        }

        .org-name {
            font-size: 22px;
            font-weight: bold;
            color: #F3DE2C;
            letter-spacing: 1px;
            line-height: 1.1;
        }

        .org-name span {
            color: #ffffff;
        }

        .org-subtitle {
            font-size: 9px;
            color: #c8c8c8;
            letter-spacing: 2px;
            margin-top: 3px;
            text-transform: uppercase;
        }

        .edition-badge {
            display: inline-block;
            background: #F3DE2C;
            color: #1a1a2e;
            font-size: 9px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 10px;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        .partner-logos {
            font-size: 8px;
            color: #aaaaaa;
            text-align: right;
        }

        .partner-name {
            font-size: 11px;
            font-weight: bold;
            color: #F3DE2C;
            line-height: 1.4;
        }

        .partner-name-secondary {
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.4;
        }

        /* ===== BANDE DE TITRE ===== */
        .title-band {
            background: #F3DE2C;
            padding: 10px 24px;
            text-align: center;
        }

        .title-band h1 {
            font-size: 15px;
            font-weight: bold;
            color: #1a1a2e;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .title-band p {
            font-size: 9px;
            color: #333333;
            margin-top: 2px;
            letter-spacing: 1px;
        }

        /* ===== SOUS-TITRE ROUGE/ORANGE ===== */
        .subtitle-band {
            background: #2c3e50;
            padding: 6px 24px;
            text-align: center;
        }

        .subtitle-band p {
            font-size: 9px;
            color: #F3DE2C;
            letter-spacing: 1px;
            font-style: italic;
        }

        /* ===== SECTION NUMÉRO DE REÇU ===== */
        .receipt-number-bar {
            background: #f8f8f0;
            border-top: 2px solid #F3DE2C;
            border-bottom: 1px solid #e0e0e0;
            padding: 8px 24px;
            display: table;
            width: 100%;
        }

        .receipt-number-bar .left {
            display: table-cell;
            vertical-align: middle;
        }

        .receipt-number-bar .right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .receipt-ref {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
        }

        .receipt-date {
            font-size: 10px;
            color: #555;
        }

        /* ===== CORPS DU DOCUMENT ===== */
        .body-content {
            padding: 20px 24px;
        }

        /* Section donataire */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #F3DE2C;
            padding-bottom: 4px;
            margin-bottom: 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .info-table td {
            padding: 7px 10px;
            border: 1px solid #e8e8e8;
            font-size: 10.5px;
        }

        .info-table td.label {
            background: #f5f5f0;
            color: #555;
            font-weight: bold;
            width: 35%;
            font-size: 10px;
        }

        .info-table td.value {
            color: #1a1a2e;
            font-weight: bold;
        }

        .info-table td.value.highlight {
            color: #c8a800;
            font-size: 13px;
            font-weight: bold;
        }

        /* Montant principal */
        .amount-box {
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            border: 2px solid #F3DE2C;
            border-radius: 4px;
            padding: 16px 20px;
            text-align: center;
            margin: 16px 0;
        }

        .amount-label {
            font-size: 10px;
            color: #aaaaaa;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #F3DE2C;
            line-height: 1;
        }

        .amount-words {
            font-size: 9px;
            color: #cccccc;
            margin-top: 4px;
            font-style: italic;
        }

        /* Statut */
        .status-approved {
            display: inline-block;
            background: #27ae60;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 14px;
            border-radius: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Message de remerciement */
        .thanks-box {
            background: #fffdf0;
            border-left: 4px solid #F3DE2C;
            border: 1px solid #e8d800;
            padding: 14px 16px;
            margin: 16px 0;
        }

        .thanks-box p {
            font-size: 10px;
            color: #333;
            line-height: 1.6;
        }

        .thanks-box .thanks-title {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        /* QR / numéro de validation */
        .validation-row {
            display: table;
            width: 100%;
            margin-top: 16px;
            border: 1px solid #e0e0e0;
            background: #fafafa;
        }

        .validation-left {
            display: table-cell;
            vertical-align: middle;
            padding: 10px 14px;
            width: 70%;
        }

        .validation-right {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 10px 14px;
            width: 30%;
            border-left: 1px solid #e0e0e0;
        }

        .valid-code {
            font-size: 9px;
            font-family: monospace;
            color: #555;
            word-break: break-all;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #1a1a2e;
            padding: 10px 24px;
            margin-top: 10px;
        }

        .footer-table {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }

        .footer-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }

        .footer p {
            font-size: 8px;
            color: #aaaaaa;
            line-height: 1.6;
        }

        .footer .footer-org {
            font-size: 9px;
            color: #F3DE2C;
            font-weight: bold;
        }

        .footer .footer-slogan {
            font-size: 8px;
            color: #cccccc;
            font-style: italic;
        }

        /* ===== WATERMARK ===== */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 72px;
            font-weight: bold;
            color: rgba(243, 222, 44, 0.06);
            white-space: nowrap;
            z-index: 0;
            pointer-events: none;
        }

        .content-wrap {
            position: relative;
            z-index: 1;
        }

        /* ===== SÉPARATEURS ===== */
        .separator {
            height: 1px;
            background: #e0e0e0;
            margin: 10px 0;
        }

        .gold-line {
            height: 2px;
            background: #F3DE2C;
            margin: 0;
        }
    </style>
</head>
<body>

<div class="watermark">MISSION SOURIRE</div>

<div class="content-wrap">
<div class="outer-border">
<div class="inner-border">

    <!-- EN-TÊTE -->
    <div class="header">
        <div class="header-top">
            <div class="header-left">
                <div class="partner-name">CSCS</div>
                <div style="font-size:8px; color:#aaa; margin-top:2px;">Cercle de Solidarité<br>pour la Communauté et la Société</div>
            </div>
            <div class="header-center">
                <div class="org-name">MISSION <span>SOURIRE</span></div>
                <div class="org-subtitle">Collecte de Fonds Solidaire</div>
                <div class="edition-badge">2ᵉ ÉDITION – 2024/2025</div>
            </div>
            <div class="header-right">
                <div class="partner-name-secondary">IWA</div>
                <div style="font-size:8px; color:#aaa; margin-top:2px; text-align:right;">Initiatives des Femmes<br>pour l'Action</div>
            </div>
        </div>
    </div>

    <!-- BANDE OR -->
    <div class="gold-line"></div>

    <!-- TITRE DU DOCUMENT -->
    <div class="title-band">
        <h1>Reçu Officiel de Contribution</h1>
        <p>Document institutionnel – Conserver précieusement</p>
    </div>

    <!-- BANDE SOMBRE -->
    <div class="subtitle-band">
        <p>L'orphelinat La Providence – Pour chaque enfant, un avenir meilleur</p>
    </div>

    <!-- NUMÉRO DE REÇU -->
    <div class="receipt-number-bar">
        <div class="left">
            <span class="receipt-ref">N° {{ strtoupper($reference) }}</span>
        </div>
        <div class="right">
            <span class="receipt-date">Date : {{ $date }}</span>
        </div>
    </div>

    <!-- CORPS -->
    <div class="body-content">

        <!-- SECTION 1 : Donataire -->
        <div class="section-title">Informations du Contributeur</div>
        <table class="info-table">
            <tr>
                <td class="label">Nom & Prénom(s)</td>
                <td class="value">{{ strtoupper($lastname) }} {{ ucfirst($firstname) }}</td>
            </tr>
            <tr>
                <td class="label">Adresse e-mail</td>
                <td class="value">{{ $email }}</td>
            </tr>
            <tr>
                <td class="label">Téléphone</td>
                <td class="value">{{ $phone }}</td>
            </tr>
        </table>

        <!-- MONTANT PRINCIPAL -->
        <div class="amount-box">
            <div class="amount-label">Montant de la contribution</div>
            <div class="amount-value">{{ $amount }} {{ $currency }}</div>
            <div class="amount-words">Reçu avec succès via {{ $method }}</div>
        </div>

        <!-- SECTION 2 : Détails du paiement -->
        <div class="section-title">Détails de la Transaction</div>
        <table class="info-table">
            <tr>
                <td class="label">Référence</td>
                <td class="value" style="font-family: monospace;">{{ $reference }}</td>
            </tr>
            <tr>
                <td class="label">ID Transaction</td>
                <td class="value" style="font-family: monospace;">{{ $payment->transaction_id }}</td>
            </tr>
            <tr>
                <td class="label">Mode de paiement</td>
                <td class="value">{{ $method }}</td>
            </tr>
            <tr>
                <td class="label">Statut</td>
                <td class="value">
                    <span class="status-approved">Validé</span>
                </td>
            </tr>
            <tr>
                <td class="label">Date &amp; Heure</td>
                <td class="value">{{ $date }}</td>
            </tr>
        </table>

        <!-- MESSAGE DE REMERCIEMENT -->
        <div class="thanks-box">
            <div class="thanks-title">Message de l'équipe MISSION SOURIRE</div>
            <p>
                Cher(e) <strong>{{ ucfirst($firstname) }} {{ strtoupper($lastname) }}</strong>,<br><br>
                Nous vous adressons nos plus sincères remerciements pour votre précieuse contribution à la campagne
                <strong>Mission Sourire – 2ᵉ Édition</strong>. Grâce à votre geste généreux, vous participez activement
                à apporter du sourire, de la dignité et de l'espoir aux enfants de l'orphelinat
                <strong>La Providence</strong>.<br><br>
                Chaque don, aussi modeste soit-il, représente un acte fort de solidarité humaine.
                Votre soutien nous touche profondément et nous donne la force de continuer cette belle mission.<br><br>
                <em>Un don, un geste, un sourire.</em>
            </p>
        </div>

        <!-- VALIDATION -->
        <div class="validation-row">
            <div class="validation-left">
                <p style="font-size:9px; color:#555; margin-bottom:4px;">
                    <strong>Code de validation :</strong>
                </p>
                <div class="valid-code">{{ strtoupper(hash('crc32', $reference . $payment->transaction_id . $payment->amount)) }}-{{ strtoupper(substr($reference, -6)) }}-MS2E</div>
                <p style="font-size:8px; color:#999; margin-top:4px;">
                    Ce document tient lieu de reçu officiel de don et peut être présenté à des fins fiscales.
                </p>
            </div>
            <div class="validation-right">
                <div style="font-size:8px; color:#999; text-align:center; margin-bottom:4px;">Tampon officiel</div>
                <div style="border: 2px dashed #cccccc; width: 70px; height: 50px; margin: auto;
                            display: flex; align-items: center; justify-content: center;
                            font-size: 7px; color: #cccccc; text-align: center;">
                    CSCS &amp; IWA
                </div>
            </div>
        </div>

    </div>

    <!-- BANDE OR BASSE -->
    <div class="gold-line"></div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-table">
            <div class="footer-left">
                <p class="footer-org">MISSION SOURIRE – CSCS &amp; IWA</p>
                <p class="footer-slogan">« Un don, un geste, un sourire ! »</p>
                <p style="margin-top:4px;">
                    Ce reçu a été généré automatiquement le {{ now()->format('d/m/Y à H:i') }}.
                    Réf. système : {{ $payment->id }}-{{ date('Y') }}
                </p>
            </div>
            <div class="footer-right">
                <p>Document officiel</p>
                <p>Non modifiable</p>
                <p style="margin-top:4px; color:#F3DE2C;">CSCS &amp; IWA – {{ date('Y') }}</p>
            </div>
        </div>
    </div>

</div><!-- /inner-border -->
</div><!-- /outer-border -->
</div><!-- /content-wrap -->

</body>
</html>
