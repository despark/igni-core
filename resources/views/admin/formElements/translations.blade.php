@if(!empty(config('ignicms.languages')))
  <div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        @foreach(config('ignicms.languages') as $i18n)
            <li role="presentation" class="{{ $field->getValue() === $i18n['locale'] ? 'active' : '' }}">
                <a href="{{ url()->current() }}" aria-controls="{{ $i18n['locale'] }}" role="tab" data-toggle="tab">{{ $i18n['name'] }}</a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach(config('ignicms.languages') as $i18n)
            <div role="tabpanel" class="tab-pane {{ $field->getValue() === $i18n['locale'] ? 'active' : '' }}" id="{{ $i18n['locale'] }}">
                @include('admin.'.$record->getTable().'.form', array('model' => $record->getTranslations($record->id, $i18n['locale']), 'i18n' => $i18n, 'record' => $record))
            </div>
        @endforeach
    </div>
   </div>
@endif