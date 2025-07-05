<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar contatos de exemplo para demonstrar a funcionalidade de busca
        $contacts = [
            [
                'name' => 'João Silva',
                'email' => 'joao.silva@email.com',
                'phone' => '11999887766',
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@email.com',
                'phone' => '11888776655',
            ],
            [
                'name' => 'Pedro Oliveira',
                'email' => 'pedro.oliveira@email.com',
                'phone' => '11777665544',
            ],
            [
                'name' => 'Ana Costa',
                'email' => 'ana.costa@email.com',
                'phone' => '11666554433',
            ],
            [
                'name' => 'Carlos Ferreira',
                'email' => 'carlos.ferreira@email.com',
                'phone' => '11555443322',
            ],
            [
                'name' => 'Lucia Pereira',
                'email' => 'lucia.pereira@email.com',
                'phone' => '11444332211',
            ],
            [
                'name' => 'Roberto Almeida',
                'email' => 'roberto.almeida@email.com',
                'phone' => '11333221100',
            ],
            [
                'name' => 'Fernanda Lima',
                'email' => 'fernanda.lima@email.com',
                'phone' => '11222110099',
            ],
            [
                'name' => 'Bruno Rocha',
                'email' => 'bruno.rocha@email.com',
                'phone' => '11111009988',
            ],
            [
                'name' => 'Juliana Martins',
                'email' => 'juliana.martins@email.com',
                'phone' => '11000998877',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::updateOrCreate(
                ['email' => $contact['email']], // Chave única
                $contact // Dados para criar/atualizar
            );
        }
    }
}
