<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
</head>
<body>
<h1>Przesyłanie pliku</h1>

<!-- Wyświetlanie błędów -->
@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Formularz przesyłania pliku -->
<form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="file">Wybierz plik:</label>
        <input type="file" name="file" id="file" accept="image/*" required>
    </div>

    <button type="submit">Prześlij</button>
</form>
</body>
</html>

<!-- próbny plik do zapisu -->
