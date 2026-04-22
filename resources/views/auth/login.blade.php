<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <div class="col-md-4 offset-md-4">

        <h3 class="text-center mb-4">Login MEPI</h3>

       <form method="POST" action="{{ route('login') }}">
    @csrf

        <input type="email" name="email" class="form-control mb-2" placeholder="Email">

        <input type="password" name="password" class="form-control mb-3" placeholder="Senha">

         <button class="btn btn-primary w-100">Entrar</button>
    </form>

    </div>
</div>

</body>
</html>