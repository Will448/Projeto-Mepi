@extends('layout.app')

@section('title', 'Detalhes do Funcionário')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">👤 Detalhes do Funcionário</h2>
            <p class="text-muted mb-0">
                Informações completas do funcionário cadastrado.
            </p>
        </div>

    </div>

    <div class="card border-0 shadow rounded-4 overflow-hidden">

        <div class=" text-dark px-4 py-3">
            <h4 class="mb-0">{{ $funcionario->nome }}</h4>
        </div>

        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">Número de registro</small>
                        <strong>#{{ $funcionario->id }}</strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">CPF</small>
                        <strong>{{ $funcionario->cpf }}</strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">E-mail</small>
                        <strong>{{ $funcionario->email }}</strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">Telefone</small>
                        <strong>{{ $funcionario->telefone ?? 'Não informado' }}</strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">Cargo</small>
                        <strong>
                            {{ $funcionario->cargo->nome ?? 'Sem cargo' }}
                        </strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">Salário</small>
                        <strong>
                            R$ {{ number_format($funcionario->salario, 2, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">
                            Data de Admissão
                        </small>

                        <strong>
                            {{ \Carbon\Carbon::parse($funcionario->data_admissao)->format('d/m/Y') }}
                        </strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <small class="text-muted d-block mb-1">Status</small>

                        @if($funcionario->status == 'ativo')
                            <span class="badge bg-success px-3 py-2">
                                Ativo
                            </span>

                        @elseif($funcionario->status == 'afastado')
                            <span class="badge bg-warning text-dark px-3 py-2">
                                Afastado
                            </span>

                        @else
                            <span class="badge bg-danger px-3 py-2">
                                Inativo
                            </span>
                        @endif

                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer bg-white border-0 p-4">
            <div class="d-flex gap-2">

         
                <a href="{{ route('admin.funcionarios.index') }}"
                   class="btn btn-outline-dark">
                    Voltar para lista
                </a>

            </div>
        </div>

    </div>

</div>

@endsection