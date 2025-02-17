<?php declare(strict_types=1);

namespace IamIn\Otusp2025PhpPackage;

class DateInterval
{
    public const PERIOD_DAY = 'day';
    public const PERIOD_MONTH = 'month';
    public const PERIOD_YEAR = 'year';

    public static array $periodTypeAsWord = [
        'D' => self::PERIOD_DAY,
        'M' => self::PERIOD_MONTH,
        'Y' => self::PERIOD_YEAR,
    ];

    /**
     * Создает строку интервала в формате ISO 8601.
     *
     * @param int $periodCount Количество периодов.
     * @param string $periodType Тип периода (day, month, year).
     * @return string Строка интервала в формате ISO 8601.
     * @throws \InvalidArgumentException
     */
    public static function composePeriod(int $periodCount, string $periodType): string
    {
        if ($periodCount < 0) {
            throw new \InvalidArgumentException("Количество периодов должно быть неотрицательным целым числом");
        }

        switch ($periodType) {
            case self::PERIOD_DAY:
                return "P{$periodCount}D";
            case self::PERIOD_MONTH:
                return "P{$periodCount}M";
            case self::PERIOD_YEAR:
                return "P{$periodCount}Y";
            default:
                throw new \InvalidArgumentException("Неверный тип периода");
        }
    }

    /**
     * Преобразует строку интервала в объект DateInterval.
     *
     * @param string $intervalString Строка интервала в формате ISO 8601.
     * @return \DateInterval Объект DateInterval.
     * @throws \InvalidArgumentException
     * @throws \DateMalformedIntervalStringException
     */
    public static function periodToInterval(string $intervalString): \DateInterval
    {
        if (empty($intervalString)) {
            throw new \InvalidArgumentException("Строка интервала не может быть пустой");
        }

        return new \DateInterval($intervalString);
    }

    /**
     * Разбивает строку интервала на количество и тип периода.
     *
     * @param string $intervalString Строка интервала в формате ISO 8601.
     * @return array Массив с количеством и типом периода.
     * @throws \InvalidArgumentException
     * @throws \DateMalformedIntervalStringException
     */
    public static function periodToCountAndPeriodString(string $intervalString): array
    {
        if (empty($intervalString)) {
            throw new \InvalidArgumentException("Строка интервала не может быть пустой");
        }

        $interval = new \DateInterval($intervalString);

        if ($interval->y) {
            return [$interval->y, 'Y'];
        }

        if ($interval->m) {
            return [$interval->m, 'M'];
        }

        if ($interval->d) {
            return [$interval->d, 'D'];
        }

        return [0, 'D'];
    }

    /**
     * Преобразует интервал в читаемую строку на русском языке.
     *
     * @param string $intervalString Строка интервала в формате ISO 8601.
     * @return string Интервал в виде читаемой строки.
     * @throws \InvalidArgumentException
     * @throws \DateMalformedIntervalStringException
     */
    public static function intervalToString(string $intervalString): string
    {
        if (empty($intervalString)) {
            throw new \InvalidArgumentException("Строка интервала не может быть пустой");
        }

        $interval = new \DateInterval($intervalString);

        $fields = [
            'y' => [
                'one' => 'год',
                'three' => 'года',
                'ten' => 'лет',
            ],
            'm' => [
                'one' => 'месяц',
                'three' => 'месяца',
                'ten' => 'месяцев',
            ],
            'd' => [
                'one' => 'день',
                'three' => 'дня',
                'ten' => 'дней',
            ],
        ];

        $stringInterval = '';

        foreach ($fields as $field => $names) {
            if ($interval->$field > 0) {
                $stringInterval .= $interval->$field . ' ' . self::getNumeralSuffix($interval->$field, $names) . ' ';
            }
        }

        return trim($stringInterval);
    }

    /**
     * Возвращает суффикс для числительного в зависимости от количества.
     *
     * @param string|int|float $amount Количество.
     * @param array $names Массив с вариантами написания суффиксов.
     * @return string
     */
    public static function getNumeralSuffix($amount, array $names): string
    {
        // Преобразуем $amount в строку, если это число
        $amount = is_numeric($amount) ? (string)$amount : $amount;

        // Очищаем число от запятых и преобразуем в строку
        $amount = (string)self::cleanFloatFromComma($amount);

        if (!preg_match('/^(\d+)([,\.](\d{1,2}))?$/', $amount, $matches)) {
            return '';
        }

        $number = (int)$matches[1];
        $num = $number % 100;

        if ($num > 19) {
            $num %= 10;
        }

        switch ($num) {
            case 1:
                return $names['one'];
            case 2:
            case 3:
            case 4:
                return $names['three'];
            default:
                return $names['ten'];
        }
    }

    /**
     * Преобразует строку с запятой в десятичное число.
     *
     * @param string $number Строка с числом.
     * @return float Преобразованное число.
     */
    public static function cleanFloatFromComma(string $number): float
    {
        return (float)str_replace(',', '.', $number);
    }
}