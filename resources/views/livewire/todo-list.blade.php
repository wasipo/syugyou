<div class="max-w-md mx-auto mt-10 space-y-4">
    <div class="flex gap-2">
        <!-- wire:model で双方向バインド -->
        <input type="text" wire:model.defer="new" class="flex-1 border p-2 rounded" placeholder="やること…">
        <button wire:click="add" class="bg-blue-600 text-white px-3 py-2 rounded">追加</button>
    </div>

    <ul class="divide-y">
        @foreach ($items as $i => $task)
        <li class="py-2 flex justify-between items-center">
            {{ $task }}
            <button wire:click="remove({{ $i }})" class="text-red-500">✕</button>
        </li>
        @endforeach
    </ul>
</div>