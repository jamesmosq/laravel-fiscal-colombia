<x-fiscal.layout>

    <div class="grid gap-6">

        {{-- Datos empresa --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">{{ $empresa->razon_social }}</h2>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">NIT</dt>
                    <dd class="font-mono font-bold text-gray-800">{{ $empresa->nit }}-{{ $empresa->dv }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Régimen tributario</dt>
                    <dd class="text-gray-800">{{ $empresa->regimen_tributario?->label() }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Aplica reteiva</dt>
                    <dd class="{{ $empresa->regimen_tributario?->aplicaReteiva() ? 'text-amber-600 font-semibold' : 'text-gray-400' }}">
                        {{ $empresa->regimen_tributario?->aplicaReteiva() ? 'Sí' : 'No' }}
                    </dd>
                </div>
            </dl>
            <div class="mt-4 flex gap-3">
                <a href="{{ route('fiscal.edit', $empresa) }}"
                   class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600">Editar</a>
                <form method="POST" action="{{ route('fiscal.destroy', $empresa) }}"
                      onsubmit="return confirm('¿Eliminar esta empresa?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600">Eliminar</button>
                </form>
            </div>
        </div>

        {{-- Retenciones calculadas (Retenciones::calcular) --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-gray-700 mb-1">
                Simulación de retenciones sobre $1.000.000 (honorarios)
            </h3>
            <p class="text-xs text-gray-400 mb-4">
                Calculado con <code>Retenciones::calcular()</code> del plugin
            </p>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div class="bg-gray-50 rounded-lg p-3">
                    <dt class="text-gray-500 text-xs">Retefuente</dt>
                    <dd class="font-bold text-gray-800">${{ number_format($retenciones['retefuente']) }}</dd>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <dt class="text-gray-500 text-xs">Reteiva</dt>
                    <dd class="font-bold text-gray-800">${{ number_format($retenciones['reteiva']) }}</dd>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <dt class="text-gray-500 text-xs">Reteica</dt>
                    <dd class="font-bold text-gray-800">${{ number_format($retenciones['reteica']) }}</dd>
                </div>
                <div class="bg-indigo-50 rounded-lg p-3">
                    <dt class="text-indigo-500 text-xs">Total retenciones</dt>
                    <dd class="font-bold text-indigo-700">${{ number_format($retenciones['total_retenciones']) }}</dd>
                </div>
                <div class="col-span-2 bg-green-50 rounded-lg p-3">
                    <dt class="text-green-600 text-xs">Valor neto a pagar</dt>
                    <dd class="text-xl font-bold text-green-700">${{ number_format($retenciones['valor_neto']) }}</dd>
                </div>
            </dl>
        </div>

    </div>

</x-fiscal.layout>
