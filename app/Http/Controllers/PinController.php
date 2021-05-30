<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Tablero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $texto = $request->get('texto');
        $listaPins = DB::table('pins')
            ->leftJoin('users', 'pins.usuario_id', '=', 'users.id')->select('pins.id as pin_id','pins.titulo','pins.imagen','pins.url','pins.tablero_id','pins.usuario_id','users.id as user_id','users.name')->where('titulo','LIKE','%'.$texto.'%')
            ->get();
        return view('pins.lista',compact('listaPins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $listaTableros = Tablero::all();
        return view('pins.form',compact('listaTableros'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function store(Request $request){
        $nombre = time().'.'.$request->file('file')->extension();

        $request->file('file')->move(public_path('images'), $nombre);

        $validator = Validator::make($request->all(),[
            'titulo'=>['required', 'string'],
            'url'=>['required', 'string'],
            'tablero_id'=>['required', 'int'],
            'usuario_id'=>['required', 'int']
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request->merge([
            'imagen' =>$nombre
        ]);
        $objPin = Pin::create($request->all());
        $objPin->save();
        return response()->redirectTo(route('pins.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pin  $pin
     * @return \Illuminate\Http\Response
     */
    public function show(Pin $pin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pin  $pin
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $listaTableros = Tablero::all();
        $objPin= Pin::find($id);
        return view('pins.edit', compact('objPin','listaTableros'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pin  $pin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pin $pin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pin  $pin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $objPin = Pin::find($id);
        if ($objPin == null) {
            return response()->redirectTo('/pins');
        }
        $objPin->delete();
        return response()->redirectTo('/pins');
    }

    public function pinsPorTableros($id)
    {

        $listaPins = DB::table('pins')
            ->leftJoin('users', 'pins.usuario_id', '=', 'users.id')->select('pins.id as pin_id','pins.titulo','pins.imagen','pins.url','pins.tablero_id','pins.usuario_id','users.id as user_id','users.name')->where('tablero_id','=',$id)
            ->get();
        return view('pins.lista',compact('listaPins'));
    }

    public function myPins($id)
    {
        $listaPins = DB::table('pins')
            ->leftJoin('users', 'pins.usuario_id', '=', 'users.id')->select('pins.id as pin_id','pins.titulo','pins.imagen','pins.url','pins.tablero_id','pins.usuario_id','users.id as user_id','users.name')->where('usuario_id','=',$id)
            ->get();
        return view('pins.lista',compact('listaPins'));
    }
}
