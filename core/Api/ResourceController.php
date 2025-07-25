<?php

namespace Core\Api;

use Core\Http\Request;
use Core\Http\Response;

abstract class ResourceController extends ApiController
{
    protected string $modelClass;

    public function index(Request $request): Response
    {
        try {
            $model = $this->modelClass;
            $items = $model::all();
            
            return $this->success($items, 'Ressources récupérées avec succès');
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la récupération des ressources', 500);
        }
    }

    public function show(Request $request, int $id): Response
    {
        try {
            $model = $this->modelClass;
            $item = $model::find($id);
            
            if (!$item) {
                return $this->error('Ressource non trouvée', 404);
            }
            
            return $this->success($item, 'Ressource récupérée avec succès');
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la récupération de la ressource', 500);
        }
    }

    public function store(Request $request): Response
    {
        try {
            $model = $this->modelClass;
            $item = new $model($request->all());
            
            if ($item->save()) {
                return $this->success($item, 'Ressource créée avec succès', 201);
            }
            
            return $this->error('Erreur lors de la création de la ressource', 400);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la création de la ressource', 500);
        }
    }

    public function update(Request $request, int $id): Response
    {
        try {
            $model = $this->modelClass;
            $item = $model::find($id);
            
            if (!$item) {
                return $this->error('Ressource non trouvée', 404);
            }
            
            $item->fill($request->all());
            
            if ($item->save()) {
                return $this->success($item, 'Ressource mise à jour avec succès');
            }
            
            return $this->error('Erreur lors de la mise à jour de la ressource', 400);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la mise à jour de la ressource', 500);
        }
    }

    public function destroy(Request $request, int $id): Response
    {
        try {
            $model = $this->modelClass;
            $item = $model::find($id);
            
            if (!$item) {
                return $this->error('Ressource non trouvée', 404);
            }
            
            if ($item->delete()) {
                return $this->success([], 'Ressource supprimée avec succès');
            }
            
            return $this->error('Erreur lors de la suppression de la ressource', 400);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la suppression de la ressource', 500);
        }
    }
}

