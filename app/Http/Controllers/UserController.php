<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showRegisterPage()
    {
        return view('home');
    }
    
    public function showLoginPage()
    {
        return view('home');
    }

    public function showRatingPage()
    {
        return view('rating');
    }

    public function registerUser(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|digits:10|regex:/^[0-9]{10}$/',
            'instagram'  => [
                'required',
                'string',
                'max:30',
                'regex:/^@?(?!.*\.\.)(?!.*\.$)[a-zA-Z0-9._]{1,30}$/',
                'unique:users,instagram_id'
            ],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $data = $validator->validated();
        
        // Strip leading '@' before saving
        $data['instagram'] = ltrim($data['instagram'], '@');

        // Check Instagram existence
        // if (!$this->instagramExists($data['instagram'])) {
        //     return response()->json([
        //         'success' => false,
        //         'errors' => ['instagram' => ['This Instagram account does not exist.']]
        //     ], 422);
        // }
    
        // Save to DB
        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone'],
            'instagram_id' => $data['instagram'], // Make sure your DB has this column
            'password'     => bcrypt('password'), // Use bcrypt for security
        ]);
    
        // Auto login
        Auth::login($user);
    
        return response()->json([
            'success' => true,
            'message' => 'Registration successful!'
        ]);
    }
    
    // function to check if Instagram username exists
    private function instagramExists($username) 
    {
        $username = ltrim($username, '@'); // remove @ if given
        $url = "https://www.instagram.com/$username/";
    
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0' // Instagram blocks requests without UA
            ])->get($url);
    
            return $response->status() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function submitRating(Request $request)
    {
        // Validate the rating input
        $validator = Validator::make($request->all(), [
            'aroma' => 'required|integer|min:1|max:5',
            'taste' => 'required|integer|min:1|max:5',
            'smoothness' => 'required|integer|min:1|max:5',
            'recommendation' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Save the rating details if needed
        $data = $validator->validated();
        // Optional: Store the rating data in the database for the logged-in user

        $user = auth()->user();
        $user->update([
            'aroma' => $data['aroma'],
            'taste' => $data['taste'],
            'smoothness' => $data['smoothness'],
            'recommendation' => $data['recommendation'],
        ]);


        // Log out the user after submitting the rating
        Auth::logout();

        return response()->json(['success' => true, 'message' => 'Rating submitted successfully!']);
    }
}
