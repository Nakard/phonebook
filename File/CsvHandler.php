<?php
/**
 * CsvHandler.php
 *
 * Creation date: 2014-04-27
 * Creation time: 16:19
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\File;
use Phonebook\Exceptions\MissingCsvHeadersException;

/**
 * Class CsvHandler
 * Handler for import, export and pre-parsing CSV
 * @package Phonebook\File
 */
class CsvHandler {

    /**
     * Exports phone numbers to csv
     *
     * @param string    $filename
     * @param array     $phoneNumbers
     * @param array     $header
     */
    public function exportPhoneNumbers(
        &$filename,
        array &$phoneNumbers,
        array $header = array('First name','Last name','Phone number')
    )
    {
        $handle = fopen($filename,'w');
        fputcsv($handle,$header);
        foreach($phoneNumbers as $number)
        {
            fputcsv($handle, $number);
        }
        fclose($handle);
    }

    /**
     * Reads uploaded file and gathers data according to number owner.
     *
     * @param   string  $filename
     * @return  array
     */
    public function parseImport(&$filename)
    {
        $preparedArray = array();
        $handle = fopen($filename,'r');
        $translation = array_flip(fgetcsv($handle, 512));
        $this->checkHeaders($translation);
        while($line = fgetcsv($handle,512))
        {
            $fullname = $line[$translation['First name']].' '.$line[$translation['Last name']];
            if(!isset($preparedArray[$fullname]))
                $preparedArray[$fullname] = array();
            $preparedArray[$fullname][] = $line[$translation['Phone number']];
        }
        return $preparedArray;
    }

    /**
     * Checks if import csv file has appropriate headers
     *
     * @param   array $translation
     * @throws  \Phonebook\Exceptions\MissingCsvHeadersException
     */
    private function checkHeaders(array &$translation)
    {
        $headers = array('First name','Last name', 'Phone number');
        foreach($headers as $header)
        {
            if(!isset($translation[$header]))
                throw new MissingCsvHeadersException();
        }
    }
} 