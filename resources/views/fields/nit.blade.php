@props([
    'value' => '',
    'extensions' => [],
    'extensionsAttributes' => null,
])

<div
    x-data="{
        nit: '{{ $value }}',
        dv: '',
        factores: [71, 67, 59, 53, 47, 43, 41, 37, 29, 23, 19, 17, 13, 7, 3],

        init() {
            if (this.nit) this.calcularDv();
        },

        calcularDv() {
            const digits = this.nit.replace(/\D/g, '');
            if (digits.length === 0) { this.dv = ''; return; }

            const n = digits.length;
            const f = this.factores.slice(this.factores.length - n);
            let suma = 0;
            for (let i = 0; i < n; i++) {
                suma += parseInt(digits[i]) * f[i];
            }

            const residuo = suma % 11;
            if (residuo === 0) this.dv = '0';
            else if (residuo === 1) this.dv = '1';
            else this.dv = String(11 - residuo);
        }
    }"
    class="flex items-end gap-2"
>
    <div class="flex-1">
        <x-moonshine::form.input-extensions
            :extensions="$extensions"
            :attributes="$extensionsAttributes"
        >
            <x-moonshine::form.input
                :attributes="$attributes->merge(['value' => $value])"
                x-model="nit"
                x-on:input="calcularDv()"
            />
        </x-moonshine::form.input-extensions>
    </div>

    <div class="w-16 shrink-0">
        <label class="block text-xs text-gray-500 mb-1">DV</label>
        <div
            class="flex items-center justify-center h-10 rounded-lg border text-lg font-bold"
            :class="dv !== '' ? 'bg-primary/10 border-primary text-primary' : 'bg-gray-100 border-gray-300 text-gray-400'"
            x-text="dv !== '' ? dv : '?'"
        ></div>
    </div>
</div>
