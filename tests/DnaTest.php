<?php

/**
 * Class for test DNA module
 */
class DnaTest extends TestCase
{
    /**
     * Test to check DNA input required
     *
     * @return void
     */
    public function testDnaRequired()
    {
        $this->json('POST', '/mutant')
            ->seeStatusCode(422)
            ->seeJson([
                'dna' => ["The dna field is required."]
            ]);
    }

    /**
     * Test to check if dna is array
     *
     * @return void
     */
    public function testDnaIsArray()
    {
        $this->json('POST', '/mutant', ['dna' => 'string'])
            ->seeStatusCode(422)
            ->assertContains("The dna must be an array.", json_decode($this->response->getContent(), true)['dna']);
    }

    /**
     * Test to check if dna is array of strings
     *
     * @return void
     */
    public function testDnaIsArrayOfStrings()
    {
        $this->json('POST', '/mutant', ['dna' => ["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA", 10]])
            ->seeStatusCode(422)
            ->assertContains("The dna must be an array of strings.", json_decode($this->response->getContent(), true)['dna']);
    }

    /**
     * Test to check if dna is an array of NxN
     *
     * @return void
     */
    public function testDnaIsArrayOfNxN()
    {
        $this->json('POST', '/mutant', ['dna' => ["ATGG","CAGTGC","TTATGT","AGAAGG","CCCCTA", 'AAA']])
            ->seeStatusCode(422)
            ->assertContains("The dna must be an array of NxN.", json_decode($this->response->getContent(), true)['dna']);
    }

    /**
     * Test to check if dna is an array with just letters allowed
     *
     * @return void
     */
    public function testDnaIsArrayWithLettersAllowed()
    {
        $this->json('POST', '/mutant', ['dna' => ["BTGG","CAGTGC","TTATGT","AGAAGG","CCCCTA", 'AAA']])
            ->seeStatusCode(422)
            ->assertContains("The dna must be an array that contain just letters (A, T, C, G).", json_decode($this->response->getContent(), true)['dna']);
    }

    /**
     * Test if given dna is mutant
     *
     * @return void
     */
    public function testIsMutantTrue()
    {
        $this->json('POST', '/mutant', ['dna' => ["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]])
            ->seeStatusCode(200)
            ->assertEmpty($this->response->getContent());
    }

    /**
     * Test if given dna is not mutant
     *
     * @return void
     */
    public function testIsMutantFalse()
    {
        $this->json('POST', '/mutant', ['dna' => ["ATGCGA","CAGTGC","TTATTT","AGACGG","GCGTCA","TCACTG"]])
            ->seeStatusCode(403)
            ->assertEmpty($this->response->getContent());
    }

    /**
     * Test if dna is stored in database
     *
     * @return void
     */
    public function testDnaDatabase()
    {
        $dna = $this->buildRandomDnaSequence();
        
        $this->json('POST', '/mutant', ['dna' => $dna])
            ->seeInDatabase('dnas', [
                'dna' => json_encode($dna)
            ]);
    }

    /**
     * Build a random Dna Sequence for tests
     *
     * @return void
     */
    private function buildRandomDnaSequence() : Array
    {
        $dna = [];
        
        $letters = ['A', 'T', 'C', 'G'];

        for($i=0; $i < 6; $i++) {
            
            $sequence = '';

            for ($j=0; $j < 6; $j++) {
                $sequence .= $letters[rand(0, 3)];
            }

            $dna[$i] = $sequence;
        }

        return $dna;
    }

}
