<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Notebook - Mi Organizador de Tareas</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM5z4z5e5e5e5e5e5e5e5e5e5e5e5e5e5e5e5" crossorigin="anonymous">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* Tailwind CSS styles */
            /* ... (existing styles) ... */
        </style>
    @endif
    <style>
        /* Custom styles for the new color scheme */
        body {
            background-color: #F4F6F8; /* Soft light background */
            color: #2C3E50; /* Dark text color */
            font-family: 'Instrument Sans', sans-serif;
        }
        .header {
            background-color: #3498DB; /* Header background */
            color: white; /* Header text color */
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .button {
            background-color: #2ECC71; /* Button color */
            color: white; /* Button text color */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #27AE60; /* Darker green on hover */
        }
        .main-content {
            background-color: white; /* Main content background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            display: flex;
            flex-direction: row; /* Align items in a row */
            align-items: center; /* Center items vertically */
        }
        .text-content {
            flex: 1; /* Take remaining space */
            padding: 20px; /* Padding for text content */
        }
        h1 {
            font-size: 2.5rem; /* Larger heading */
            margin-bottom: 10px;
        }
        h2 {
            font-size: 1.5rem; /* Subheading */
            color: #3498DB; /* Accent color */
            margin-bottom: 10px;
        }
        p {
            line-height: 1.6; /* Improved line height */
            margin-bottom: 10px;
        }
        .features {
            list-style-type: disc; /* Bullet points for features */
            padding-left: 20px;
        }
        .image-container {
            width: 300px; /* Fixed width for the image */
            margin-right: 20px; /* Space between image and text */
            border-radius: 8px;
            overflow: hidden;
        }
        .image-container img {
            width: 100%;
            height: auto; /* Maintain aspect ratio */
            display: block;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen">
    <header class="header">
        <h1><i class="fas fa-book"></i> Notebook</h1>
        <nav>
            @if (Route::has('login'))
                <div class="flex justify-center gap-4">
                    @auth
                        <a href="{{ route('tasks.index') }}" class="button">Mis Tareas</a>
                    @else
                        <a href="{{ route('login') }}" class="button">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="button">Registrarse</a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>
    </header>
    <main class="main-content">
        <div class="image-container">
            <img src="https://coworkingfy.com/wp-content/uploads/2022/10/ccomo-organizar-mi-tiempo-1024x612.jpg" alt="Organiza tu tiempo">
        </div>
        <div class="text-content">
            <p>Organiza tu día, semana o proyecto de manera eficiente.</p>
            <p> Crea, edita y gestiona tus tareas con facilidad. ¡Mantén todo bajo control y aumenta tu productividad!</p>
            <h2>Características principales:</h2>
            <ul class="features">
                <li>Crea nuevas tareas con fechas de vencimiento.</li>
                <li>Marca tareas como completadas fácilmente.</li>
                <li>Visualiza tu progreso y prioriza tus actividades.</li>
                <li>Interfaz intuitiva y fácil de usar.</li>
            </ul>
        </div>
    </main>
</body>
</html>
