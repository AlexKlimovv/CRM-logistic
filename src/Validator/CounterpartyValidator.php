<?php
class CounterpartyValidator
{
    private function normalize(array $data): array
    {
        $data = array_map('trim', $data);

        $data['edrpou'] = preg_replace('/\D+/', '', $data['edrpou'] ?? '');
        $data['inn'] = preg_replace('/\D+/', '', $data['inn'] ?? '');
        $data['vat_certificate'] = preg_replace('/\D+/', '', $data['vat_certificate'] ?? '');

        return $data;
    }
    public function validate(array $input): array
    {
        $data = $this->normalize($input);
        $errors = [];


        if ($data['name'] === '') {
            $errors['name'] = 'Название компании обязательно';
        }

        if ($data['edrpou'] === '') {
            $errors['edrpou'] = 'ЕДРПОУ обязателен';
        } elseif (strlen($data['edrpou']) < 8) {
            $errors['edrpou'] = 'ЕДРПОУ не менее 8 цифр';
        }

        if ($data['inn'] === '') {
            $errors['inn'] = 'ИНН обязателен';
        }

        if ($data['vat_certificate'] === '') {
            $errors['vat_certificate'] = 'Св-во налогоплательщика обязательно';
        }

        if ($data['legal_address'] === '') {
            $errors['legal_address'] = 'Юр. адрес обязателен';
        }

        if ($data['postal_address'] === '') {
            $errors['postal_address'] = 'Почтовый адрес обязателен';
        }
        if (!ctype_digit((string)$data['city_id'])) {
            $errors['city_id'] = 'Город не выбран';
        }

        return [$data, $errors];
    }
}