<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);

        return [

            'id' =>$this->id,
            'title'=>$this->title,
            'bio'=>$this->description,
            // Task All
//            'Task'=>$this->whenLoaded('tasks'),
        //Task get user
            'tasks'=> TasksResource::collection($this->whenLoaded('tasks'))
        ];
    }
}
