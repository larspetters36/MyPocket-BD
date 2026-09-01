<?php


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyPocket</title>
    <style>
        /* O segredo inicial: esconder o elemento usando CSS */
        .oculto {
            display: none;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div>
        <a href="index.php">MyPocket</a>
        <a href="login.php">login</a>
    </div>
    <div class="forms">
        <form method="POST" action="processa.php">
            <div>
                <label for="pet-select">Tipo de Transação</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="entrada">
                    <label class="form-check-label" for="receita">
                        Entrada
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="saida">
                    <label class="form-check-label" for="receita">
                        Saida
                    </label>
                </div>
                <label>A transação é recorrente?</label>
                <select name="asdf" id="asdfg" onchange="verificarOpcao()">
                    <option value=""></option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
                <div class="mb-3">
                    <label class="form-label">Valor</label>
                    <input
                        type="text"
                        name="valor"
                        class="form-control"
                        placeholder="R$ 0,00">
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <input
                        type="text"
                        name="descricao"
                        class="form-control"
                        placeholder="Ex: Mercado">
                </div>

                <div class="oculto" id="campo-oculto">
                    <label>Tipo de recorrencia:</label>

                    <select name="tipo_recorrencia" id="rec">
                        <option value="">Selecione a duração da recorrencia</option>
                        <option value="dia">Diaria</option>
                        <option value="semana">Semanal</option>
                        <option value="mes">Mensal</option>
                        <option value="ano">Anual</option>
                    </select>

                    <div>
                        <label for="data">Escolha a data de inicio da transação:</label>
                        <input type="date" id="data" name="data" required>
                        <button type="submit">Enviar</button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meses de duração da transação:</label>
                        <input
                            type="text"
                            name="valor"
                            class="form-control"
                            placeholder="0 meses">
                    </div>

                </div>
                <button class="btn btn-primary w-100">
                    Salvar Transação
                </button>
            </div>
        </form>
        <script>
            function verificarOpcao() {
                var select = document.getElementById("asdfg");
                var campoOculto = document.getElementById("campo-oculto");

                // Se a opção selecionada for "outro", mostra o campo. Se não, esconde.
                if (select.value === "sim") {
                    campoOculto.style.display = "block";
                } else {
                    campoOculto.style.display = "none";
                }
            }
        </script>
    </div>
</body>

</html>
