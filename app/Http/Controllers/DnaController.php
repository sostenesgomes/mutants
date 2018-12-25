<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dna;

/**
 * Controller class for the Dna Module
 */
class DnaController extends Controller
{
    
    /**
     * Function to control /mutant request
     * 
     * @return Illuminate\Http\Response|Laravel\Lumen\Http\ResponseFactory
     */
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
        
        $dna = $request->get('dna');

        $isMutant = $this->isMutant($dna);
        
        Dna::create([
            'dna' => json_encode($dna),
            'is_mutant' => $isMutant
        ]);

        return response('', $isMutant ? 200 : 403);
    }

    /**
     * Check if is mutant with based on given dna
     *
     * @param Array $dna
     * @return boolean
     */
    private function isMutant(Array $dna) : bool
    {
        $mutantsSequences = ['AAAA', 'TTTT', 'CCCC', 'GGGG'];
        
        $sequences = [];

        $count = 0;
        
        $rows = array_map(function($row) {
            return str_split($row);
        }, $dna);
        
        $totalRows = count($rows);
        
        foreach($rows as $rowNum => $row) {
        
            $totalLetters = count($row);
        
            for($i=0; $i < $totalLetters; $i++) {
        
                $horizontalIndex = $obliqueIndexNext = $obliqueIndexPrev = $i; 
                $verticalIndex = $rowNum;
        
                $max = $horizontalIndex + 4;
                
                $horizontalWord = $verticalWord = $obliqueWordNext = $obliqueWordPrev = '';
        
                while($horizontalIndex < $max) {
                    $horizontalWord .= isset($row[$horizontalIndex]) ? $row[$horizontalIndex] : '-';
                    
                    $verticalWord   .= isset($rows[$verticalIndex][$i]) ? $rows[$verticalIndex][$i] : '-';
                    
                    $obliqueWordNext  .= isset($rows[$verticalIndex][$obliqueIndexNext]) ? $rows[$verticalIndex][$obliqueIndexNext] : '-';
                    
                    $obliqueWordPrev  .= isset($rows[$verticalIndex][$obliqueIndexPrev]) ? $rows[$verticalIndex][$obliqueIndexPrev] : '-';
        
                    $horizontalIndex++;
                    $verticalIndex++;
                    $obliqueIndexNext++;
                    $obliqueIndexPrev--;
                }
        
                $sequences[] = $horizontalWord;
                $sequences[] = $verticalWord;
                $sequences[] = $obliqueWordNext;
                $sequences[] = $obliqueWordPrev;
            }
        
        }
                
        foreach($sequences as $sequence) {
            if (in_array($sequence, $mutantsSequences)) {
                $count++;
            }
        }
        
        return $count > 1;
    }

    /**
     * Custom validation to dna rules format
     *
     * @param [type] $value
     * @return Array
     */
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
