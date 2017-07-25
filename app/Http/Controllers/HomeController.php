<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Empresa;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {
        $empresa_id = Session::get('empresa');

        $empresa = Empresa::select('DESCRIPCION')->where('ID', '=', $empresa_id)->FIRST();

        return view('home', compact('empresa'));
    }
}
