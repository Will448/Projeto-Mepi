<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Funcionario;
use App\Models\Equipamento;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. USUÁRIOS ────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@mepi.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $rh = User::create([
            'name'     => 'Gestor RH',
            'email'    => 'rh@mepi.com',
            'password' => Hash::make('password'),
            'role'     => 'rh',
        ]);

        $userFunc1 = User::create([
            'name'     => 'João Silva',
            'email'    => 'joao@mepi.com',
            'password' => Hash::make('password'),
            'role'     => 'funcionario',
        ]);

        $userFunc2 = User::create([
            'name'     => 'Maria Souza',
            'email'    => 'maria@mepi.com',
            'password' => Hash::make('password'),
            'role'     => 'funcionario',
        ]);

        // ── 2. CARGOS ─────────────────────────────────────────────────
        $cargoAnalista = Cargo::create([
            'nome'         => 'Analista de Sistemas',
            'descricao'    => 'Desenvolvimento e manutenção de sistemas',
            'salario_base' => 4500.00,
        ]);

        $cargoTecnico = Cargo::create([
            'nome'         => 'Técnico de Segurança',
            'descricao'    => 'Controle e distribuição de EPIs',
            'salario_base' => 3200.00,
        ]);

        $cargoAssistente = Cargo::create([
            'nome'         => 'Assistente Administrativo',
            'descricao'    => 'Suporte administrativo geral',
            'salario_base' => 2200.00,
        ]);

        // ── 3. FUNCIONÁRIOS ───────────────────────────────────────────
        // data_admissao antiga para já ter período aquisitivo de férias gerado
        Funcionario::create([
            'nome'           => 'João Silva',
            'cpf'            => '111.222.333-44',
            'email'          => 'joao@mepi.com',
            'telefone'       => '(63) 99999-0001',
            'data_nascimento'=> '1990-05-15',
            'data_admissao'  => '2023-01-10',   // +1 ano → tem direito a férias
            'salario'        => 4500.00,
            'status'         => 'ativo',
            'cargo_id'       => $cargoAnalista->id,
            'user_id'        => $userFunc1->id,
        ]);

        Funcionario::create([
            'nome'           => 'Maria Souza',
            'cpf'            => '222.333.444-55',
            'email'          => 'maria@mepi.com',
            'telefone'       => '(63) 99999-0002',
            'data_nascimento'=> '1995-08-22',
            'data_admissao'  => '2022-06-01',
            'salario'        => 3200.00,
            'status'         => 'ativo',
            'cargo_id'       => $cargoTecnico->id,
            'user_id'        => $userFunc2->id,
        ]);

        Funcionario::create([
            'nome'           => 'Carlos Pereira',
            'cpf'            => '333.444.555-66',
            'email'          => 'carlos@empresa.com',
            'telefone'       => '(63) 99999-0003',
            'data_nascimento'=> '1988-03-30',
            'data_admissao'  => '2021-03-15',
            'salario'        => 2200.00,
            'status'         => 'ativo',
            'cargo_id'       => $cargoAssistente->id,
            'user_id'        => null, // sem acesso ao sistema
        ]);

        // ── 4. EQUIPAMENTOS ───────────────────────────────────────────
        Equipamento::create([
            'nome'         => 'Capacete de Segurança',
            'numero_serie' => 'CAP-001',
            'tipo'         => 'EPI',
            'validade'     => '2026-12-31',
            'status'       => 'disponivel',
        ]);

        Equipamento::create([
            'nome'         => 'Óculos de Proteção',
            'numero_serie' => 'OC-002',
            'tipo'         => 'EPI',
            'validade'     => '2026-06-30',
            'status'       => 'disponivel',
        ]);

        Equipamento::create([
            'nome'         => 'Notebook Dell i5',
            'numero_serie' => 'NB-2024-003',
            'tipo'         => 'Eletrônico',
            'validade'     => null,
            'status'       => 'disponivel',
        ]);
    }
}
