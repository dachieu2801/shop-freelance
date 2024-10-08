<?php


namespace Beike\Admin\View\DesignBuilders;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Product extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        $data['register'] = [
            'code' => 'product',
            'sort' => 0,
            'name' => trans('admin/design_builder.module_product'),
            'icon' => '&#xe606;',
        ];

        return view('admin::pages.design.module.product', $data);
    }
}
