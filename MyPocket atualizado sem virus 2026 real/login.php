<?php


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyPocket</title>

</head>

<body>
    <div>
        <form action="processa_login.php" method="POST">
            <label for="email">Cadastrar conta</label>
            <div>
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="name@example.com" required>
                <label for="password">Senha:</label>
                <input type="password" id="senha" name="senha" required />
                <button type="submit">Ciar conta</button>
            </div>
        </form>
    </div>
</body>

</html>
