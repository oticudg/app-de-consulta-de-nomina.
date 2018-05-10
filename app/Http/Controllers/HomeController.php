<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nomina;
use App\Models\Entregado;

class HomeController extends Controller
{
    public $event_current = 2;

    /**
     * Show dashboard of the app.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! request()->ajax()) {
            return view('clap.index');
        }

        $this->validate($request, ['cedula' => 'numeric|required'],[],['cedula' => 'cédula']);

        $event_current = $this->event_current;

        $trabajador = Nomina::where('cedula_a', $request->cedula)->first();
        if (isset($trabajador->id)) {
            $trabajador->entregado;
        }

        return response()->json(compact('trabajador', 'event_current'));
    }

    public function entregar($id)
    {
        $trabajador = Nomina::findOrFail($id)->first();
        Entregado::create([
            'event_id' => $this->event_current,
            'cedula_a' => $id,
        ]);
    }
}
