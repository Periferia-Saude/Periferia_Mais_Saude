<?php

namespace App\Http\Controllers;

use App\Http\Requests\Location\IndexLocationRequest;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use Illuminate\Http\Request;
use \App\Models\Location;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Description;
use App\Models\Address;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(indexLocationRequest $request)
    {
        $validated = $request->validated();

        //formula utilizada: Distância = raiz quadrada de (x2 - x1)² + (y2 - y1)²
        $locations = Location::select('id', 'photo', 'latitude', 'longitude')
            ->selectRaw(
                'SQRT(POW(latitude - ?, 2) + POW(longitude - ?, 2)) as distance',
                [$validated['latitude'], $validated['longitude']]
            )
            ->orderBy('distance')
            ->take(45)
            ->get();
        // $locations = Location::all();
        return response($locations, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request)
    {
        $validated = $request->validated();

        if (isset($validated['photo'])) {
            $photoPath = $validated['photo']->store('photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $location = Location::create($validated);

        if (isset($validated['description'])) {
            $location->description()->create($validated['description']);
        }

        if (isset($validated['services'])) {
            $location->services()->sync($validated['services']);
        }

        return response()->json($location, 201);
    }

    public function attachServices(Request $request, $locationId)
    {
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*' => 'integer|exists:services,id',
        ]);

        $location = Location::findOrFail($locationId);

        // Faz a associação (sem remover anteriores)
        $location->services()->syncWithoutDetaching($validated['services']);

        return response()->json([
            'message' => 'Serviços vinculados com sucesso!',
            'services' => $location->services
        ]);
    }

    /**
     * Display the specified resource.
     */

    public function show($id){

        $location = Location::with([
            // 'description',
            'description.address',
            'services'
        ])->find($id);

        if (!$location) {
            return response()->json(['message' => 'Local não encontrado'], 404);
        }

        return response()->json($location);
    }

       public function list(){
        $location = Location::with([
            'description.address',
            'services'
        ])->get();

        return response()->json($location);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
     try {
        $location = Location::with(['description', 'address', 'services'])->findOrFail($id);

        $validated = $request->validate([
            'description.name' => 'required|string|max:255',
            'description.contact' => 'nullable|string|max:255',
            'address.street' => 'required|string|max:255',
            'address.number' => 'nullable|string|max:20',
            'address.district' => 'required|string|max:255',
            'address.cep' => 'nullable|string|max:20',
            'address.reference' => 'nullable|string|max:255',
            'services' => 'array',
            'services.*.id' => 'integer|exists:services,id'
        ]);

        $location->description->update($validated['description']);
        $location->address->update($validated['address']);

        if (isset($validated['services'])) {
            $location->services()->sync(collect($validated['services'])->pluck('id'));
        }

        $location->touch();
        return response()->json(['message' => 'Local atualizado com sucesso!', 'data' => $location]);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Erro de validação',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao atualizar local',
            'error' => $e->getMessage()
        ], 500);
    }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
{
    try {
        $location = Location::findOrFail($id);

        $location->services()->detach();

        if ($location->address) {
            $location->address->delete();
        }

        if ($location->description) {
            $location->description->delete();
        }

        $location->delete();

        return response()->json(['message' => 'Local excluído com sucesso'], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao excluir local',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
