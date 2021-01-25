@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-400">{{ __('Oops! Alguma coisa deu errado.') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
