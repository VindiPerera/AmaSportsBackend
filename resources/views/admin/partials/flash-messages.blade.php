@if (session('success'))
    <x-alert type="success" title="Success">
        {{ session('success') }}
    </x-alert>
@endif

@if (session('error'))
    <x-alert type="error" title="Error">
        {{ session('error') }}
    </x-alert>
@endif

@if (session('firebase_warning'))
    <x-alert type="warning" title="Warning">
        {{ session('firebase_warning') }}
    </x-alert>
@endif

@if ($errors->any())
    <x-alert type="error" title="Please correct the following errors">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
