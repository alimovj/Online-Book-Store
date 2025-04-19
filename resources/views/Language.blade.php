<!-- Laravel Blade yoki Vue orqali -->
<form method="POST" action="{{ route('languages.store') }}">
    @csrf
    <input type="text" name="name" placeholder="O'zbek tili">
    <input type="text" name="prefix" placeholder="uz">
    <label>
        <input type="checkbox" name="is_active" checked> Faol
    </label>
    <button type="submit">Saqlash</button>
</form>
