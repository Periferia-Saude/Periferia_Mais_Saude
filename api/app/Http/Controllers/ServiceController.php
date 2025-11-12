<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Location;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function index()
    {
        $services = Service::all();
        return response()->json($services, 200);
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $service = Service::create($validated);
        return response()->json($service, 201);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $service->update($validated);

        return response()->json($service, 200);
    }

    public function destroy($id)
    {
        try {
        $service = Service::findOrFail($id);
        $service->locations()->detach();
        $service->delete();

        return response()->json(['message' => 'Serviço excluído com sucesso'], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao excluir serviços',
            'error' => $e->getMessage(),
        ], 500);
    }
}
    }

  

