<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>QQuality Test</title>
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h3>Cash Machine</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form id="cashForm" method="POST" action="/">
                    @csrf
                        <div class="form-group">
                            <label for="bills">Notas Disponíveis</label>
                            <input type="text" name="bills" value="{{ isset($data['bills']) ? $data['bills'] : '' }}" class="form-control" aria-describedby="billHelp" placeholder="Ex: 1,30,50" required>
                            <small id="billHelp" class="form-text text-muted">Informe as notas disponíveis separando-as por vírgula!</small>
                        </div>
                        <div class="form-group">
                            <label for="withdrawal">Valor Desejado</label>
                            <input type="number" min="0" name="withdrawal" value="{{ isset($data['withdrawal']) ? $data['withdrawal'] : '' }}" class="form-control"  placeholder="Ex: 134" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Sacar</button>
                    </form>
                </div>
            </div>
            @if(isset($data['results']))
            <div class="row mt-4">
                <div class="col-sm">
                    @if(!empty($data['results']['errors']))
                    <div class="alert alert-danger" role="alert">
                        {{ $data['results']['errors'] }}
                    </div>
                    @else
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Resultados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['results']['totalBills'] AS $bill => $amount)
                            <tr>
                                <th scope="row">{{ $amount }} nota(s) de {{ $bill }}</th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <script src="{{ asset('jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="{{ asset('jquery/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('jquery/jquery.validate.ptbr.js') }}"></script>
        <script>
            $(function(){
                $.validator.addMethod('bills', function(value) {
                    return /^[0-9\,]*$/.test(value);
                }, 'Por favor, insira um valor válido!');
                $("#cashForm").validate({
                    rules: {
                        bills: "required bills",
                        withdrawal: "required number"
                    }
                });
            });
        </script>
    </body>
</html>
