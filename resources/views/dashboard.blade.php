<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Użytkownika') }}
        </h2>
    </x-slot>

    <!-- wyświetlanie zwierząt -->
    @include('components.dashboard-animal')


</x-app-layout>
<!-- Dodanie stopki -->
@include('components.footer')
