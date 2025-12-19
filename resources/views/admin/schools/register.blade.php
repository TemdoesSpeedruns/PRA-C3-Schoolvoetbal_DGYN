<h1>School aanmelden</h1>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<form method="POST" action="/scholen/aanmelden">
    @csrf

    <label>Schoolnaam</label>
    <input type="text" name="name" required>

    <label>Contact Persoon</label>
    <input type="text" name="name" required>

    <label>E-mailadres</label>
    <input type="email" name="contact_email" required>

    <button type="submit">Aanmelden</button>
</form>
