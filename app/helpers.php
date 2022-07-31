<?php

if (!function_exists('translit')) {
    function translit(string $value, string $delimiter = '-'): string
    {
        $converter = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ];

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', $delimiter, $value);
        $value = mb_ereg_replace('[-]+', $delimiter, $value);
        $value = trim($value, $delimiter);

        return $value;
    }
}

if (!function_exists('makeCode')) {
    function makeCode(string $name, string $delimiter = '-', int $maxLength = 10): string
    {
        $newCode = translit((empty($name) ? 'no-name' : $name), $delimiter);
        if (mb_strlen($newCode) > $maxLength) $newCode = mb_substr($newCode, 0, $maxLength);

        return $newCode;
    }
}

if (!function_exists('makeUniqueCode')) {
    function makeUniqueCode(array $existCodes, string $name, string $delimiter = '-', int $maxTextLength = 10): string
    {
        $newCode = makeCode($name, $delimiter, $maxTextLength);

        if (!in_array($newCode, $existCodes)) {
            $newCodeName = $newCode;
        } else {
            $kk = 2;
            do {
                $newCodeName = $newCode . $delimiter . $kk++;
            } while (in_array($newCodeName, $existCodes));
        }

        return $newCodeName;
    }
}
