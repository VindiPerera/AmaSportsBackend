@props([
    'paginator',
])

@if($paginator->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between">
        <div class="text-xs text-slate-500 font-medium">
            Showing <span class="font-bold text-slate-800">{{ $paginator->firstItem() }}</span> to <span class="font-bold text-slate-800">{{ $paginator->lastItem() }}</span> of <span class="font-bold text-slate-800">{{ $paginator->total() }}</span> results
        </div>
        <div class="flex items-center space-x-1">
            {{ $paginator->links() }}
        </div>
    </div>
@endif
