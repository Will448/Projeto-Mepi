# ⚡ MEPI — Monitoramento Inteligente de Pessoas e Equipamentos


**Sistema web para gestão de Recursos Humanos e controle de EPIs**

---

## 📚 Informações do Projeto

Projeto desenvolvido para a disciplina de **Programação Web II**, ministrada pela professora **Amanda Spader**, no curso de **Sistemas de Informação** do **IFPR – Campus Palmas**.

| | |
|---|---|
| **Aluno** | Willian de Vicentin Bongiovanni |
| **Período** | 7º período — 2026 |
| **Disciplina** | Programação Web II (Optativa) |
| **Professor(a)** | Prof.ª Amanda Spader |

> Este repositório está estruturado por pastas — é possível navegar pelas atividades realizadas em aula e também pelo projeto final.

---

## 🎯 Objetivo

O **MEPI** tem como objetivo centralizar e facilitar o gerenciamento de informações dentro de empresas, resolvendo a dificuldade de controlar dados de funcionários e equipamentos que muitas vezes ficam espalhados em planilhas ou processos manuais.

O sistema integra em uma única plataforma web:

- Gestão completa de **funcionários e cargos**
- Controle de **férias** seguindo as regras da CLT
- **Folha de pagamento** com cálculos reais de INSS e IRRF
- Cadastro e rastreamento de **equipamentos (EPIs)**
- Fluxo de **reserva e entrega** de equipamentos


---

## 🚀 Funcionalidades

### 👤 Módulo de Funcionários

### 🏢 Módulo de Cargos

### 🏖️ Módulo de Férias

### 💰 Módulo de Folha de Pagamento

### 🦺 Módulo de Equipamentos (EPIs)

### 🔄 Módulo de Entregas

### 🔖 Reservas de Equipamento

### 📰 Painel de Notícias

---

## 🧩 Tecnologias Utilizadas

| Camada | Tecnologia |
|---|---|
| **Backend** | PHP 8.2+ · Laravel 11 |
| **Frontend** | Blade Templates · Bootstrap 5.3 · Bootstrap Icons |
| **JavaScript** | Vanilla JS — simulador AJAX, máscaras, campos dinâmicos |
| **Banco de dados** | MySQL 8.0 |
| **Tipografia** | Google Fonts — Syne + DM Sans |
| **API externa** | GNews API |
| **Ambiente local** | Laragon |

---

## 🏗️ Arquitetura MVC

O sistema foi desenvolvido seguindo o padrão **MVC (Model-View-Controller)** do Laravel:

- **Model** — Eloquent ORM com relacionamentos, casts e accessors
- **Controller** — regras de negócio e controle de fluxo por módulo
- **View** — Blade templates organizados por módulo com layouts reutilizáveis

---

## ⚙️ Instalação

### Pré-requisitos
- PHP 8.2+
- Composer
- MySQL 8.0+
- Laragon ou XAMPP

### Passo a passo

```bash
# 1. Clonar o repositório
git clone https://github.com/seu-usuario/mepi.git
cd mepi

# 2. Instalar dependências
composer install

# 3. Configurar o ambiente
cp .env.example .env
php artisan key:generate

# 4. Configurar o .env com os dados do banco e rodar as migrations
php artisan migrate

# 5. Popular com dados iniciais
php artisan db:seed

# 6. Iniciar o servidor
php artisan serve
```

---

## 👤 Perfis de Acesso

| Perfil | Rota base | Permissões |
|---|---|---|
| **Admin** | `/admin/*` | Acesso total, incluindo gerenciar usuários |
| **RH** | `/rh/*` | Gestão de pessoas, folha, EPIs e reservas |
| **Funcionário** | `/funcionario/*` | Apenas próprios dados, férias, holerite e EPIs |

O redirecionamento após o login é automático conforme o perfil do usuário.

---

## 🌐 API Externa

O MEPI integra a **[GNews API](https://gnews.io)** para exibir notícias nos dashboards de Admin e RH.

---


  MEPI © 2026 — Projeto Final Programação Web II · IFPR Campus Palmas
