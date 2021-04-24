<?php

namespace System;

class View
{
    /** 
     * @param string $path
     * @param array $data
     * @throws \ErrorException
     */
    public static function render(string $path, array $data = [])
    {
        // Получаем путь, где лежат все представления
        $fullPath = __DIR__ . '/../Views/' . $path . '.php';

        // Если представление не было найдено, выбрасываем исключение
        if (!file_exists($fullPath)) {
            throw new \ErrorException('view cannot be found');
        }

        // создаются переменные, которые будут доступны в представлении
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }

        include($fullPath);
    }
}
