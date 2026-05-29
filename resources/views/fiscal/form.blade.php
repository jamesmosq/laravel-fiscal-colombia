<x-fiscal.layout>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-6">
            {{ isset($empresa) ? 'Editar empresa' : 'Nueva empresa' }}
        </h2>

        <form method="POST"
              action="{{ isset($empresa) ? route('fiscal.update', $empresa) : route('fiscal.store') }}">
            @csrf
            @isset($empresa) @method('PUT') @endisset

            {{-- NIT --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    NIT <span class="text-gray-400 font-normal">(sin dígito verificador)</span>
                </label>
                <input type="text" name="nit"
                       value="{{ old('nit', $empresa->nit ?? '') }}"
                       placeholder="Ej: 8001972687"
                       class="w-full border rounded-lg px-3 py-2 text-sm @error('nit') border-red-400 @else border-gray-300 @enderror">
                @error('nit')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">
                    El DV se calcula automáticamente al guardar (via <code>NitValidator::calcularDv()</code>).
                </p>
            </div>

            {{-- Razón social --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Razón social</label>
                <input type="text" name="razon_social"
                       value="{{ old('razon_social', $empresa->razon_social ?? '') }}"
                       placeholder="Ej: Constructora ABC S.A.S"
                       class="w-full border rounded-lg px-3 py-2 text-sm @error('razon_social') border-red-400 @else border-gray-300 @enderror">
                @error('razon_social')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Régimen tributario --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Régimen tributario</label>
                <select name="regimen_tributario"
                        class="w-full border rounded-lg px-3 py-2 text-sm @error('regimen_tributario') border-red-400 @else border-gray-300 @enderror">
                    <option value="">— Seleccionar —</option>
                    @foreach($regimenes as $regimen)
                        <option value="{{ $regimen->value }}"
                            {{ old('regimen_tributario', $empresa->regimen_tributario?->value ?? '') === $regimen->value ? 'selected' : '' }}>
                            {{ $regimen->label() }}
                        </option>
                    @endforeach
                </select>
                @error('regimen_tributario')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    {{ isset($empresa) ? 'Actualizar' : 'Guardar' }}
                </button>
                <a href="{{ route('fiscal.index') }}"
                   class="px-5 py-2 rounded-lg text-sm border border-gray-300 text-gray-600 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

</x-fiscal.layout>
