@extends('auth.layouts')
@section('page-script')
    <script src="{{ asset('assets/js/form-basic-inputs.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <div class="card">
        <div class="card-header">{{ ucfirst($code) }} Page</div>
        <div class="card-body">
          <div clone-row>
          <form
                action="{{ isset($item) ? route($code . '.save', ['code' => $code, 'id' => $item->id]) : route($code . '.save', ['code' => $code]) }}"
                method="post" enctype="multipart/form-data">
                @csrf
                @if (isset($item))
                    @method('PATCH')
                @endif
                @foreach ($listColumns as $column => $columnInfo)
                    <label for="{{ $column }}">{{ $columnInfo['display_name'] }}:</label>
                    @if ($columnInfo['data_type'] === 'string')
                        <input type="text" name="{{ $column }}" id="{{ $column }}"
                            value="{{ old($column) }}" class="form-control" maxlength="{{ $columnInfo['length'] }}">
                    @elseif ($columnInfo['data_type'] === 'date')
                        <input type="date" name="{{ $column }}" id="{{ $column }}"
                            value="{{ old($column) }}" class="form-control">
                        <span id="error" style="color:red;"></span>
                    @endif
                    @error($column)
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <br>
                @endforeach
                <input type="submit" value="{{ isset($item) ? 'Update' : 'Save' }}" class="btn btn-success">
                <a href="{{ url('/crud/' . $code . '/list') }}" class="btn btn-success">Back</a>
                <br>

            </form>
          </div>



        </div>
    </div>

@stop
