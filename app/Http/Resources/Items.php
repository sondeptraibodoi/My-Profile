<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Items extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->resource->toArray());
        if ($this->resource instanceof LengthAwarePaginator) {

            foreach ($this->collection as $key => $value) {
                if (isset($value['image']) && is_resource($value['image']) && get_resource_type($value['image']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image']);

                    $value['image'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['image_small']) && is_resource($value['image_small']) && get_resource_type($value['image_small']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image_small']);

                    $value['image_small'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['image_medium']) && is_resource($value['image_medium']) && get_resource_type($value['image_medium']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image_medium']);

                    $value['image_medium'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }

                if (isset($value['model_image']) && is_resource($value['model_image']) && get_resource_type($value['model_image']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image']);

                    $value['model_image'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['model_image_medium']) && is_resource($value['model_image_medium']) && get_resource_type($value['model_image_medium']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image_medium']);

                    $value['model_image_medium'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['model_image_small']) && is_resource($value['model_image_small']) && get_resource_type($value['model_image_small']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image_small']);

                    $value['model_image_small'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }

            }
            $result = [
                'list' => $this->collection,
                'pagination' => [
                    'count' => $this->count(),
                    'hasMoreItems' => $this->hasMorePages(),
                    'page' => $this->currentPage(),
                    'total' => $this->total(),
                    'totalPage' => $this->lastPage(),
                    'itemsPerPage' => (float) $this->perPage(),
                ],
            ];
        } else if ($this->resource instanceof Paginator) {
            foreach ($this->collection as $key => $value) {
                if (isset($value['image']) && is_resource($value['image']) && get_resource_type($value['image']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image']);

                    $value['image'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['image_small']) && is_resource($value['image_small']) && get_resource_type($value['image_small']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image_small']);

                    $value['image_small'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['image_medium']) && is_resource($value['image_medium']) && get_resource_type($value['image_medium']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image_medium']);

                    $value['image_medium'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }

                if (isset($value['model_image']) && is_resource($value['model_image']) && get_resource_type($value['model_image']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image']);

                    $value['model_image'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['model_image_medium']) && is_resource($value['model_image_medium']) && get_resource_type($value['model_image_medium']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image_medium']);

                    $value['model_image_medium'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['model_image_small']) && is_resource($value['model_image_small']) && get_resource_type($value['model_image_small']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image_small']);

                    $value['model_image_small'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }

            }

            $result = [
                'list' => $this->collection,
                'pagination' => [
                    'count' => $this->count(),
                    'hasMoreItems' => $this->hasMorePages(),
                    'page' => $this->currentPage(),
                    'itemsPerPage' => (float) $this->perPage(),
                ],
            ];
        } else {
            foreach ($this->collection as $key => $value) {
                if (isset($value['image']) && is_resource($value['image']) && get_resource_type($value['image']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image']);
                    $value['image'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['image_small']) && is_resource($value['image_small']) && get_resource_type($value['image_small']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image_small']);
                    $value['image_small'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['image_medium']) && is_resource($value['image_medium']) && get_resource_type($value['image_medium']) === 'stream') {
                    $my_bytea = stream_get_contents($value['image_medium']);
                    $value['image_medium'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }

                if (isset($value['model_image']) && is_resource($value['model_image']) && get_resource_type($value['model_image']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image']);
                    $value['model_image'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['model_image_medium']) && is_resource($value['model_image_medium']) && get_resource_type($value['model_image_medium']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image_medium']);
                    $value['model_image_medium'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }
                if (isset($value['model_image_small']) && is_resource($value['model_image_small']) && get_resource_type($value['model_image_small']) === 'stream') {
                    $my_bytea = stream_get_contents($value['model_image_small']);
                    $value['model_image_small'] = $my_bytea;
                    // Các trường hình ảnh khác cũng sẽ được gán giá trị tương ứng
                }

            }

            $result = $this->collection;
        }

        return $result;
    }
}
