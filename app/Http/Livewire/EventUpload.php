<?php

namespace App\Http\Livewire;

use App\Models\Address;
use App\Models\Event;
use App\Models\File;
use App\Models\PriceType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

class EventUpload extends Component
{
    use WithFileUploads;

    public $image;
    public $name;
    public $description;
    public $starts;
    public $ends;
    public $genre;
    public $address;
    public $city;
    public $province;
    public $post_code;
    public $priceTypes;
    public $priceSelected = [];
    public $event = null;
    public $eventUploaded = 0;

    public function mount()
    {
        $this->priceTypes = PriceType::all();
    }

    public function render()
    {
        $event = Event::where('id', request()->id)->with('address', 'flyer', 'prices')->first();

        if($event) {
            $this->event = $event;
            $this->name = $event->name;
            $this->description = $event->description;

            $starts = new \DateTime($event->starts);
            $ends = new \DateTime($event->ends);
            $this->starts = $starts->format('Y-m-d\TH:i:s');
            $this->ends = $ends->format('Y-m-d\TH:i:s');

            $this->genre = $event->genre;
            $this->address = $event->address->address;
            $this->city = $event->address->city;
            $this->province = $event->address->province;
            $this->post_code = $event->address->post_code;
        }

        return view('livewire.event-upload', compact('event'));
    }

    public function save()
    {
        $this->validate($this->validationData(true));

        $this->validatePrice();

        $fileName = $this->image->store('public/Event');

        $event = Event::create([
            'name' => $this->name,
            'description' => $this->description,
            'starts' => $this->starts,
            'ends' => $this->ends,
            'genre' => $this->genre,
        ]);

        $event->address()->save(new Address([
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'post_code' => $this->post_code,
        ]));

        $event->flyer()->save(new File([
            'link' => $fileName
        ]));

        foreach ($this->priceSelected as $price) {
            if(head($price)) {
                $event->prices()->attach(array_key_first($price), ['price' => head($price)]);
            }
        }

        $this->image = null;
        $this->name = null;
        $this->description = null;
        $this->starts = null;
        $this->ends = null;
        $this->genre = null;
        $this->address = null;
        $this->city = null;
        $this->province = null;
        $this->post_code = null;
        $this->priceSelected = [];

        $this->eventUploaded = 1;
    }

    public function update()
    {
        $this->validate($this->validationData());

        $this->event->update([
            'name' => $this->name,
            'description' => $this->description,
            'starts' => $this->starts,
            'ends' => $this->ends,
            'genre' => $this->genre,
        ]);

        $this->event->address->update([
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'post_code' => $this->post_code,
        ]);

        foreach ($this->priceSelected as $price) {
            if(head($price)) {
                $this->event->prices()->detach();
                break;
            }
        }

        foreach ($this->priceSelected as $price) {
            if(head($price)) {
                $this->event->prices()->attach(array_key_first($price), ['price' => head($price)]);
            }
        }

        $this->eventUploaded = 1;
    }

    private function validationData(bool $withImage = false)
    {
        $validationRules = [
            'name' => 'required',
            'description' => 'required',
            'starts' => 'required',
            'ends' => 'required|after:starts',
            'genre' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'post_code' => 'required',
        ];

        if($withImage) {
            $validationRules['image'] = 'image|required|max:15000';
        }

        return $validationRules;
    }

    private function validatePrice()
    {
        if(count($this->priceSelected) === 0) {
            throw ValidationException::withMessages(['price' => ['Validation Message #1']]);
        }
    }
}
