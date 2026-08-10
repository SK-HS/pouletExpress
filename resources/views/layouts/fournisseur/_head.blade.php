<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
     <meta name= "csrf-token" content= "{{ csrf_token() }}" > 
    <title>{{config('app.name')}} | Espace Fournisseur</title>
    
    <!-- External Scripts & Fonts -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Custom Config and Styles -->
    <script src="{{asset('assets/fournisseur/js/tailwind-config.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/fournisseur/css/main.css')}}">
</head>