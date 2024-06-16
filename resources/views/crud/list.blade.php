@extends('auth.layouts')
@section('page-script')
    <script src="{{ asset('assets/js/form-basic-inputs.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <script>
         function confirmDelete(url) {
            if (confirm('Confirm delete?')) {
                var form = document.getElementById('fm');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <br>
                    <br>
                    <div class="table-responsive">
                        @if ($codeData->isEmpty())
                            <table class="table table-sm table-bordered table-striped table-hover">
                                <thead class="table-secondary">
                                    <tr>
                                        @foreach ($listColumns as $column => $columnHeader)
                                            <th scope="col">{{ $columnHeader }}</th>
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
                                            <th scope="col">{{ $columnHeader }}</th>
                                        @endforeach
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($codeData as $item)
                                        <tr>
                                            @foreach ($listColumns as $column => $columnHeader)
                                                @if($column == 'is_completed')
                                                    <td>
                                                        <form action="{{ route('tasks.save', ['code' => $code, 'id' => $item->id]) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="radio" name="is_completed" value="1" {{ $item->is_completed ? 'checked' : '' }} onchange="this.form.submit()"> Yes
                                                            <input type="radio" name="is_completed" value="0" {{ !$item->is_completed ? 'checked' : '' }} onchange="this.form.submit()"> No
                                                        </form>
                                                    </td>
                                                @else
                                                    <td>{{ is_array($item->$column) ? implode(', ', $item->$column) : $item->$column }}</td>
                                                @endif
                                            @endforeach
                                            <td>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('crud.delete', ['code' => $code, 'id' => $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    @foreach ($item->listButtons() as $label => $url)
                                                        @if ($label == 'Delete')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>

                                                        @else
                                                            <a href="{{ $url }}"
                                                                class="btn btn-success btn-sm">{{ $label }}</a>
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
