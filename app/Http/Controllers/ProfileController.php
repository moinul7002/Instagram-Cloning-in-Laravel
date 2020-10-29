<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $follows = (auth()-> user()) ? auth()-> user()->following->contains($user->id) : false;

        $postcount = Cache::remember(
            'count.posts.' . $user->id, 
            now()->addSeconds(30), 
            function () use ($user) 
            {
                return $user->posts->count();
            }
        );

        $followercount = Cache::remember(
            'count.followers.' . $user->id, 
            now()->addSeconds(30), 
            function () use ($user) 
            {
                return $user->profile->followers->count();
            }
        );

        $followingcount = Cache::remember(
            'count.following.' . $user->id, 
            now()->addSeconds(30), 
            function () use ($user) 
            {
                return $user->following->count();
            }
        );


        // dd($follows);
        return view('profiles.index', compact('user', 'follows', 'postcount', 'followercount', 'followingcount'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            $imageArray = ['image' => $imagePath];
        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }

}