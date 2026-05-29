<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Fiscal — Plugin Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-4xl mx-auto py-10 px-4">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">CRUD Laravel nativo — Plugin fiscal</h1>
            <p class="text-sm text-gray-500">Prueba de <code>NitRule</code>, <code>NitValidator</code>, <code>Retenciones</code> y enums</p>
        </div>
        <a href="{{ route('fiscal.index') }}" class="text-sm text-indigo-600 hover:underline">← Listado</a>
    </div>

    @if(session('ok'))
        <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 rounded-lg text-sm">
            {{ session('ok') }}
        </div>
    @endif

    {{ $slot }}
</div>
</body>
</html>
