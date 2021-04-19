<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class EventView extends Component
{
    public int $deleteId = 0;

    public function render()
    {
        $events = Event::with('address', 'flyer', 'prices')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        return view('livewire.event-view', compact('events'));
    }

    public function deleteConfirmation($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        $event = Event::findOrFail($this->deleteId);

        $path = str_replace('public/', '', $event->flyer->link);

        Storage::disk('public')->delete($path);

        $event->address()->delete();
        $event->flyer()->delete();
        $event->prices()->detach();
        $event->delete();
    }
}
