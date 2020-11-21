<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FakePayouts;
use App\Models\FakeTeam;
use App\Models\FakeTestimonial;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function abort_if;
use function array_pull;
use function base_path;
use function jsonOrRedirectBack;
use function public_path;
use function view;

class FakerController extends Controller
{
    public function payouts(Request $request)
    {
        $payouts = FakePayouts::latest()->paginate(10);
        return view('admin.faker.payouts', compact('payouts'));
    }

    public function createPayouts(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:255',
            'amount' => 'required|max:255',
            'address' => 'required|max:255',
            'country' => 'required|max:255',
            'txid' => 'max:255',
            'confirmations' => 'numeric|max:10',
        ]);
        $props = $request->all();
        $props['txid'] = $request->txid ?: '';
        FakePayouts::create($props);
        $data = ['status' => true, 'message' => 'Payout created succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function updatePayouts(Request $request)
    {
        $this->updateMultiple($request, FakePayouts::class, [
            'username' => 'required|max:255',
            'amount' => 'required|max:255',
            'address' => 'required|max:255',
            'country' => 'required|max:255',
            'txid' => 'max:255',
            'confirmations' => 'numeric|max:10',
        ]);
        $data = ['status' => true, 'message' => 'Payout updated succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function deletePayouts(Request $request)
    {
        $payouts = FakePayouts::findOrFail($request->id);
        $payouts->delete();
        $data = ['status' => true, 'message' => 'Payout deleted succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function testimonials(Request $request)
    {
        $testimonials = FakeTestimonial::latest()->paginate(10);
        return view('admin.faker.testimonials', compact('testimonials'));
    }

    public function createTestimonials(Request $request)
    {
        $this->validate($request, [
            'video' => 'required|url|max:255',
            'image' => 'file',
            'description' => 'max:255',
        ]);
        $video_id = $this->getYouTubeId($request->video);
        abort_if(empty($video_id), 422, 'Invalid yotube link');
        $props = $request->all();
        $props['video'] = "https://www.youtube.com/watch?v={$video_id}";
        $props['description'] = $request->description ?: '';
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $name = $video_id . '.' . $uploadedFile->extension();
            $folder = "images/videos";
            $path ="$folder/$name";
            abort_if(Storage::disk('assets')->exists($path), 422, 'File already exist');
//            Storage::disk('assets')->put($name, $uploadedFile->getPathname());
            $uploadedFile->move(public_path($folder), $name);
            $props['image'] = $path;
            //move to public_html for shared hosting
            if (file_exists(base_path('../public_html'))) {
                $folder = base_path("../public_html/$folder");
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                copy(public_path($path), base_path("../public_html/$path"));
            }
        }
        FakeTestimonial::create($props);
        $data = ['status' => true, 'message' => 'Testimonial created succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function updateTestimonials(Request $request)
    {
        $this->updateMultiple($request, FakeTestimonial::class, [
            'video' => 'required|url|max:255',
            'description' => 'max:255',
        ]);
        $data = ['status' => true, 'message' => 'Testimonial updated succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function deleteTestimonials(Request $request)
    {
        $payouts = FakeTestimonial::findOrFail($request->id);
        if ($payouts->image) {
            if (file_exists(public_path($payouts->image))) {
                unlink(public_path($payouts->image));
            }
            //Delete copy for shared hosting
            if (file_exists(base_path("../public_html/{$payouts->image}"))) {
                unlink(base_path("../public_html/{$payouts->image}"));
            }
        }
        $payouts->delete();
        $data = ['status' => true, 'message' => 'Testimonial deleted succesfully'];
        return jsonOrRedirectBack($data);
    }
    public function team(Request $request)
    {
        $members = FakeTeam::latest()->paginate(10);
        return view('admin.faker.team', compact('members'));
    }

    public function createTeam(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'image' => 'file',
            'linkedin_url' => 'required|url',
            'email' => 'required|max:255',
        ]);
        $props = $request->all();
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $image_name = uniqid('team_');
            $name = $image_name . '.' . $uploadedFile->extension();
            $folder = "images/team";
            $path ="$folder/$name";
            abort_if(Storage::disk('assets')->exists($path), 422, 'File already exist');
//            Storage::disk('assets')->put($name, $uploadedFile->getPathname());
            $uploadedFile->move(public_path($folder), $name);
            $props['image'] = $path;
            //move to public_html for shared hosting
            if (file_exists(base_path('../public_html'))) {
                $folder = base_path("../public_html/$folder");
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                copy(public_path($path), base_path("../public_html/$path"));
            }
        }
        FakeTeam::create($props);
        $data = ['status' => true, 'message' => 'Team member created succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function updateTeam(Request $request)
    {
        $this->updateMultiple($request, FakeTeam::class, [
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'image' => 'file',
            'linkedin_url' => 'required|url',
            'email' => 'required|max:255',
        ]);
        $data = ['status' => true, 'message' => 'Team updated succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function deleteTeamMember(Request $request)
    {
        $member = FakeTeam::findOrFail($request->id);
        if ($member->image) {
            if (file_exists(public_path($member->image))) {
                unlink(public_path($member->image));
            }
            //Delete copy for shared hosting
            if (file_exists(base_path("../public_html/{$member->image}"))) {
                unlink(base_path("../public_html/{$member->image}"));
            }
        }
        $member->delete();
        $data = ['status' => true, 'message' => 'Team member deleted succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function faqs(Request $request)
    {
        $faqs = FAQ::latest()->paginate(10);
        return view('admin.faker.faqs', compact('faqs'));
    }

    public function createFaqs(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|max:255',
            'answer' => 'required|max:10000',
        ]);
        FAQ::create($request->all());
        $data = ['status' => true, 'message' => 'FAQ created succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function updateFaqs(Request $request)
    {
        $this->updateMultiple($request, FAQ::class);
        $data = ['status' => true, 'message' => 'FAQs updated succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function deleteFaqs(Request $request)
    {
        $payouts = FAQ::findOrFail($request->id);
        $payouts->delete();
        $data = ['status' => true, 'message' => 'FAQ deleted succesfully'];
        return jsonOrRedirectBack($data);
    }

    public function updateMultiple(Request $request, $model, $customRules = [])
    {
        $input = $request->all();
        array_pull($input, '_token');
        $rules = [];
        $e_msg = [];
        foreach ($input as $key => $value) {
            $nameKey = explode('::', $key)[1];
            $name = ucfirst($nameKey);
            $rules[$key] = $customRules[$nameKey] ?? 'required|max:1000';
            $e_msg[$key . '.required'] = "$name is required";
            $e_msg[$key . '.max'] = "$name must not be more than 255 characters";
        }
        $this->validate($request, $rules, $e_msg);

        //Group
        $rows = [];
        foreach ($input as $key => $value) {
            $splits = explode('::', $key);
            $rows[$splits[0]][$splits[1]] = $value;
        }

        //Update
        foreach ($rows as $key => $values) {
            $model::where('id', $key)->update($values);
        }
    }

    private function getYouTubeId($link)
    {
        $video_id = null;
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $match)) {
            $video_id = $match[1];
        }
        return $video_id;
    }
}
