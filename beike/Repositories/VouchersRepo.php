<?php

namespace Beike\Repositories;

use Beike\Models\Vouchers;
use Illuminate\Http\Request;

class VouchersRepo
{
    private function generateRandomCode(int $length)
    {
        $characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; // Các ký tự có thể xuất hiện trong mã
        $charactersLength = strlen($characters);
        $randomCode       = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomCode;
    }

    public function create(array $data)
    {
        $voucherCode = $this->generateRandomCode(13);

        // Thêm mã voucher vào dữ liệu
        $data['code'] = $voucherCode;

        // Tạo và trả về voucher mới
        return Vouchers::create($data);
    }

    public function getById(int $id)
    {
        return Vouchers::find($id);
    }

    public function getByIdActive(int $id)
    {
        return Vouchers::where('id', $id)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('used_count', '>=', 'usage_limit')
            ->first();
    }

    public function update(int $id, array $data)
    {
        $voucher = Vouchers::find($id);

        if ($voucher) {
            return $voucher->update($data);
        }

        return false;
    }

    public function delete(int $id)
    {
        $voucher = Vouchers::find($id);

        if ($voucher) {
            return $voucher->delete();
        }

        return false;
    }

    public function getAllWithFilters(Request $request)
    {
        $query = Vouchers::query();

        if ($request->has('code')) {
            $query->where('code', 'like', '%' . $request->input('code') . '%');
        }

        if ($request->has('name')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('name')) . '%']);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'expired') {
                $now = now();
                $query->where('end_date', '<', $now);
            } elseif (in_array($status, ['active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        if ($request->has('sort_by') && in_array($request->input('sort_by'), ['asc', 'desc'])) {
            $query->orderBy('created_at', $request->input('sort_by'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Phân trang
        return $query->paginate($request->input('per_page', 15));
    }

    public function getActiveVouchers($currentDate)
    {
        return Vouchers::where('status', 'active')
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $currentDate);
            })
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $currentDate);
            })
            ->get()->jsonSerialize();
    }
}
