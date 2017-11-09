<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Request;
use App\User;
use App\Empresa;
use App\UsuarioEmpresa;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $maxLoginAttempts = 3;
    protected $lockoutTime = 60;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'EMPRESA' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return route('login');
    }


    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {

        $loginEmpresa = Session::get('loginEmpresa');

        if ($loginEmpresa != null) {

            $user_id = Auth::user()->id;
            $usuarioEmpresa = UsuarioEmpresa::select('ID')
                                                ->where('USER_ID', '=', $user_id)
                                                ->where('EMPRESA_ID', '=', $loginEmpresa)
                                                ->first();
            if ($usuarioEmpresa){
                Session::put('empresa', $loginEmpresa);;
            } else {

                $user_id = Auth::user()->id;
                $EMPRESA = Empresa::select('cat_empresa.ID', 'cat_empresa.DESCRIPCION')
                    ->join('cat_usuarioempresa', 'cat_usuarioempresa.EMPRESA_ID', '=', 'cat_empresa.ID')
                    ->join('users', 'users.id', '=', 'cat_usuarioempresa.USER_ID')
                    ->where('users.id', '=', $user_id)
                    ->first();

                Session::put('empresa', $EMPRESA->ID);
            }

        } else {
            $user_id = Auth::user()->id;
            $EMPRESA = Empresa::select('cat_empresa.ID', 'cat_empresa.DESCRIPCION')
                ->join('cat_usuarioempresa', 'cat_usuarioempresa.EMPRESA_ID', '=', 'cat_empresa.ID')
                ->join('users', 'users.id', '=', 'cat_usuarioempresa.USER_ID')
                ->where('users.id', '=', $user_id)
                ->first();

            Session::put('empresa', $EMPRESA->ID);
        }

        return route('home');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the login lockout error message.
     *
     * @param  int  $seconds
     * @return string
     */
      protected function getLockoutErrorMessage($seconds)
      {
          $minutes = round($seconds / 60);
          return \Lang::has('auth.throttle')
              ? \Lang::get('auth.throttle', ['minutes' => $minutes])
              : 'Too many login attempts. Please try again in '.$seconds.' seconds.';
      }


}
