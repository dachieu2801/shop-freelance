<?php


namespace Beike\Admin\View\DesignBuilders;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image301 extends Component
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
            'code' => 'image301',
            'sort' => 0,
            'name' => trans('admin/builder.modules_image_301'),
            'icon' => asset('image/module/image_301.png'),
        ];

        return view('admin::pages.design.module.image301', $data);
    }
}
