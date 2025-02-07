<div class="header-container">
    <img src="{{ asset('images/logo2.jpg') }}" alt="Logo" class="logo">

    <!-- Przycisk Wyloguj -->
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <x-dropdown-link :href="route('logout')"
                         onclick="event.preventDefault();
                     this.closest('form').submit();">
            {{ __('Wyloguj') }}
        </x-dropdown-link>
    </form>
</div>

<style>
    .header-container {
        position: relative; /* Aby umożliwić umieszczenie logo i przycisku w jednym kontenerze */
        height: 60px; /* Ustawienie wysokości dla lepszego wyrównania */
        padding: 10px; /* Odstępy wewnętrzne */
        display: flex; /* Użycie flexboxa do rozmieszczenia elementów */
        justify-content: space-between; /* Rozdzielenie logo i przycisku wylogowania */
        align-items: center; /* Wyrównanie elementów w pionie */
        background-color: #fff; /* Tło dla lepszej widoczności */
    }

    .logo {
        height: 60px; /* Wysokość logo */
        width: auto; /* Automatyczna szerokość dla proporcji */
    }

    .logout-form {
        margin-right: 20px; /* Odstęp od prawej strony */
    }

    .logout-form .dropdown-link {
        background-color: #f5f5f5; /* Tło przycisku */
        border: 1px solid #ccc; /* Obramowanie */
        padding: 10px 15px; /* Odstępy wewnętrzne */
        border-radius: 5px; /* Zaokrąglone rogi */
        color: #000; /* Kolor tekstu */
        text-decoration: none; /* Usunięcie podkreślenia */
        cursor: pointer; /* Kursor wskazujący możliwość kliknięcia */
        transition: background-color 0.3s; /* Animacja zmiany koloru */
    }

    .logout-form .dropdown-link:hover {
        background-color: #e2e2e2; /* Kolor tła przy najechaniu */
    }
</style>
