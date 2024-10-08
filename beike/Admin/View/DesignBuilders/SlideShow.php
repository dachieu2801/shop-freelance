<?php


namespace Beike\Admin\View\DesignBuilders;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SlideShow extends Component
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
            'code'  => 'slideshow',
            'sort'  => 0,
            'name'  => trans('admin/design_builder.module_slideshow'),
            'icon'  => '&#xe61b;',
            'style' => 'font-size: 40px;',
        ];

        return view('admin::pages.design.module.slideshow', $data);
    }
}
