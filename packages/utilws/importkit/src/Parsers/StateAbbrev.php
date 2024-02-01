<?php
namespace Utilws\Importkit\Parsers;

/**
 * Convert state names to postal 2 letter abbreviations
 */
class StateAbbrev extends BaseParser
{
    protected static $map = [
        'alabama'              => 'AL',
        'alaska'               => 'AK',
        'american samoa'       => 'AS',
        'arizona'              => 'AZ',
        'arkansas'             => 'AR',
        'california'           => 'CA',
        'colorado'             => 'CO',
        'connecticut'          => 'CT',
        'delaware'             => 'DE',
        'district of columbia' => 'DC',
        'florida'              => 'FL',
        'georgia'              => 'GA',
        'guam'                 => 'GU',
        'hawaii'               => 'HI',
        'idaho'                => 'ID',
        'illinois'             => 'IL',
        'indiana'              => 'IN',
        'iowa'                 => 'IA',
        'kansas'               => 'KS',
        'kentucky'             => 'KY',
        'louisiana'            => 'LA',
        'maine'                => 'ME',
        'marshall islands'     => 'MH',
        'maryland'             => 'MD',
        'massachusetts'        => 'MA',
        'michigan'             => 'MI',
        'minnesota'            => 'MN',
        'mississippi'          => 'MS',
        'missouri'             => 'MO',
        'montana'              => 'MT',
        'nebraska'             => 'NE',
        'nevada'               => 'NV',
        'new hampshire'        => 'NH',
        'new jersey'           => 'NJ',
        'new mexico'           => 'NM',
        'new york'             => 'NY',
        'north carolina'       => 'NC',
        'north dakota'         => 'ND',
        'ohio'                 => 'OH',
        'oklahoma'             => 'OK',
        'oregon'               => 'OR',
        'palau'                => 'PW',
        'pennsylvania'         => 'PA',
        'puerto rico'          => 'PR',
        'rhode island'         => 'RI',
        'south carolina'       => 'SC',
        'south dakota'         => 'SD',
        'tennessee'            => 'TN',
        'texas'                => 'TX',
        'utah'                 => 'UT',
        'vermont'              => 'VT',
        'virgin islands'       => 'VI',
        'virginia'             => 'VA',
        'washington'           => 'WA',
        'west virginia'        => 'WV',
        'wisconsin'            => 'WI',
        'wyoming'              => 'WY',
    ];

    protected function parseValue($input)
    {
        $value = strtolower($input);

        if (preg_match('/^[a-z][a-z]$/', $value)) {
            return strtoupper($value);
        }

        return self::$map[$value] ?? $this->emptyValue();
    }

    public static function maplower()
    {
        foreach (self::$map as $proper => $abbrev) {
            $state = "'" . strtolower($proper) . "'";
            printf("%' -22s => '%s',\n", $state, $abbrev);
        }
    }

}
