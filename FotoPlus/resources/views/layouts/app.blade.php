<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('imagens/iconeABA.png') }}">

    <title> {{ config('app.name', 'FotoPlus') }} </title>

    <!-- Fontes -->
    <link 
        href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" 
        rel="stylesheet"
    > 

    <!-- CSS Aplicacao -->
    <link 
        rel="stylesheet" 
        href="{{ asset('css/styles.css') }}"
    >  

    <!-- Font Awesome -->
    <link 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
        rel="stylesheet"
    >

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" 
        crossorigin="anonymous"
    >   

    <!-- Livewire Styles -->
    @livewireStyles
</head>

<body class="antialiased">

    <!-- Header -->
    <header id="inicio">
        @livewire('navigation-menu')
    </header>

    <!-- Page Content -->
    <main class="container-fluid">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <ul class="social-icons">
            <li><p>FotoPlus &copy;Copyright 2023-2024</p></li>
            <li><p>Visite as Redes Sociais</p></li>
            <li><a href="https://www.linkedin.com/in/renan-evilásio-43b357247" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
            <li><a href="https://www.instagram.com/renan_eso/" target="_blank"><i class="fab fa-instagram"></i></a></li>
        </ul>
    </footer>       

    @stack('modals')

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    <!-- Bootstrap Bundle with Popper - Versão Estável -->
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"
    ></script>

    <!-- Ionicons -->
    <script  
        type="module"  
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script  
        nomodule 
        src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>

</html>
