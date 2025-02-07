<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Miau-Hau</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Figtree', sans-serif;
        }

        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            z-index: 1000;
            border-bottom: 1px solid #ccc;
            padding: 5px 10px;
        }

        .fixed-header a {
            margin: 0;
            padding: 2px 15px;
            line-height: 4;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-top: 100px;
        }

        .image-wrapper {
            display: flex;
            justify-content: center;
            width: 1620px;
            height: 680px;
            overflow: hidden;
        }
        .image-wrapper.logged-in .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-width: 480px; /* Stała szerokość */
            max-height: 500px; /* Stała wysokość */
            display: block;
            margin: auto;
            border-radius: 8px;
        }

        .image-wrapper.guest .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-width: 480px;
            max-height: 500px;
            display: block;
            margin: auto;
            border-radius: 8px;
        }

        .image-item {
            text-align: center;
            margin: 0 5px;
            flex: 1 1 33.33%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }

        .photo-info {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 40px;
            user-select: none;
            color: black;
            z-index: 100000;
        }

        .arrow.left {
            left: -50px;
        }

        .arrow.right {
            right: -50px;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
<div class="bg-gray-50 text-black/50 dark:text-white/50">
    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
            <!-- Przypięty nagłówek -->
            <header class="fixed-header grid grid-cols-2 items-center gap-2 lg:grid-cols-3">
                <div class="flex lg:justify-center lg:col-start-2">
                    @include('components.logo')
                </div>
                @if (Route::has('login'))
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-md text-black ring-1 ring-transparent hover:text-black/70 focus:outline-none dark:text-white dark:hover:text-white/80">Panel Użytkownika</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md text-black ring-1 ring-transparent hover:text-black/70 focus:outline-none dark:text-white dark:hover:text-white/80">Zaloguj się</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-md text-black ring-1 ring-transparent hover:text-black/70 focus:outline-none dark:text-white dark:hover:text-white/80">Zarejestruj się</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>
        </div>

        <main class="container">
            <div class="image-wrapper @auth logged-in @else guest @endauth">
                <div class="arrow left" onclick="changeImage('prev')">&#10094;</div>

                @foreach ($photos as $index => $photo)
                    <div class="image-item">
                        <img src="{{ asset($photo) }}" alt="Zdjęcie {{ $index + 1 }}">

                        @auth
                            <div class="photo-info">
                                <p>Imię: {{ $additionalInfo[$index]['name'] ?? '' }}</p>
                                <p>Miasto: {{ $additionalInfo[$index]['city'] ?? '' }}</p>
                                <p>Kontakt do właściciela: {{ $additionalInfo[$index]['email'] ?? '' }}</p>
                                <p>Liczba polubień: <span id="likesCount{{ $index }}">{{ $additionalInfo[$index]['likes'] ?? 0 }}</span></p>
                            </div>
                            <div class="like-section">
                                @if (isset($additionalInfo[$index]['id']))
                                    @if ($additionalInfo[$index]['liked'])
                                        <form action="{{ route('animals.unlike', $additionalInfo[$index]['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                                                Usuń polubienie
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('animals.like', $additionalInfo[$index]['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                Lubię to
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <p class="photo-info">Błąd: Brak ID zwierzęcia</p>
                                @endif
                            </div>
                        @endauth
                    </div>
                @endforeach

                <div class="arrow right" onclick="changeImage('next')">&#10095;</div>
            </div>
        </main>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    @include('components/footer')
</footer>

<style>
    .hidden {
        display: none;
    }
</style>

<script>
    const images = document.querySelectorAll('.image-item');
    let currentIndex = 0;

    function updateCarousel() {
        images.forEach((image, index) => {
            const position = (index - currentIndex + images.length) % images.length;

            if (position === 0) {
                image.style.order = 1;
                image.classList.remove('hidden');
            } else if (position === 1) {
                image.style.order = 2;
                image.classList.remove('hidden');
            } else if (position === 2) {
                image.style.order = 3;
                image.classList.remove('hidden');
            } else {
                image.classList.add('hidden');
            }
        });
    }

    function changeImage(direction) {
        if (direction === 'next') {
            currentIndex = (currentIndex + 1) % images.length;
        } else if (direction === 'prev') {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
        }
        updateCarousel();
    }

    updateCarousel();
</script>

</body>
</html>
