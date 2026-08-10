@if (session('success'))
    <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
@endif

@if (session('firebase_warning'))
    <div class="mb-4 rounded border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        ⚠ {{ session('firebase_warning') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
