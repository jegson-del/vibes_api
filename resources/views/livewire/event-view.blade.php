<div class="bg-white">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <!-- Table -->
    <table class="overflow-x-auto w-full bg-white divide-y divide-gray-200">
        <thead class="bg-gray-50 text-gray-500 text-sm">
            <tr class="divide-x divide-gray-300">
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Prices</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Starts</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ends</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Genre</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">City</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Province</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Post Code</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
        </thead>
        <tbody class="text-gray-500 text-xs divide-y divide-gray-200">
            @foreach($events as $event)
                <tr class="text-center">
                    <td class="py-3 flex justify-center">
                        <img src="{{ $event->flyer->fullFilePath() }}" height="50" width="50">
                    </td>
                    <td class="py-3">{{ $event->name }}</td>
                    <td class="py-3">{{ $event->description }}</td>
                    <td class="py-3">
                        @foreach($event->prices as $price)
                            {{ $price ->type . ': ' . $price->pivot->price }}
                        @endforeach
                    </td>
                    <td class="py-3">{{ $event->starts }}</td>
                    <td class="py-3">{{ $event->ends }}</td>
                    <td class="py-3">{{ $event->genre }}</td>
                    <td class="py-3">{{ $event->address->address }}</td>
                    <td class="py-3">{{ $event->address->city }}</td>
                    <td class="py-3">{{ $event->address->province }}</td>
                    <td class="py-3">{{ $event->address->post_code }}</td>
                    <td class="py-3">
                        <div class="flex justify-center space-x-1">
                            <button onclick="location.href='{{ route('admin') . '?id=' . $event->id }}'" class="border-2 border-indigo-200 rounded-md p-1">
                                Edit
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4 text-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            @if($deleteId && $deleteId == $event->id)
                                <button wire:click="delete()" class="border-2 border-red-500 bg-red-500 text-white rounded-md p-1">
                                    Confirm?
                                </button>
                            @else
                                <button wire:click="deleteConfirmation({{$event->id}})" class="border-2 border-red-200 rounded-md p-1">
                                    Delete
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- End Table -->

    {{ $events->links() }}
</div>
