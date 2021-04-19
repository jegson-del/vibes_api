<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $images = [
            "https://i.ytimg.com/vi/YWCoA4jhHVQ/maxresdefault.jpg",
            "https://blogmedia.evbstatic.com/wp-content/uploads/wpmulti/sites/8/shutterstock_199419065.jpg",
            "https://st.depositphotos.com/1002111/2556/i/600/depositphotos_25565783-stock-photo-young-party-people.jpg",
            "https://mirageparties.co.uk/wp-content/uploads/2019/01/18th-birthday-party-hero.jpg",
            "https://www.incimages.com/uploaded_files/image/1920x1080/getty_614867390_321301.jpg",
            "https://images.pexels.com/photos/3171837/pexels-photo-3171837.jpeg",
            "https://static.toiimg.com/photo/msid-73001128,width-96,height-65.cms",
            "https://cdn.cnn.com/cnnnext/dam/assets/200818130226-01-wuhan-water-park-1808.jpg",
            "https://visualhunt.com/photos/1/1-people-enjoying-rock-concert-1.jpg?s=s",
            "https://www.amnesia.es/uploads/headers/28052018175632926831.jpg"
        ];

        return [
            'link' => $images[rand(0, (count($images) - 1))],
//            'link' => $this->faker->imageUrl(),
        ];
    }
}
