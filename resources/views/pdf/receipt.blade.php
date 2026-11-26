<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Reçu de Don – Mission Sourire</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #111;
            background: #fff;
            padding: 20px 25px 15px 25px;
        }

        /* EN-TÊTE */
        .header { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .header td { vertical-align: top; padding: 0 6px; }
        .col-l  { width: 22%; }
        .col-m  { width: 56%; text-align: center; }
        .col-r  { width: 22%; text-align: right; }
        .logo-side   { width: 60px; height: 60px; }
        .logo-center { width: 72px; height: 72px; }
        .partner-name { font-size: 9px; font-weight: bold; color: #111; line-height: 1.4; margin-top: 3px; }
        .title-main   { font-size: 16px; font-weight: bold; color: #111; letter-spacing: 1.5px; margin: 4px 0 2px 0; }
        .title-sub    { font-size: 8.5px; color: #333; line-height: 1.6; }

        /* SÉPARATEURS */
        .sep-double { border-top: 3px double #111; margin: 7px 0 3px 0; }
        .sep-single { border-top: 1px solid #111; margin: 3px 0 6px 0; }
        .sep-thin   { border-top: 1px solid #ccc; margin: 5px 0; }

        /* TITRE */
        .doc-row { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .doc-row td { vertical-align: top; padding: 0; }
        .doc-left  { width: 55%; }
        .doc-right { width: 45%; text-align: right; }
        .edition-label { font-size: 10px; font-weight: bold; color: #111; line-height: 1.6; }
        .edition-sub   { font-size: 9px; font-weight: normal; color: #444; }
        .recu-number   { font-size: 15px; font-weight: bold; color: #111; }
        .recu-date     { font-size: 11px; color: #111; margin-top: 3px; }

        /* CONTRIBUTEUR */
        .contrib-block { margin: 8px 0 5px 0; font-size: 11.5px; line-height: 2; }

        /* TABLEAU */
        .trx-table { width: 100%; border-collapse: collapse; margin: 7px 0; font-size: 11px; }
        .trx-table th {
            background: #555; color: #fff; padding: 6px 10px;
            font-size: 11px; font-weight: bold; border: 1px solid #555;
        }
        .trx-table th.r { text-align: right; }
        .trx-table td   { padding: 7px 10px; border: 1px solid #bbb; vertical-align: top; background: #fff; }
        .trx-table td.mode { width: 16%; }
        .trx-table td.ref  { width: 65%; }
        .trx-table td.amt  { width: 19%; text-align: right; font-weight: bold; }
        .ref-bold { font-weight: bold; }
        .ref-sub  { font-size: 9.5px; color: #444; }

        /* TOTAL */
        .total-row td { padding: 5px 0; }
        .total-label  { text-align: right; font-size: 11px; }
        .total-value  { text-align: right; font-size: 13px; font-weight: bold; padding-right: 10px; width: 19%; }

        /* ARRÊTÉ */
        .arrete { font-size: 11.5px; margin: 10px 0 14px 0; line-height: 1.7; }

        /* QR + SIGNATURE */
        .bottom    { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .bottom td { vertical-align: top; padding: 0 8px; }
        .col-qr    { width: 30%; }
        .col-sig   { width: 70%; text-align: right; }
        .qr-img    { width: 110px; height: 110px; }
        .qr-label  { font-size: 7.5px; color: #555; margin-top: 3px; line-height: 1.4; }
        .sig-title { font-size: 10.5px; color: #111; margin-bottom: 32px; }
        .sig-line  { border-top: 1px solid #aaa; width: 180px; margin: 0 0 4px auto; }
        .sig-name  { font-size: 11.5px; font-weight: bold; color: #111; }

        /* FOOTER */
        .footer   { margin-top: 16px; border-top: 1px solid #ccc; padding-top: 5px; }
        .footer-t { width: 100%; border-collapse: collapse; }
        .footer-t td { font-size: 8px; color: #444; padding: 0; vertical-align: middle; }
        .footer-t td:last-child { text-align: right; }
        .flag-g { display: inline-block; width: 12px; height: 7px; background: #008751; }
        .flag-y { display: inline-block; width: 12px; height: 7px; background: #FCD116; }
        .flag-r { display: inline-block; width: 12px; height: 7px; background: #E8112D; }

        /* FILIGRANE */
        .watermark {
            position: fixed; top: 38%; left: 5%;
            font-size: 68px; font-weight: bold;
            color: rgba(0,0,0,0.04);
            transform: rotate(-38deg);
            white-space: nowrap; letter-spacing: 4px; z-index: -1;
        }
    </style>
</head>
<body>

<div class="watermark">MISSION SOURIRE</div>

{{-- EN-TÊTE --}}
<table class="header">
    <tr>
        <td class="col-l">
            @if(!empty($logo_dglink))
                <img src="{{ $logo_dglink }}" class="logo-side" alt="DGlink"/>
            @endif
            <div class="partner-name">DGlink</div>
        </td>
        <td class="col-m">
            @if(!empty($logo_iwa))
                <img src="{{ $logo_iwa }}" class="logo-center" alt="INSTI's Women in Action"/>
            @endif
            <div class="title-main">MISSION SOURIRE</div>
            <div class="title-sub">
                <strong>Orphelinat La Providence &mdash; Bénin</strong><br>
                Email : instiswomeninaction@gmail.com<br>
                Organisation à but non lucratif
            </div>
        </td>
        <td class="col-r">
            @if(!empty($logo_chorale))
                <img src="{{ $logo_chorale }}" class="logo-side" style="float:right;" alt="Chorale Ste Cathérine de Sienne"/>
            @endif
            <div class="partner-name" style="text-align:right; clear:both;">Chorale Ste Cathérine de Sienne</div>
        </td>
    </tr>
</table>

<div class="sep-double"></div>
<div class="sep-single"></div>

{{-- TITRE + NUMÉRO + DATE --}}
<table class="doc-row">
    <tr>
        <td class="doc-left">
            <div class="edition-label">
                MISSION SOURIRE &mdash; 2&egrave;me &Eacute;DITION<br>
                <span class="edition-sub">Collecte de Fonds Solidaire &mdash; D&eacute;cembre 2026</span>
            </div>
        </td>
        <td class="doc-right">
            <div class="recu-number">Reçu N° {{ strtoupper(substr($reference, -16)) }}</div>
            <div class="recu-date">Date : <strong>{{ $date }} (GMT+1)</strong></div>
        </td>
    </tr>
</table>

<div class="sep-thin"></div>

{{-- PARTIE VERSANTE --}}
<div class="contrib-block">
    <div>Partie versante : <strong>{{ strtoupper($lastname) }} {{ ucfirst($firstname) }} ({{ $phone }})</strong></div>
    <div>Mode de contribution : <strong>GUICHET DE PAIEMENT NUMÉRIQUE</strong></div>
</div>

{{-- TABLEAU TRANSACTION --}}
<table class="trx-table">
    <thead>
        <tr>
            <th class="mode">Mode</th>
            <th class="ref">Référence</th>
            <th class="amt r">Montant</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="mode">{{ $method }}</td>
            <td class="ref">
                <span class="ref-bold">{{ strtoupper($payment->transaction_id) }}</span>
                – MISSION SOURIRE / DON SOLIDAIRE<br>
                <span class="ref-sub">({{ $method }})</span>
            </td>
            <td class="amt">{{ $amount }}</td>
        </tr>
    </tbody>
</table>

{{-- TOTAL --}}
<table style="width:100%; border-collapse:collapse;">
    <tr class="total-row">
        <td class="total-label">Montant versé</td>
        <td class="total-value">{{ $amount }}</td>
    </tr>
</table>

<div class="sep-thin"></div>

{{-- ARRÊTÉ --}}
<div class="arrete">
    Arrêté la présente quittance à la somme de :
    <strong>{{ \App\Helpers\NumberHelper::toWords((int) preg_replace('/\D/', '', $amount)) }} ({{ $amount }}) FCFA</strong>
</div>

{{-- QR CODE + SIGNATURE --}}
<table class="bottom">
    <tr>
        <td class="col-qr">
            @if(!empty($qr_base64))
                <img src="{{ $qr_base64 }}" class="qr-img" alt="QR Code"/>
            @else
                <div style="width:110px;height:110px;border:1px dashed #ccc;display:table-cell;vertical-align:middle;text-align:center;font-size:8px;color:#aaa;">
                    QR indisponible
                </div>
            @endif
            <div class="qr-label">Scanner pour vérifier<br>ce reçu en ligne</div>
        </td>
        <td class="col-sig">
            <div class="sig-title">L'équipe MISSION SOURIRE</div>
            <div class="sig-line"></div>
            <div class="sig-name">Mission Sourire</div>
        </td>
    </tr>
</table>

{{-- FOOTER --}}
<div class="footer">
    <table class="footer-t">
        <tr>
            <td>
                <span class="flag-g"></span><span class="flag-y"></span><span class="flag-r"></span>
                &nbsp; eReçu : {{ $date }} (GMT+1)
            </td>
            <td style="text-align:center; color:#888;">
                Réf. : {{ strtoupper($reference) }}
            </td>
            <td>
                <span class="flag-g"></span><span class="flag-y"></span><span class="flag-r"></span>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
