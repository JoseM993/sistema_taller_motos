<x-app-layout>
    <div class="p-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Gestión de Cotizaciones (CU18)</h2>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente / Moto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mano Obra</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repuestos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($cotizaciones as $cot)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $cot->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cot->fecha }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-semibold">{{ $cot->cliente }}</div>
                                <div class="text-xs text-gray-500">{{ $cot->marca }} {{ $cot->modelo }} ({{ $cot->placa }})</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Bs {{ number_format($cot->total_Mano_Obra, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Bs {{ number_format($cot->total_repuesto, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Bs {{ number_format($cot->montoTotal, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($cot->estado == 'APROBADA') bg-green-100 text-green-800 
                                    @elseif($cot->estado == 'RECHAZADA') bg-red-100 text-red-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $cot->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <form action="{{ route('cotizaciones.estado', $cot->id) }}" method="POST" class="inline-flex space-x-2">
                                    @csrf
                                    <input type="hidden" name="estado" value="APROBADA">
                                    <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 px-2 py-1 rounded border border-green-200 text-xs">Aprobar</button>
                                </form>
                                <form action="{{ route('cotizaciones.estado', $cot->id) }}" method="POST" class="inline-flex space-x-2">
                                    @csrf
                                    <input type="hidden" name="estado" value="RECHAZADA">
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded border border-red-200 text-xs">Rechazar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No hay cotizaciones registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>