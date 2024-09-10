@extends('layout')

@section('title', 'Minhas tarefas')

@section('content')
    <div class="submenu">
        <div class="submenu-left ">
            <h2>Adicionar Tarefa</h2>
        </div>
    </div>
    <!-- Exibir mensagens de erro -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <form class="task-form" action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <fieldset>
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Nome"
                            value="{{ $task->name }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description">{{ $task->description }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="doing" {{ $task->status == 'doing' ? 'selected' : '' }}>Fazendo</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Concluído
                            </option>
                        </select>
                    </div>
                    <div class="col mb-3">
                        <label for="date" class="form-label">Prazo
                        </label>
                        <input type="date" id="due_date" name="due_date" class="form-control"
                            value="{{ (new DateTimeImmutable($task->due_date))->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-secondary" href="{{ route('tasks.index') }}">Voltar</a>
                    </div>
                    <div class="col text-end">
                        <button type="reset" class="btn btn-danger">Limpar</button>
                        <button type="submit" id="create" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>


@endsection
