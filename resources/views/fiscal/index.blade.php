<x-fiscal.layout>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Empresas registradas</h2>
        <a href="{{ route('fiscal.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            + Nueva empresa
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Razón social</th>
                    <th class="px-4 py-3 text-left">NIT</th>
                    <th class="px-4 py-3 text-left">DV</th>
                    <th class="px-4 py-3 text-left">Régimen</th>
                    <th class="px-4 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($empresas as $empresa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $empresa->razon_social }}</td>
                        <td class="px-4 py-3 font-mono">{{ $empresa->nit }}</td>
                        <td class="px-4 py-3 font-mono font-bold text-indigo-600">{{ $empresa->dv }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $empresa->regimen_tributario?->label() }}</td>
                        <td class="px-4 py-3 flex gap-3">
                            <a href="{{ route('fiscal.show', $empresa) }}" class="text-indigo-600 hover:underline">Ver</a>
                            <a href="{{ route('fiscal.edit', $empresa) }}" class="text-amber-600 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('fiscal.destroy', $empresa) }}"
                                  onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                            Sin registros. <a href="{{ route('fiscal.create') }}" class="text-indigo-600 hover:underline">Crear el primero</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $empresas->links() }}</div>

</x-fiscal.layout>
