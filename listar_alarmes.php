<?php
include './includes/head.php';
include './includes/conexao.php';

$sql = "SELECT alarmes.*, equipamentos.* FROM alarmes INNER JOIN equipamentos ON alarmes.equipamento_relacionado = equipamentos.id_equip";
$res = $conn->query($sql);

$qtd = $res->num_rows;

$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'id_alarmes';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$results = [];

while ($row = $res->fetch_assoc()) {
  $results[] = $row;
}

usort($results, function ($a, $b) use ($orderBy, $order) {
  return $order === 'ASC' ? $a[$orderBy] <=> $b[$orderBy] : $b[$orderBy] <=> $a[$orderBy];
});

?>

<div class="container">
  <form action="buscar_alarmes.php" method="POST">
      <div class="col-sm-12 d-flex mb-4">
        <input type="text" name="termo" class="form-control" id="termo" placeholder="Buscar Alarme">
        <button type="submit" class="btn btn-primary">Buscar</button>
      </div>
      <div class="col-sm-2">
      </div>
  </form>
</div>

<?php if ($qtd > 0) { ?>
  <div class="container">
    <table class="table table-striped">
      <thead>
        <tr>
          <th><a href="?orderBy=id_alarmes&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Código</a></th>
          <th><a href="?orderBy=descricao&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Descrição</a></th>
          <th><a href="?orderBy=nome&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Equipamento</a></th>
          <th><a href="?orderBy=classificacao&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Classificação</a></th>
          <th><a href="?orderBy=equipamento_relacionado&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Equipamento</a></th>
          <th><a href="?orderBy=status&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Status</a></th>
          <th><a href="?orderBy=data_cadastro&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Data Cadastro</a></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $row) : ?>
          <tr>
            <td><?= $row['id_alarmes'] ?></td>
            <td><?= $row['descricao'] ?></td>
            <td><?= $row['nome'] ?></td>
            <td>
              <?php
              switch ($row['classificacao']) {
                case 1:
                  echo "Urgente";
                  break;
                case 2:
                  echo "Emergente";
                  break;
                case 3:
                  echo "Ordinário";
                  break;
                default:
                  echo "";
                  break;
              }
              ?>
            </td>
            <td><?= $row['equipamento_relacionado'] ?></td>
            <td><?php $row['status'] == 1 ? print "Ativo" : print "Inativo" ?></td>
            <td><?= $row['data_cadastro'] ?></td>
            <td><a href="/alarmes/cadastrar_alarmes.php?form-action=editar&id=<?= $row['id_alarmes'] ?>" }>Editar</a></td>
            <td><a href="includes/alarme.php?form-action=excluir&id=<?= $row['id_alarmes'] ?>" }>Excluir</a></td>
            <td><?php
                if ($row['status'] == 1) {
                  echo '<a href="includes/alarme.php?form-action=desativar&id=' . $row['id_alarmes'] . '"><button type="submit" class="btn btn-sm btn-danger">Desativar</button></a>';
                } else {
                  echo '<a href="includes/alarme.php?form-action=ativar&id=' . $row['id_alarmes'] . '"><button type="submit" class="btn btn-sm btn-success">Ativar</button></a>';
                }
                ?>
            </td>
            <td><a href="includes/alarme.php?form-action=simulardisparo&id=<?= $row['id_alarmes'] ?>" }>Simular</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php } ?>