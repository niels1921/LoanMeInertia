<?php

namespace App\Http\Controllers\Auth;

use App\Account;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mockery\Exception;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'account_id' => $data['account_id'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        if($request->get('password') == $request->get('passwordRepeat')){
            $data = $request->all();
            $account = Account::create(['name' => $request->get('name')]);
            $data['account_id'] = $account->id;
            $validator = $this->validator($data);
            if($validator->fails()){
                return Redirect::back()->with('error', 'Something went wrong.');
            }
            try {
                $user = $this->create($data);
                $user->assignRole('owner');
                return Redirect::route('login')->with('success', 'Registered.');

            } catch (Exception $e){}

        }
        return Redirect::back()->with('error', 'Something went wrong.');
    }
}
