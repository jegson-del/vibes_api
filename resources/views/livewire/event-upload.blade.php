<div>
    <div class="mt-10 flex items-center justify-center">
        <form wire:submit.prevent="{{ $event ? 'update' : 'save'}}" id="form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full">
            @if($eventUploaded)
                <div class="my-5 relative flex flex-col sm:flex-row sm:items-center bg-white shadow rounded-md py-5 pl-6 pr-8 sm:pr-6">
                    <div class="flex flex-row items-center border-b sm:border-b-0 w-full sm:w-auto pb-4 sm:pb-0">
                        <div class="text-green-500">
                            <svg class="w-6 sm:w-5 h-6 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-sm font-medium ml-3">Success.</div>
                    </div>
                    <div class="text-sm tracking-wide text-gray-500 mt-4 sm:mt-0 sm:ml-4">Your end was uploaded successfully.</div>
                </div>
            @endif

            <h1 class="block text-gray-700 font-bold mb-2 text-xl text-center">Image</h1>

            @error('image') <span class="error text-red-500">{{ $message }}</span> @enderror
            @if($event)
                <img src="{{ $event->flyer->fullFilePath() }}" height="50" width="50">
            @endif

            @if(!$event)
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Flyer</label>
                    <input wire:model="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="image" id="image" type="file" required>
                </div>
            @endif

            <div wire:loading wire:target="image">
                Processing image...
            </div>

            <br>
            <h1 class="block text-gray-700 font-bold mb-2 text-xl text-center">Event</h1>
            @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                <input wire:model.defer="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" id="name" type="text" required>
            </div>

            @error('description') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Description</label>
                <textarea wire:model.defer="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="description" id="description" maxlength="2000" required></textarea>
            </div>

            @error('starts') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="Date">Start Date&Time</label>
                <input wire:model.defer="starts" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="starts" id="starts" type="datetime-local" required>
            </div>

            @error('ends') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">End Date&Time</label>
                <input wire:model.defer="ends" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="ends" id="ends" type="datetime-local" required>
            </div>

            @error('genre') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Genre</label>
                <select wire:model.defer="genre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="genre" id="genre" required>
                    <option>Select a genre</option>
                    <option value="House">House</option>
                    <option value="Hip-hop">Hip-hop</option>
                    <option value="Afro">Afro</option>
                </select>
            </div>

            <br>
            <h1 class="block text-gray-700 font-bold mb-2 text-xl text-center">Pricing</h1>

            <span class="text-yellow-700">{{ __('Event has the following prices shown below. Please only update these fields if you want to override the old prices. If you decide to fill these fields, all the old prices for this event will be deleted and replaced with the new prices entered here.') }}</span>
            <br />

            @if($event && $event->prices)
                @foreach($event->prices as $price)
                    <span class="text-sm text-blue-500">{{ $price ->type . ': ' . $price->pivot->price }}</span>
                @endforeach
            @endif

            @foreach($priceTypes as $priceType)
                @error('price') <span class="error text-red-500">{{ $message }}</span> @enderror

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">{{ $priceType->type }}</label>
                    <input wire:model="priceSelected.{{ $priceType->type }}.{{$priceType->id}}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number">
                </div>
            @endforeach

            <br>
            <h1 class="block text-gray-700 font-bold mb-2 text-xl text-center">Address</h1>

            @error('address') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Address</label>
                <input wire:model.defer="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="address" id="address" type="text" required>
            </div>

            @error('city') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">City</label>
                <input wire:model.defer="city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="city" id="city" type="text" required>
            </div>

            @error('province') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Province</label>
                <input wire:model.defer="province" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="province" id="province" type="text" required>
            </div>

            @error('post_code') <span class="error text-red-500">{{ $message }}</span> @enderror
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Post Code</label>
                <input wire:model.defer="post_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="post_code" id="post_code" type="text" required>
            </div>

            @if($eventUploaded)
                <div class="my-5 relative flex flex-col sm:flex-row sm:items-center bg-white shadow rounded-md py-5 pl-6 pr-8 sm:pr-6">
                    <div class="flex flex-row items-center border-b sm:border-b-0 w-full sm:w-auto pb-4 sm:pb-0">
                        <div class="text-green-500">
                            <svg class="w-6 sm:w-5 h-6 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-sm font-medium ml-3">Success.</div>
                    </div>
                    <div class="text-sm tracking-wide text-gray-500 mt-4 sm:mt-0 sm:ml-4">Your end was uploaded successfully.</div>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <button id="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">{{ $event ? 'Edit' : 'Upload' }}</button>
            </div>
        </form>
    </div>
</div>
