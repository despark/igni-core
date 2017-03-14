<div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        @foreach($languages as $i18n)
            <li role="presentation" class="{{ $locale === $i18n['locale'] ? 'active' : '' }}">
                <a href="{{ $url }}" aria-controls="{{ $i18n['locale'] }}" role="tab"
                   data-toggle="tab">{{ $i18n['name'] }}</a>
            </li>
        @endforeach
    </ul>
</div>