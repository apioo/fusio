<?php
namespace App\Action;

class Library
{
    public const COUNTRIES = [
        [
            'name' => 'Aruba',
            'code' => 'AW',
            'dial_code' => '297',
        ],
        [
            'name' => 'Afghanistan',
            'code' => 'AF',
            'dial_code' => '93',
        ],
        [
            'name' => 'Angola',
            'code' => 'AO',
            'dial_code' => '244',
        ],
        [
            'name' => 'Anguilla',
            'code' => 'AI',
            'dial_code' => '1264',
        ],
        [
            'name' => 'Ãland Islands',
            'code' => 'AX',
            'dial_code' => '358',
        ],
        [
            'name' => 'Albania',
            'code' => 'AL',
            'dial_code' => '355',
        ],
        [
            'name' => 'Andorra',
            'code' => 'AD',
            'dial_code' => '376',
        ],
        [
            'name' => 'United Arab Emirates',
            'code' => 'AE',
            'dial_code' => '971',
        ],
        [
            'name' => 'Argentina',
            'code' => 'AR',
            'dial_code' => '54',
        ],
        [
            'name' => 'Armenia',
            'code' => 'AM',
            'dial_code' => '374',
        ],
        [
            'name' => 'American Samoa',
            'code' => 'AS',
            'dial_code' => '1684',
        ],
        [
            'name' => 'Antigua and Barbuda',
            'code' => 'AG',
            'dial_code' => '1268',
        ],
        [
            'name' => 'Australia',
            'code' => 'AU',
            'dial_code' => '61',
        ],
        [
            'name' => 'Austria',
            'code' => 'AT',
            'dial_code' => '43',
        ],
        [
            'name' => 'Azerbaijan',
            'code' => 'AZ',
            'dial_code' => '994',
        ],
        [
            'name' => 'Burundi',
            'code' => 'BI',
            'dial_code' => '257',
        ],
        [
            'name' => 'Belgium',
            'code' => 'BE',
            'dial_code' => '32',
        ],
        [
            'name' => 'Benin',
            'code' => 'BJ',
            'dial_code' => '229',
        ],
        [
            'name' => 'Burkina Faso',
            'code' => 'BF',
            'dial_code' => '226',
        ],
        [
            'name' => 'Bangladesh',
            'code' => 'BD',
            'dial_code' => '880',
        ],
        [
            'name' => 'Bulgaria',
            'code' => 'BG',
            'dial_code' => '359',
        ],
        [
            'name' => 'Bahrain',
            'code' => 'BH',
            'dial_code' => '973',
        ],
        [
            'name' => 'Bahamas',
            'code' => 'BS',
            'dial_code' => '1242',
        ],
        [
            'name' => 'Bosnia and Herzegovina',
            'code' => 'BA',
            'dial_code' => '387',
        ],
        [
            'name' => 'Saint BarthÃ©lemy',
            'code' => 'BL',
            'dial_code' => '590',
        ],
        [
            'name' => 'Belarus',
            'code' => 'BY',
            'dial_code' => '375',
        ],
        [
            'name' => 'Belize',
            'code' => 'BZ',
            'dial_code' => '501',
        ],
        [
            'name' => 'Bermuda',
            'code' => 'BM',
            'dial_code' => '1441',
        ],
        [
            'name' => 'Bolivia',
            'code' => 'BO',
            'dial_code' => '591',
        ],
        [
            'name' => 'Brazil',
            'code' => 'BR',
            'dial_code' => '55',
        ],
        [
            'name' => 'Barbados',
            'code' => 'BB',
            'dial_code' => '1246',
        ],
        [
            'name' => 'Brunei',
            'code' => 'BN',
            'dial_code' => '673',
        ],
        [
            'name' => 'Bhutan',
            'code' => 'BT',
            'dial_code' => '975',
        ],
        [
            'name' => 'Botswana',
            'code' => 'BW',
            'dial_code' => '267',
        ],
        [
            'name' => 'Central African Republic',
            'code' => 'CF',
            'dial_code' => '236',
        ],
        [
            'name' => 'Canada',
            'code' => 'CA',
            'dial_code' => '1',
        ],
        [
            'name' => 'Cocos (Keeling) Islands',
            'code' => 'CC',
            'dial_code' => '61',
        ],
        [
            'name' => 'Switzerland',
            'code' => 'CH',
            'dial_code' => '41',
        ],
        [
            'name' => 'Chile',
            'code' => 'CL',
            'dial_code' => '56',
        ],
        [
            'name' => 'China',
            'code' => 'CN',
            'dial_code' => '86',
        ],
        [
            'name' => 'Ivory Coast',
            'code' => 'CI',
            'dial_code' => '225',
        ],
        [
            'name' => 'Cameroon',
            'code' => 'CM',
            'dial_code' => '237',
        ],
        [
            'name' => 'DR Congo',
            'code' => 'CD',
            'dial_code' => '243',
        ],
        [
            'name' => 'Republic of the Congo',
            'code' => 'CG',
            'dial_code' => '242',
        ],
        [
            'name' => 'Cook Islands',
            'code' => 'CK',
            'dial_code' => '682',
        ],
        [
            'name' => 'Colombia',
            'code' => 'CO',
            'dial_code' => '57',
        ],
        [
            'name' => 'Comoros',
            'code' => 'KM',
            'dial_code' => '269',
        ],
        [
            'name' => 'Cape Verde',
            'code' => 'CV',
            'dial_code' => '238',
        ],
        [
            'name' => 'Costa Rica',
            'code' => 'CR',
            'dial_code' => '506',
        ],
        [
            'name' => 'Cuba',
            'code' => 'CU',
            'dial_code' => '53',
        ],
        [
            'name' => 'CuraÃ§ao',
            'code' => 'CW',
            'dial_code' => '5999',
        ],
        [
            'name' => 'Christmas Island',
            'code' => 'CX',
            'dial_code' => '61',
        ],
        [
            'name' => 'Cayman Islands',
            'code' => 'KY',
            'dial_code' => '1345',
        ],
        [
            'name' => 'Cyprus',
            'code' => 'CY',
            'dial_code' => '357',
        ],
        [
            'name' => 'Czechia',
            'code' => 'CZ',
            'dial_code' => '420',
        ],
        [
            'name' => 'Germany',
            'code' => 'DE',
            'dial_code' => '49',
        ],
        [
            'name' => 'Djibouti',
            'code' => 'DJ',
            'dial_code' => '253',
        ],
        [
            'name' => 'Dominica',
            'code' => 'DM',
            'dial_code' => '1767',
        ],
        [
            'name' => 'Denmark',
            'code' => 'DK',
            'dial_code' => '45',
        ],
        [
            'name' => 'Dominican Republic',
            'code' => 'DO',
            'dial_code' => '1809',
        ],
        [
            'name' => 'Dominican Republic',
            'code' => 'DO',
            'dial_code' => '1829',
        ],
        [
            'name' => 'Dominican Republic',
            'code' => 'DO',
            'dial_code' => '1849',
        ],
        [
            'name' => 'Algeria',
            'code' => 'DZ',
            'dial_code' => '213',
        ],
        [
            'name' => 'Ecuador',
            'code' => 'EC',
            'dial_code' => '593',
        ],
        [
            'name' => 'Egypt',
            'code' => 'EG',
            'dial_code' => '20',
        ],
        [
            'name' => 'Eritrea',
            'code' => 'ER',
            'dial_code' => '291',
        ],
        [
            'name' => 'Western Sahara',
            'code' => 'EH',
            'dial_code' => '212',
        ],
        [
            'name' => 'Spain',
            'code' => 'ES',
            'dial_code' => '34',
        ],
        [
            'name' => 'Estonia',
            'code' => 'EE',
            'dial_code' => '372',
        ],
        [
            'name' => 'Ethiopia',
            'code' => 'ET',
            'dial_code' => '251',
        ],
        [
            'name' => 'Finland',
            'code' => 'FI',
            'dial_code' => '358',
        ],
        [
            'name' => 'Fiji',
            'code' => 'FJ',
            'dial_code' => '679',
        ],
        [
            'name' => 'Falkland Islands',
            'code' => 'FK',
            'dial_code' => '500',
        ],
        [
            'name' => 'France',
            'code' => 'FR',
            'dial_code' => '33',
        ],
        [
            'name' => 'Faroe Islands',
            'code' => 'FO',
            'dial_code' => '298',
        ],
        [
            'name' => 'Micronesia',
            'code' => 'FM',
            'dial_code' => '691',
        ],
        [
            'name' => 'Gabon',
            'code' => 'GA',
            'dial_code' => '241',
        ],
        [
            'name' => 'United Kingdom',
            'code' => 'GB',
            'dial_code' => '44',
        ],
        [
            'name' => 'Georgia',
            'code' => 'GE',
            'dial_code' => '995',
        ],
        [
            'name' => 'Guernsey',
            'code' => 'GG',
            'dial_code' => '44',
        ],
        [
            'name' => 'Ghana',
            'code' => 'GH',
            'dial_code' => '233',
        ],
        [
            'name' => 'Gibraltar',
            'code' => 'GI',
            'dial_code' => '350',
        ],
        [
            'name' => 'Guinea',
            'code' => 'GN',
            'dial_code' => '224',
        ],
        [
            'name' => 'Guadeloupe',
            'code' => 'GP',
            'dial_code' => '590',
        ],
        [
            'name' => 'Gambia',
            'code' => 'GM',
            'dial_code' => '220',
        ],
        [
            'name' => 'Guinea-Bissau',
            'code' => 'GW',
            'dial_code' => '245',
        ],
        [
            'name' => 'Equatorial Guinea',
            'code' => 'GQ',
            'dial_code' => '240',
        ],
        [
            'name' => 'Greece',
            'code' => 'GR',
            'dial_code' => '30',
        ],
        [
            'name' => 'Grenada',
            'code' => 'GD',
            'dial_code' => '1473',
        ],
        [
            'name' => 'Greenland',
            'code' => 'GL',
            'dial_code' => '299',
        ],
        [
            'name' => 'Guatemala',
            'code' => 'GT',
            'dial_code' => '502',
        ],
        [
            'name' => 'French Guiana',
            'code' => 'GF',
            'dial_code' => '594',
        ],
        [
            'name' => 'Guam',
            'code' => 'GU',
            'dial_code' => '1671',
        ],
        [
            'name' => 'Guyana',
            'code' => 'GY',
            'dial_code' => '592',
        ],
        [
            'name' => 'Hong Kong',
            'code' => 'HK',
            'dial_code' => '852',
        ],
        [
            'name' => 'Honduras',
            'code' => 'HN',
            'dial_code' => '504',
        ],
        [
            'name' => 'Croatia',
            'code' => 'HR',
            'dial_code' => '385',
        ],
        [
            'name' => 'Haiti',
            'code' => 'HT',
            'dial_code' => '509',
        ],
        [
            'name' => 'Hungary',
            'code' => 'HU',
            'dial_code' => '36',
        ],
        [
            'name' => 'Indonesia',
            'code' => 'ID',
            'dial_code' => '62',
        ],
        [
            'name' => 'Isle of Man',
            'code' => 'IM',
            'dial_code' => '44',
        ],
        [
            'name' => 'India',
            'code' => 'IN',
            'dial_code' => '91',
        ],
        [
            'name' => 'British Indian Ocean Territory',
            'code' => 'IO',
            'dial_code' => '246',
        ],
        [
            'name' => 'Ireland',
            'code' => 'IE',
            'dial_code' => '353',
        ],
        [
            'name' => 'Iran',
            'code' => 'IR',
            'dial_code' => '98',
        ],
        [
            'name' => 'Iraq',
            'code' => 'IQ',
            'dial_code' => '964',
        ],
        [
            'name' => 'Iceland',
            'code' => 'IS',
            'dial_code' => '354',
        ],
        [
            'name' => 'Israel',
            'code' => 'IL',
            'dial_code' => '972',
        ],
        [
            'name' => 'Italy',
            'code' => 'IT',
            'dial_code' => '39',
        ],
        [
            'name' => 'Jamaica',
            'code' => 'JM',
            'dial_code' => '1876',
        ],
        [
            'name' => 'Jersey',
            'code' => 'JE',
            'dial_code' => '44',
        ],
        [
            'name' => 'Jordan',
            'code' => 'JO',
            'dial_code' => '962',
        ],
        [
            'name' => 'Japan',
            'code' => 'JP',
            'dial_code' => '81',
        ],
        [
            'name' => 'Kazakhstan',
            'code' => 'KZ',
            'dial_code' => '76',
        ],
        [
            'name' => 'Kazakhstan',
            'code' => 'KZ',
            'dial_code' => '77',
        ],
        [
            'name' => 'Kenya',
            'code' => 'KE',
            'dial_code' => '254',
        ],
        [
            'name' => 'Kyrgyzstan',
            'code' => 'KG',
            'dial_code' => '996',
        ],
        [
            'name' => 'Cambodia',
            'code' => 'KH',
            'dial_code' => '855',
        ],
        [
            'name' => 'Kiribati',
            'code' => 'KI',
            'dial_code' => '686',
        ],
        [
            'name' => 'Saint Kitts and Nevis',
            'code' => 'KN',
            'dial_code' => '1869',
        ],
        [
            'name' => 'South Korea',
            'code' => 'KR',
            'dial_code' => '82',
        ],
        [
            'name' => 'Kosovo',
            'code' => 'XK',
            'dial_code' => '383',
        ],
        [
            'name' => 'Kuwait',
            'code' => 'KW',
            'dial_code' => '965',
        ],
        [
            'name' => 'Laos',
            'code' => 'LA',
            'dial_code' => '856',
        ],
        [
            'name' => 'Lebanon',
            'code' => 'LB',
            'dial_code' => '961',
        ],
        [
            'name' => 'Liberia',
            'code' => 'LR',
            'dial_code' => '231',
        ],
        [
            'name' => 'Libya',
            'code' => 'LY',
            'dial_code' => '218',
        ],
        [
            'name' => 'Saint Lucia',
            'code' => 'LC',
            'dial_code' => '1758',
        ],
        [
            'name' => 'Liechtenstein',
            'code' => 'LI',
            'dial_code' => '423',
        ],
        [
            'name' => 'Sri Lanka',
            'code' => 'LK',
            'dial_code' => '94',
        ],
        [
            'name' => 'Lesotho',
            'code' => 'LS',
            'dial_code' => '266',
        ],
        [
            'name' => 'Lithuania',
            'code' => 'LT',
            'dial_code' => '370',
        ],
        [
            'name' => 'Luxembourg',
            'code' => 'LU',
            'dial_code' => '352',
        ],
        [
            'name' => 'Latvia',
            'code' => 'LV',
            'dial_code' => '371',
        ],
        [
            'name' => 'Macau',
            'code' => 'MO',
            'dial_code' => '853',
        ],
        [
            'name' => 'Saint Martin',
            'code' => 'MF',
            'dial_code' => '590',
        ],
        [
            'name' => 'Morocco',
            'code' => 'MA',
            'dial_code' => '212',
        ],
        [
            'name' => 'Monaco',
            'code' => 'MC',
            'dial_code' => '377',
        ],
        [
            'name' => 'Moldova',
            'code' => 'MD',
            'dial_code' => '373',
        ],
        [
            'name' => 'Madagascar',
            'code' => 'MG',
            'dial_code' => '261',
        ],
        [
            'name' => 'Maldives',
            'code' => 'MV',
            'dial_code' => '960',
        ],
        [
            'name' => 'Mexico',
            'code' => 'MX',
            'dial_code' => '52',
        ],
        [
            'name' => 'Marshall Islands',
            'code' => 'MH',
            'dial_code' => '692',
        ],
        [
            'name' => 'Macedonia',
            'code' => 'MK',
            'dial_code' => '389',
        ],
        [
            'name' => 'Mali',
            'code' => 'ML',
            'dial_code' => '223',
        ],
        [
            'name' => 'Malta',
            'code' => 'MT',
            'dial_code' => '356',
        ],
        [
            'name' => 'Myanmar',
            'code' => 'MM',
            'dial_code' => '95',
        ],
        [
            'name' => 'Montenegro',
            'code' => 'ME',
            'dial_code' => '382',
        ],
        [
            'name' => 'Mongolia',
            'code' => 'MN',
            'dial_code' => '976',
        ],
        [
            'name' => 'Northern Mariana Islands',
            'code' => 'MP',
            'dial_code' => '1670',
        ],
        [
            'name' => 'Mozambique',
            'code' => 'MZ',
            'dial_code' => '258',
        ],
        [
            'name' => 'Mauritania',
            'code' => 'MR',
            'dial_code' => '222',
        ],
        [
            'name' => 'Montserrat',
            'code' => 'MS',
            'dial_code' => '1664',
        ],
        [
            'name' => 'Martinique',
            'code' => 'MQ',
            'dial_code' => '596',
        ],
        [
            'name' => 'Mauritius',
            'code' => 'MU',
            'dial_code' => '230',
        ],
        [
            'name' => 'Malawi',
            'code' => 'MW',
            'dial_code' => '265',
        ],
        [
            'name' => 'Malaysia',
            'code' => 'MY',
            'dial_code' => '60',
        ],
        [
            'name' => 'Mayotte',
            'code' => 'YT',
            'dial_code' => '262',
        ],
        [
            'name' => 'Namibia',
            'code' => 'NA',
            'dial_code' => '264',
        ],
        [
            'name' => 'New Caledonia',
            'code' => 'NC',
            'dial_code' => '687',
        ],
        [
            'name' => 'Niger',
            'code' => 'NE',
            'dial_code' => '227',
        ],
        [
            'name' => 'Norfolk Island',
            'code' => 'NF',
            'dial_code' => '672',
        ],
        [
            'name' => 'Nigeria',
            'code' => 'NG',
            'dial_code' => '234',
        ],
        [
            'name' => 'Nicaragua',
            'code' => 'NI',
            'dial_code' => '505',
        ],
        [
            'name' => 'Niue',
            'code' => 'NU',
            'dial_code' => '683',
        ],
        [
            'name' => 'Netherlands',
            'code' => 'NL',
            'dial_code' => '31',
        ],
        [
            'name' => 'Norway',
            'code' => 'NO',
            'dial_code' => '47',
        ],
        [
            'name' => 'Nepal',
            'code' => 'NP',
            'dial_code' => '977',
        ],
        [
            'name' => 'Nauru',
            'code' => 'NR',
            'dial_code' => '674',
        ],
        [
            'name' => 'New Zealand',
            'code' => 'NZ',
            'dial_code' => '64',
        ],
        [
            'name' => 'Oman',
            'code' => 'OM',
            'dial_code' => '968',
        ],
        [
            'name' => 'Pakistan',
            'code' => 'PK',
            'dial_code' => '92',
        ],
        [
            'name' => 'Panama',
            'code' => 'PA',
            'dial_code' => '507',
        ],
        [
            'name' => 'Pitcairn Islands',
            'code' => 'PN',
            'dial_code' => '64',
        ],
        [
            'name' => 'Peru',
            'code' => 'PE',
            'dial_code' => '51',
        ],
        [
            'name' => 'Philippines',
            'code' => 'PH',
            'dial_code' => '63',
        ],
        [
            'name' => 'Palau',
            'code' => 'PW',
            'dial_code' => '680',
        ],
        [
            'name' => 'Papua New Guinea',
            'code' => 'PG',
            'dial_code' => '675',
        ],
        [
            'name' => 'Poland',
            'code' => 'PL',
            'dial_code' => '48',
        ],
        [
            'name' => 'Puerto Rico',
            'code' => 'PR',
            'dial_code' => '1787',
        ],
        [
            'name' => 'Puerto Rico',
            'code' => 'PR',
            'dial_code' => '1939',
        ],
        [
            'name' => 'North Korea',
            'code' => 'KP',
            'dial_code' => '850',
        ],
        [
            'name' => 'Portugal',
            'code' => 'PT',
            'dial_code' => '351',
        ],
        [
            'name' => 'Paraguay',
            'code' => 'PY',
            'dial_code' => '595',
        ],
        [
            'name' => 'Palestine',
            'code' => 'PS',
            'dial_code' => '970',
        ],
        [
            'name' => 'French Polynesia',
            'code' => 'PF',
            'dial_code' => '689',
        ],
        [
            'name' => 'Qatar',
            'code' => 'QA',
            'dial_code' => '974',
        ],
        [
            'name' => 'RÃ©union',
            'code' => 'RE',
            'dial_code' => '262',
        ],
        [
            'name' => 'Romania',
            'code' => 'RO',
            'dial_code' => '40',
        ],
        [
            'name' => 'Russia',
            'code' => 'RU',
            'dial_code' => '7',
        ],
        [
            'name' => 'Rwanda',
            'code' => 'RW',
            'dial_code' => '250',
        ],
        [
            'name' => 'Saudi Arabia',
            'code' => 'SA',
            'dial_code' => '966',
        ],
        [
            'name' => 'Sudan',
            'code' => 'SD',
            'dial_code' => '249',
        ],
        [
            'name' => 'Senegal',
            'code' => 'SN',
            'dial_code' => '221',
        ],
        [
            'name' => 'Singapore',
            'code' => 'SG',
            'dial_code' => '65',
        ],
        [
            'name' => 'South Georgia',
            'code' => 'GS',
            'dial_code' => '500',
        ],
        [
            'name' => 'Svalbard and Jan Mayen',
            'code' => 'SJ',
            'dial_code' => '4779',
        ],
        [
            'name' => 'Solomon Islands',
            'code' => 'SB',
            'dial_code' => '677',
        ],
        [
            'name' => 'Sierra Leone',
            'code' => 'SL',
            'dial_code' => '232',
        ],
        [
            'name' => 'El Salvador',
            'code' => 'SV',
            'dial_code' => '503',
        ],
        [
            'name' => 'San Marino',
            'code' => 'SM',
            'dial_code' => '378',
        ],
        [
            'name' => 'Somalia',
            'code' => 'SO',
            'dial_code' => '252',
        ],
        [
            'name' => 'Saint Pierre and Miquelon',
            'code' => 'PM',
            'dial_code' => '508',
        ],
        [
            'name' => 'Serbia',
            'code' => 'RS',
            'dial_code' => '381',
        ],
        [
            'name' => 'South Sudan',
            'code' => 'SS',
            'dial_code' => '211',
        ],
        [
            'name' => 'SÃ£o TomÃ© and PrÃ­ncipe',
            'code' => 'ST',
            'dial_code' => '239',
        ],
        [
            'name' => 'Suriname',
            'code' => 'SR',
            'dial_code' => '597',
        ],
        [
            'name' => 'Slovakia',
            'code' => 'SK',
            'dial_code' => '421',
        ],
        [
            'name' => 'Slovenia',
            'code' => 'SI',
            'dial_code' => '386',
        ],
        [
            'name' => 'Sweden',
            'code' => 'SE',
            'dial_code' => '46',
        ],
        [
            'name' => 'Swaziland',
            'code' => 'SZ',
            'dial_code' => '268',
        ],
        [
            'name' => 'Sint Maarten',
            'code' => 'SX',
            'dial_code' => '1721',
        ],
        [
            'name' => 'Seychelles',
            'code' => 'SC',
            'dial_code' => '248',
        ],
        [
            'name' => 'Syria',
            'code' => 'SY',
            'dial_code' => '963',
        ],
        [
            'name' => 'Turks and Caicos Islands',
            'code' => 'TC',
            'dial_code' => '1649',
        ],
        [
            'name' => 'Chad',
            'code' => 'TD',
            'dial_code' => '235',
        ],
        [
            'name' => 'Togo',
            'code' => 'TG',
            'dial_code' => '228',
        ],
        [
            'name' => 'Thailand',
            'code' => 'TH',
            'dial_code' => '66',
        ],
        [
            'name' => 'Tajikistan',
            'code' => 'TJ',
            'dial_code' => '992',
        ],
        [
            'name' => 'Tokelau',
            'code' => 'TK',
            'dial_code' => '690',
        ],
        [
            'name' => 'Turkmenistan',
            'code' => 'TM',
            'dial_code' => '993',
        ],
        [
            'name' => 'Timor-Leste',
            'code' => 'TL',
            'dial_code' => '670',
        ],
        [
            'name' => 'Tonga',
            'code' => 'TO',
            'dial_code' => '676',
        ],
        [
            'name' => 'Trinidad and Tobago',
            'code' => 'TT',
            'dial_code' => '1868',
        ],
        [
            'name' => 'Tunisia',
            'code' => 'TN',
            'dial_code' => '216',
        ],
        [
            'name' => 'Turkey',
            'code' => 'TR',
            'dial_code' => '90',
        ],
        [
            'name' => 'Tuvalu',
            'code' => 'TV',
            'dial_code' => '688',
        ],
        [
            'name' => 'Taiwan',
            'code' => 'TW',
            'dial_code' => '886',
        ],
        [
            'name' => 'Tanzania',
            'code' => 'TZ',
            'dial_code' => '255',
        ],
        [
            'name' => 'Uganda',
            'code' => 'UG',
            'dial_code' => '256',
        ],
        [
            'name' => 'Ukraine',
            'code' => 'UA',
            'dial_code' => '380',
        ],
        [
            'name' => 'Uruguay',
            'code' => 'UY',
            'dial_code' => '598',
        ],
        [
            'name' => 'United States',
            'code' => 'US',
            'dial_code' => '1',
        ],
        [
            'name' => 'Uzbekistan',
            'code' => 'UZ',
            'dial_code' => '998',
        ],
        [
            'name' => 'Vatican City',
            'code' => 'VA',
            'dial_code' => '3906698',
        ],
        [
            'name' => 'Vatican City',
            'code' => 'VA',
            'dial_code' => '379',
        ],
        [
            'name' => 'Saint Vincent and the Grenadines',
            'code' => 'VC',
            'dial_code' => '1784',
        ],
        [
            'name' => 'Venezuela',
            'code' => 'VE',
            'dial_code' => '58',
        ],
        [
            'name' => 'British Virgin Islands',
            'code' => 'VG',
            'dial_code' => '1284',
        ],
        [
            'name' => 'United States Virgin Islands',
            'code' => 'VI',
            'dial_code' => '1340',
        ],
        [
            'name' => 'Vietnam',
            'code' => 'VN',
            'dial_code' => '84',
        ],
        [
            'name' => 'Vanuatu',
            'code' => 'VU',
            'dial_code' => '678',
        ],
        [
            'name' => 'Wallis and Futuna',
            'code' => 'WF',
            'dial_code' => '681',
        ],
        [
            'name' => 'Samoa',
            'code' => 'WS',
            'dial_code' => '685',
        ],
        [
            'name' => 'Yemen',
            'code' => 'YE',
            'dial_code' => '967',
        ],
        [
            'name' => 'South Africa',
            'code' => 'ZA',
            'dial_code' => '27',
        ],
        [
            'name' => 'Zambia',
            'code' => 'ZM',
            'dial_code' => '260',
        ],
        [
            'name' => 'Zimbabwe',
            'code' => 'ZW',
            'dial_code' => '263',
        ],
    ];

    public static function verify_webhook($data, $hmac_header, $salt)
	{
	    $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $salt, true));
	    return hash_equals($hmac_header, $calculated_hmac);
	}

    public static function sign($data, $salt)
	{
	    return base64_encode(hash_hmac('sha256', json_encode($data), $salt, true));
	}
}