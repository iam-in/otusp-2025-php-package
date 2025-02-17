# otusp-2025-php-package
Homework public composer package

## Требования

- php >= 7.4

## Установка пакета

`composer require iam-in/otusp-2025-php-package`

## Использование

```php
// Создание интервала
$intervalString = DateInterval::composePeriod(2, DateInterval::PERIOD_MONTH);
echo $intervalString; // Output: P2M
```
```php
// Преобразование интервала в объект DateInterval
$intervalObject = DateInterval::periodToInterval('P2M');
print_r($intervalObject);
```
```php
// Разбиение интервала на количество и тип периода
list($count, $period) = DateInterval::periodToCountAndPeriodString('P2M');
echo "Count: $count, Period: $period"; // Output: Count: 2, Period: M
```
```php
// Преобразование интервала в читаемую строку
$readableInterval = DateInterval::intervalToString('P2M');
echo $readableInterval; // Output: 2 месяца
```
