<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>

    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Georgia, serif;
            font-size: 24px;
            text-align: center;
        }
        .container {
            border: 15px solid tan;
            width: 750px;
            height: 563px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .logo,.marquee,.assignment,.person,.reason {
            text-align: center;
            margin: 10px;
        }
        .logo {
            color: tan;
            font-size: 36px; /* Adjusted for better visibility */
        }
        .marquee {
            color: tan;
            font-size: 45px;
        }
        .assignment {
            font-size: 21px; /* Adjusted for readability */
        }
        .person {
            border-bottom: 2px solid black;
            font-size: 32px;
            font-style: italic;
            margin: 20px auto;
            width: 400px;
            padding-bottom: 10px;
        }
        .reason {
            font-size: 28px; /* Adjusted for readability */
        }
        /* Adjusted for compatibility and readability */
        .date-points {
            font-size: 28px;
            margin-top: 40px;
        }

        .footer {
            margin-top: 50px; /* Ajoute un espace entre le contenu principal et le footer */
            text-align: left;
            font-size: 22px;
            margin-left: 25px;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="logo">ULTIMATE TEAM RACE</div>

    <!-- Ajout de l'image ici -->
    <img src="https://c8.alamy.com/comp/HMHGJJ/first-place-winner-award-vector-illustration-design-HMHGJJ.jpg" width="80" height="80" alt="Logo de l'équipe" />

    <div class="marquee">Certificate of Completion</div>

    <div class="assignment">This certificate is presented to</div>

    <div class="person">
        Equipe {{ $etape->nom }}
    </div>
    <!-- Add the date and total points -->
{{--    <div class="date-points">--}}
{{--        2023-04-01<br/> --}}
{{--        {{ $etape->total_points }} points--}}
{{--    </div>--}}

    <div class="reason">
        We hope the performance / position remain<br/>
        unbeatable for centuries
    </div>
    <div class="footer">
        <div style="float: left; width: 33%;">
            Signature: _________
        </div>
        <div style="float: left; width: 33%;">
            Date: {{ date('Y-m-d') }}
        </div>
        <div style="float: left; width: 33%;">
            Categorie: {{ $categorie }}<br/>
            Points: {{ $etape->total_points }}
        </div>
        <div style="clear: both;"></div>
    </div>
    </div>
</body>
</html>
