<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
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
            'id'=>$this->id,
            'title'=>$this->title,
            'bio'=>$this->description,
            'due_date'=>$this->due_date,
            'تم اضافتها'=>$this->created_at,
            'updated_at'=>$this->upeated_at,
            'category'=> new CategoryResource($this->whenLoaded('category'))


//            'category'=>$this->whenLoaded('category')
// اذا كان واحد ماتحط collection
//            'category'=> CategoryResource::collection($this->whenLoaded('category'))

            //sing


        ];
    }
}
