<?php

namespace Beike\Admin\View\DesignBuilders;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RichText extends Component
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
            'code' => 'rich_text',
            'sort' => 0,
            'name' => trans('admin/design_builder.module_rich_text'),
            'icon' => '&#xe601;',
        ];

        return view('admin::pages.design.module.rich_text', $data);
    }
}
