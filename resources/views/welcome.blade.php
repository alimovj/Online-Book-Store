<select onchange="window.location.href='/set-language/' + this.value">
    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
    <option value="uz" {{ app()->getLocale() == 'uz' ? 'selected' : '' }}>O'zbekcha</option>
    <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>Русский</option>
</select>
