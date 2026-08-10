<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
 <meta name= "csrf-token" content= "{{ csrf_token() }}" > 
  <title>{{config('app.name')}} | Espace Livreur</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <!-- Tailwind Dark Mode: class-based -->
  <script>tailwind.config = { darkMode: 'class' }</script>
  <!-- Anti-flash: Apply theme before render -->
  <script>if(localStorage.getItem('theme')==='dark'){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}</script>

  <!-- Google Fonts & Material Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="{{asset('assets/livreur/css/styles.css')}}" />
</head>