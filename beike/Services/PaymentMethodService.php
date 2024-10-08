<?php
/**
 * PaymentService.php
 *

 * @created    2023-11-10 17:37:52
 * @modified   2023-11-10 17:37:52
 */

namespace Beike\Services;

class PaymentMethodService
{
    public static function getCurrentMethod($payments, $methodCode)
    {
        if (empty($payments) || empty($methodCode)) {
            return null;
        }
        foreach ($payments as $payment) {
            if ($payment['code'] == $methodCode) {
                return $payment;
            }
        }

        return null;
    }
}
