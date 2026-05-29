<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use JamesMosquera\FiscalColombia\Enums\RegimenTributario;
use JamesMosquera\FiscalColombia\Rules\NitRule;
use JamesMosquera\FiscalColombia\Support\NitValidator;
use JamesMosquera\FiscalColombia\Support\Retenciones;
use Illuminate\Http\Request;

class FiscalCrudController extends Controller
{
    public function index()
    {
        $empresas = Empresa::latest()->paginate(10);
        return view('fiscal.index', compact('empresas'));
    }

    public function create()
    {
        $regimenes = RegimenTributario::cases();
        return view('fiscal.form', compact('regimenes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nit'                => ['required', 'string', 'max:15', new NitRule()],
            'razon_social'       => ['required', 'string', 'max:255'],
            'regimen_tributario' => ['required', 'string'],
        ]);

        $dv = NitValidator::calcularDv($request->nit);

        Empresa::create([
            'nit'                => $request->nit,
            'dv'                 => $dv,
            'razon_social'       => $request->razon_social,
            'regimen_tributario' => $request->regimen_tributario,
        ]);

        return redirect()->route('fiscal.index')->with('ok', 'Empresa creada correctamente.');
    }

    public function show(Empresa $empresa)
    {
        $retenciones = Retenciones::calcular(
            valorBase: 1_000_000,
            concepto: 'honorarios',
            calcularReteiva: $empresa->regimen_tributario?->aplicaReteiva() ?? false,
        );

        return view('fiscal.show', compact('empresa', 'retenciones'));
    }

    public function edit(Empresa $empresa)
    {
        $regimenes = RegimenTributario::cases();
        return view('fiscal.form', compact('empresa', 'regimenes'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nit'                => ['required', 'string', 'max:15', new NitRule()],
            'razon_social'       => ['required', 'string', 'max:255'],
            'regimen_tributario' => ['required', 'string'],
        ]);

        $dv = NitValidator::calcularDv($request->nit);

        $empresa->update([
            'nit'                => $request->nit,
            'dv'                 => $dv,
            'razon_social'       => $request->razon_social,
            'regimen_tributario' => $request->regimen_tributario,
        ]);

        return redirect()->route('fiscal.index')->with('ok', 'Empresa actualizada.');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('fiscal.index')->with('ok', 'Empresa eliminada.');
    }
}
