<?php


namespace Beike\Repositories;

use Beike\Models\Address;

class AddressRepo
{
    /**
     * 创建一个address记录
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        return Address::query()->create($data);
    }

    /**
     * @param $address
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function update($address, $data)
    {
        if (! $address instanceof Address) {
            $address = Address::query()->find($address);
        }
        if (! $address) {
            throw new \Exception("地址id {$address} 不存在");
        }
        $address->update($data);

        return $address;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        return Address::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $address = Address::query()->find($id);
        if ($address) {
            $address->delete();
        }
    }

    /**
     * 获取某个客户地址列表
     *
     * @param $customer
     * @return mixed
     */
    public static function listByCustomer($customer)
    {
        if (gettype($customer) != 'object') {
            $customer = CustomerRepo::find($customer);
        }
        if ($customer) {
            return $customer->addresses()->with('country')->get();
        }

        return collect();
    }
}
