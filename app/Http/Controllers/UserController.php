<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

class UserController extends Controller
{

    protected $modelClass = User::class;

    public function index()
    {

//        $emp = Employee::find(5);
//        dd($emp->getPicture());

        $this->viewData['customTitle'] = User::all();
    }

    public function myProfile($id)
    {
        $mydata = User::where('id', $id)->get();
        return view('myprofile', ['mydata' => $mydata]);
    }

    public function myprofileupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'mimes:jpeg,bmp,png|max:1000'
        ]);

        if($validator->fails()){
            \Session::flash('message','Please choose a file format jpeg,jpg,png and max image size 1MB.');
            \Session::flash('alert-class', 'alert-warning');
            return redirect()->back();
        }

        $userData = User::findorFail($id);

            if($request->has('name')){
            $userData->name = $request->name;
            }
            if($request->has('password')){
            $userData->password =Hash::make($request->password);
            }
            if($request->hasFile('picture')){
                $userData->picture = $request->file('picture')->store('picture');
            }
            $userData->save();
            Session()->flash('message', 'Your data has been Updated');
            return redirect()->back();
    }
}
