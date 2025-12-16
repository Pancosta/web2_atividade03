<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Biblioteca Web 2</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                <a class="navbar-brand" href="{{ url('/') }}">
                    Biblioteca Web 2
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                        <!-- Sempre visível para usuários logados (qualquer papel) -->
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('books.index') }}">Livros</a>
                            </li>

                            <!-- BIBLIOTECÁRIO + ADMIN -->
                            @if (auth()->user()->role === 'bibliotecario' || auth()->user()->role === 'admin')

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('authors.index') }}">Autores</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('publishers.index') }}">Editoras</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('categories.index') }}">Categorias</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.debit') }}">Multas</a>
                                </li>
                            @endif

                            <!-- ADMIN APENAS -->
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.index') }}">Usuários</a>
                                </li>
                            @endif
                        @endauth

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">

                        @guest
                            <!-- Login -->
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-outline-primary mx-1 px-3" href="{{ route('login') }}">
                                    Login
                                </a>
                            </li>

                            <!-- Registrar -->
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-sm btn-primary mx-1 px-3 text-white"
                                        href="{{ route('register') }}">
                                        Registrar
                                    </a>
                                </li>
                            @endif
                        @else

                            <!-- Dropdown -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                </div>
                            </li>

                        @endguest

                    </ul>

                </div>
            </div>
        </nav>

        <!-- CONTEÚDO -->
        <main class="py-4">
            @yield('content')
        </main>

    </div>
</body>

</html>