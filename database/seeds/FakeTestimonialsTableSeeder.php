<?php

use Illuminate\Database\Seeder;

class FakeTestimonialsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon\Carbon::now();
        $data = [];
        $videos = file(storage_path('files/testimonials'));
        foreach ($videos as $line) {
            $video = trim($line);
            if ($video && !starts_with($video, '//')) {
                $data[] = [
                    'video' => $video,
                    'image' => $this->getVideoImageLink($video),
                    'description' => '',
                    'created_at' => $time,
                    'updated_at' => $time
                ];
            }
        }
        App\Models\FakeTestimonial::insert($data);
    }
    
    public function getVideoImageLink($video)
    {
        $code =  str_replace('https://youtu.be/', '', $video);
        return "images/videos/$code.jpg";
    }
}
