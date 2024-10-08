<?php
/**
 * BrandDetail.php
 *

 * @created    2022-07-28 15:33:06
 * @modified   2022-07-28 15:33:06
 */

namespace Beike\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandDetail extends JsonResource
{
    /**
     * @throws \Exception
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'logo'       => image_origin($this->logo),
            'sort_order' => $this->sort_order,
            'url'        => shop_route('brands.show', $this->id),
            'first'      => $this->first,
        ];
    }
}
