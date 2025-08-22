<?php

class User
{
    /**
     * W prawdziwej aplikacji ta metoda łączyłaby się z bazą danych.
     * Dla uproszczenia zwracamy statyczną listę użytkowników.
     */
    public function getAllUsers(): array
    {
        return [
            ['id' => 1, 'name' => 'Jan Kowalski', 'email' => 'jan.kowalski@example.com'],
            ['id' => 2, 'name' => 'Anna Nowak', 'email' => 'anna.nowak@example.com'],
            ['id' => 3, 'name' => 'Piotr Wiśniewski', 'email' => 'piotr.wisniewski@example.com'],
        ];
    }
}