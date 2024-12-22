<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericModel extends Model
{

    public function saveManyRelation($relationDatas, $relationModelName, $relationName, $furtherRelationFields = [])
    {
        $relationCollection = collect($relationDatas);

        $relationsNeedToBeDelete = $this->{$relationName}->filter(function ($existingRelation, $requestRelationKey) use ($relationCollection) {
            return !$relationCollection->contains('id', $existingRelation->id);
        });

        //delete the relation where exist in db
        foreach ($relationsNeedToBeDelete as $relation) {
            $relation->delete();
        }

        $relationsNeedToBeCreate = $relationCollection->filter(function ($requestRelation, $requestRelationKey) {
            return empty($requestRelation['id']);
        });

        $relationsNeedToBeCreate = collect([...$relationsNeedToBeCreate]);

        //create the relations
        call_user_func([$this, $relationName])->createMany($relationsNeedToBeCreate->map(function ($relationData, $key) use ($furtherRelationFields) {
            return collect($relationData)->except($furtherRelationFields)->toArray();
        }))->each(function ($newRelationModel, $key) use ($relationsNeedToBeCreate, $furtherRelationFields) {
            foreach ($furtherRelationFields as $furtherRelationField) {
                $methodName = ucfirst(str_replace('_', '', ucwords($furtherRelationField, '_')));
                call_user_func([$newRelationModel, "saveMany{$methodName}"], $relationsNeedToBeCreate[$key][$furtherRelationField]);
            }
        });

        $relationsNeedToBeUpdate = $relationCollection->filter(function ($requestRelation, $requestRelationKey) {
            return !empty($requestRelation['id']);
        });

        //update the relations
        $relationsNeedToBeUpdate->each(function ($requestRelation, $requestRelationKey) use ($relationModelName, $furtherRelationFields) {
            $relationModel = call_user_func([$relationModelName, 'findOrFail'], $requestRelation['id']);
            $relationModel->update(collect($requestRelation)->except($furtherRelationFields)->toArray());
            foreach ($furtherRelationFields as $furtherRelationField) {
                $methodName = ucfirst(str_replace('_', '', ucwords($furtherRelationField, '_')));
                call_user_func([$relationModel, "saveMany{$methodName}"], $requestRelation[$furtherRelationField]);
            }
        });
    }
}
