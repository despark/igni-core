<div>
    @foreach($languages as $i18n)
        <a href="{{ $i18n['url'] }}" class="btn btn-info {{ $locale === $i18n['locale'] ? 'active' : '' }}" role="button">{{ $i18n['name'] }}</a>
    @endforeach
</div>