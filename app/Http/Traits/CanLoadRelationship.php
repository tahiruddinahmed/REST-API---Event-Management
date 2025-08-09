<?php 

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

trait CanLoadRelationship
{
    public function loadRelationships(
        Model|EloquentBuilder|QueryBuilder $for,
        ?array $relations = null
    ): Model|EloquentBuilder|QueryBuilder {
        $relations = $relations ?? $this->relations ?? [];

        // if $for is Model, load the relation directly
        if($for instanceof Model) {
            foreach($relations as $relation) {    
                if($this->shouldIncludeRelation($relation)) {
                    $for->load($relation);
                }
            }
        } else {
            // for builder, use when()
            foreach($relations as $relation) {
                $for->when(
                    $this->shouldIncludeRelation($relation),
                    fn($q) => $q->with($relation)
                );        
            }
        }
    return $for;
}


    protected function shouldIncludeRelation(string $relation): bool {
        $include = request()->query('include');

        if(!$include) {
            return false;
        }
        
        $relations = array_map('trim', explode(',', $include));  

        return in_array($relation, $relations);
    }
}