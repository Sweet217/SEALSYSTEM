<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MacAddressController extends Controller
{
   /**
     * Genera y devuelve una dirección MAC aleatoria en formato separado por dos puntos (XX:XX:XX:XX:XX:XX).
     *
     * @param Request $request (no utilizado en esta implementación)
     * @return JsonResponse Una respuesta JSON que contiene la dirección MAC generada.
     */
    public function getMacAddress(Request $request): JsonResponse
    {
        // Simula la generación de una dirección MAC aleatoria (nota de seguridad: no es una dirección MAC real)
        $mac = $this->generateRandomMac();

        // Devuelve la dirección MAC generada como una respuesta JSON
        return response()->json(['mac' => $mac]);
    }

    /**
     * Genera una cadena de dirección MAC aleatoria.
     *
     * @return string La dirección MAC aleatoria en formato separado por dos puntos (XX:XX:XX:XX:XX:XX).
     */
    private function generateRandomMac(): string
    {
        $mac = [];
        for ($i = 0; $i < 6; $i++) {
            // Genera un byte aleatorio (dos dígitos hexadecimales) y lo agrega a la matriz de la dirección MAC
            $mac[] = sprintf('%02X', mt_rand(0, 255));
        }

        // Implosiona los elementos de la matriz en una cadena separada por dos puntos que representa la dirección MAC
        return implode(':', $mac);
    }
}