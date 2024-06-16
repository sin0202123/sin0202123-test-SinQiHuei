@extends('auth.layouts')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><strong>Dashboard</strong></div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
                @else
                <div class="alert alert-success">
                    Hello, Admin, You are logged in!
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <br>
                        <br>
                        <div class="table-responsive">
                            @if ($codeData->isEmpty())
                                <table class="table table-sm table-bordered table-striped table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            @foreach ($listColumns as $column => $columnHeader)
                                                <th scope="col">@sortablelink($column, $columnHeader)</th>
                                            @endforeach
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="{{ count($listColumns) + 1 }}">
                                                <div class="alert alert-info">No records found</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="table table-sm table-bordered table-striped table-hover">
                                    <thead class="table-secondary">
                                        <tr>
                                            @foreach ($listColumns as $column => $columnHeader)
                                                <th scope="col">@sortablelink($column, $columnHeader)</th>
                                            @endforeach
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($codeData as $item)
                                            <tr>
                                                @foreach ($listColumns as $column => $columnHeader)
                                                    @if ($column === 'countryName')
                                                        <td>{{ $item->country_name ?? 'No Country' }}</td>
                                                    @elseif($column === 'priceTiers')
                                                        <td>
                                                            @foreach ($item->priceTiers as $priceTier)
                                                                {{ $priceTier->id }}
                                                            @endforeach
                                                        </td>
                                                    @elseif($column === 'image')
                                                        <td><img src="{{ asset('img/avatars/' . $item->image) }}"
                                                                alt="Image" style="height: 70px; width: 70px;"></td>
                                                    @else
                                                        <td>{{ is_array($item->$column) ? implode(', ', $item->$column) : $item->$column }}</td>
                                                    @endif
                                                @endforeach
                                                <td>
                                                    <form method="POST" id="fm" name="fm" accept-charset="UTF-8"
                                                        style="display:inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        @foreach ($item->listButtons() as $label => $url)
                                                            @if ($label == 'Delete')
                                                                <a onclick="confirmDelete('{{ route('crud.delete', ['code' => $code, 'id' => $item->id]) }}')"
                                                                    class="btn btn-danger btn-sm text-white">
                                                                    {{ $label }}
                                                                </a>
                                                            @else
                                                                <a href="{{ $url }}" class="btn btn-success btn-sm">{{ $label }}</a>
                                                            @endif
                                                        @endforeach
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $codeData->links('vendor.pagination.bootstrap-4') }}
                            @endif
                        </div>
                        <br>
                        <div style="text-align:center;">
                            <a href="{{ url('/crud/' . $code . '/create') }}" class="btn btn-success btn-sm" title="Add New Student">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
