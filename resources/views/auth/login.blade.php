@extends('layouts.app')

@include('layouts.headerOne')
@section('content')
<div class="container" style="margin-top: 200px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">{{ trans('validation.attributes.login') }}</div>
                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{ trans('validation.attributes.email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">{{ trans('validation.attributes.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group">
                          <div class="col-md-4 control-label">
                                {!! Form::label('EMPRESA', 'Empresa') !!}
                          </div>
                          <div class="col-md-5">
                              {!! Form::select('EMPRESA', App\Empresa::lists('DESCRIPCION', 'ID')->toArray(), null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Empresa', 'id' => 'empresas']); !!}
                          </div>
                        </div>
                        {{--
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>--}}

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('validation.attributes.login') }}
                                </button>
                                <a class="btn btn-link" href="{{ asset('/password/email') }}">
                                    {{ trans('passwords.attributes.forgot') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $.fn.populateSelect = function (values) {
            var options = ''
            $.each(values, function (key, row) {
                options += '<option value = "' + row.ID + '">' + row.DESCRIPCION + '</option>'
            })
            $(this).html(options)
        }
        

        $('#email').blur(function () {
            
            var usuario = $('#email').val();
                       
            vurl = '{{ route('getEmpresas') }}'
            vurl = vurl.replace('%7Bid%7D', usuario);

            $.getJSON(vurl, null, function (values) {
                    $('#empresas').populateSelect(values)
            })




        });
    });
</script>
@endpush
