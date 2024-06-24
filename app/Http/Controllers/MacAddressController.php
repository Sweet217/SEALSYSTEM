<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MacAddressController extends Controller
{
    public function getMacAddress(Request $request)
    {
        // Esto es solo para simulaciones
        $mac = $this->generateRandomMac();

        return response()->json(['mac' => $mac]);
    }

    private function generateRandomMac()
    {
        $mac = [];
        for ($i = 0; $i < 6; $i++) {
            $mac[] = sprintf('%02X', mt_rand(0, 255));
        }
        return implode(':', $mac);
    }
}