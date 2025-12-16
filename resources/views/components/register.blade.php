<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>School Registratie</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h1>School registreren</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('schools.register') }}">
        @csrf
        <div>
            <label>Schoolnaam</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div>
            <label>Contactpersoon</label>
            <input type="text" name="contact_person" value="{{ old('contact_person') }}">
        </div>
        <div>
            <label>E-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label>Telefoon</label>
            <input type="text" name="phone" value="{{ old('phone') }}">
        </div>
        <div>
            <label>Adres</label>
            <textarea name="address">{{ old('address') }}</textarea>
        </div>
        <button type="submit">Registreren</button>
    </form>
</div>
</body>
</html>