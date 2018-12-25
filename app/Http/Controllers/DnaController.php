<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DnaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function store(Request $request) 
    {
        $this->validate($request, [
            'dna' => [
                'required', 
                'array', 
                function ($attribute, $value, $fail) {
                    $errors = $this->validateDnaFormat($value);

                    if (count($errors)) {
                        $fail(array_unique($errors));
                    }
                }
            ]
        ]);

    }

    private function isDna(Array $dna) : boolean
    {

    }

    private function validateDnaFormat($value) : Array
    {
        $errors = [];

        try {
            $totalRows = count($value);

            foreach ($value as $item) {
                
                if (! is_string($item)) {
                    $errors[] = 'The dna must be an array of strings.';
                }

                if (strlen($item) != $totalRows) {
                    $errors[] = 'The dna must be an array of NxN.';
                }

                $letters = str_split($item);

                foreach($letters as $letter) {
                    if (! in_array($letter, ['A', 'T', 'C', 'G'])) {
                        $errors[] = 'The dna must be an array that contain just letters (A, T, C, G).';
                    }
                }
            }

        } catch (\Exception $e) {
            $errors[] = 'The dna is invalid';
        }

        return $errors;
    }

}
