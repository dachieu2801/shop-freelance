<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\TaxRateRequest;
use Beike\Admin\Repositories\TaxRateRepo;
use Beike\Models\Region;
use Illuminate\Http\Request;

class TaxRateController
{
    public function index()
    {
        $data = [
            'tax_rates' => TaxRateRepo::getList(),
            'regions'   => Region::all(),
        ];

        $data = hook_filter('admin.tax_rate.index.data', $data);

        return view('admin::pages.tax_rates.index', $data);
    }

    public function store(TaxRateRequest $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $taxRate     = TaxRateRepo::createOrUpdate($requestData);
        $taxRate->load('region');

        return json_success(trans('common.created_success'), $taxRate);
    }

    public function update(TaxRateRequest $request, int $taxRateId)
    {
        $requestData       = json_decode($request->getContent(), true);
        $requestData['id'] = $taxRateId;
        $taxRate           = TaxRateRepo::createOrUpdate($requestData);
        $taxRate->load('region');

        return json_success(trans('common.updated_success'), $taxRate);
    }

    public function destroy(Request $request, int $taxRateId)
    {
        if ($taxRateId != 1) {
            TaxRateRepo::deleteById($taxRateId);
            return json_success(trans('common.deleted_success'));
        }
        return json_fail('Không thể xoá');
    }
}
