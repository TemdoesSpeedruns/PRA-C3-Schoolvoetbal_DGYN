<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin - School Aanvragen</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <h1>School Aanvragen</h1>

        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>School</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ingediend</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                <tr>
                    <td>{{ $school->id }}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->contact_person }}</td>
                    <td>{{ $school->email }}</td>
                    <td>{{ $school->status }}</td>
                    <td>{{ $school->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($school->status === 'pending')
                        <form method="POST" action="{{ route('admin.schools.approve', $school->id) }}" style="display:inline">
                            @csrf
                            <button type="submit">Goedkeuren</button>
                        </form>
                        <form method="POST" action="{{ route('admin.schools.reject', $school->id) }}" style="display:inline">
                            @csrf
                            <button type="submit">Afwijzen</button>
                        </form>
                        @else
                        <span>Geen acties beschikbaar</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $schools->links() }}
    </div>
</body>

</html>