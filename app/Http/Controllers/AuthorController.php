<?php

namespace App\Http\Controllers;

use App\Mail\AuthorVerifyMail;
use App\Models\Author;
use App\Models\EmailVerify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Mail;

class AuthorController extends Controller
{

    function author_register(Request $request)
    {
        $author_id = Author::insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_at' => Carbon::now(),
        ]);

        $author = EmailVerify::create([
            'author_id' => $author_id,
            'token' => uniqid(),
        ]);

        Mail::to($request->email)->send(new AuthorVerifyMail($author));

        return back()->with('verify', "We have sent you a verification email to $request->email");
        // return back()->with('success', 'Author Registration success! Your account is pending for approval');
    }

    function author_login(Request $request)
    {
        if (Author::where('email', $request->email)->exists()) {
            if (Auth::guard('author')->attempt(['email' => $request->email, 'password' => $request->password])) {
                if (Auth::guard('author')->user()->email_verified_at != null) {
                    if (Auth::guard('author')->user()->status == 1) {
                        return redirect()->route('index');
                    } else {
                        Auth::guard('author')->logout();
                        return back()->with('pending', 'Your account is pending for approval');
                    }
                } else {
                    Auth::guard('author')->logout();
                    return back()->with('not_verify', 'Your email is not verified');
                }
            } else {
                return back()->with('wrong', 'Wrong Password');
            }
        } else {
            return back()->with('exist', 'Email does not exist');
        }
    }
    function author_logout()
    {
        Auth::guard('author')->logout();
        return redirect('/');
    }

    function author_dashboard()
    {
        return view('frontend.author.dashboard');
    }
    function author_edit()
    {
        return view('frontend.author.edit');
    }
    function author_profile_update(Request $request)
    {
        if ($request->photo == '') {
            Author::find(Auth::guard('author')->id())->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            return back();
        } else {
            if (Auth::guard('author')->user()->photo != null) {
                $delete = public_path('uploads/author/' . Auth::guard('author')->user()->photo);
                unlink($delete);
            }

            $photo = $request->photo;
            $extension = $photo->extension();
            $file_name = uniqid() . '.' . $extension;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($photo);
            $image->resize(200, 200);
            $image->save(public_path('uploads/author/' . $file_name));
            Author::find(Auth::guard('author')->id())->update([
                'name' => $request->name,
                'email' => $request->email,
                'photo' => $file_name,
            ]);
            return back();
        }
    }
    function author_pass_update(Request $request)
    {
        if (Hash::check($request->current_password, Auth::guard('author')->user()->password)) {
            Author::find(Auth::guard('author')->id())->update([
                'password' => bcrypt($request->password),
            ]);
            return back()->with('success', 'password changed');
        } else {
            return back()->with('wrong', 'Current password does not match');
        }
    }

    function author_verify($token)
    {
        $author = EmailVerify::where('token', $token)->first();
        if (EmailVerify::where('token', $token)->exists()) {
            Author::find($author->author_id)->update([
                'email_verified_at' => Carbon::now(),
            ]);

            EmailVerify::where('token', $token)->delete();

            return redirect()->route('author.signin')->with('verified', 'Your Email is Verified');
        } else {
            abort('404');
        }
    }

    function request_verify()
    {
        return view('frontend.author.request_verify');
    }
    function request_verify_send(Request $request)
    {
        $author = Author::where('email', $request->email)->first();

        if (EmailVerify::where('author_id', $author->id)->exists()) {
            EmailVerify::where('author_id', $author->id)->delete();
        }

        $author = EmailVerify::create([
            'author_id' => $author->id,
            'token' => uniqid(),
        ]);

        Mail::to($request->email)->send(new AuthorVerifyMail($author));

        return back()->with('verify', "We have sent you a verification email to $request->email");
    }
}
