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

}
