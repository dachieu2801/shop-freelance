<?php

namespace Plugin\Bestseller\Admin\View\DesignBuilders;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Bestseller extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        $data['register'] = [
            'code'      => 'bestseller',
            'sort'      => 0,
            'name'      => trans('Bestseller::common.module_name'),
            'icon'      => plugin_asset('Bestseller', 'image/icon.png'),
            'view_path' => 'Bestseller::shop/design_module_bestseller',
        ];

        return view('Bestseller::admin/design_module_bestseller', $data);
    }
}
