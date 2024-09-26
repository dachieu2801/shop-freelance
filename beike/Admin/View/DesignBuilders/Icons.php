<?php

namespace Beike\Admin\View\DesignBuilders;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icons extends Component
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
            'code' => 'icons',
            'sort' => 0,
            'name' => trans('admin/design_builder.module_icons'),
            'icon' => '&#xe60e;',
        ];

        return view('admin::pages.design.module.icons', $data);
    }
}
