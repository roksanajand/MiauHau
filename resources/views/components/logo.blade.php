<a href="{{ url('/') }}">
    <img src="{{ asset('images/logo2.jpg') }}" alt="Logo" class="logo">
</a>

<style>

    .logo {
        position: absolute; /* Przypięcie logo do lewego górnego rogu */
        top: 5px;
        left: 20px;
        height: 60px; /* Wysokość logo */
        width: auto; /* Automatyczna szerokość, aby zachować proporcje */
        z-index: 10000;
    }
</style>
