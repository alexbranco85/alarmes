<?php
include './includes/head.php';
include './includes/conexao.php';

$sql = "SELECT * from equipamentos";
$res = $conn->query($sql);

$qtd = $res->num_rows;

$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'id_equip';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$results = [];

while ($row = $res->fetch_assoc()) {
  $results[] = $row;
}

usort($results, function ($a, $b) use ($orderBy, $order) {
  return $order === 'ASC' ? $a[$orderBy] <=> $b[$orderBy] : $b[$orderBy] <=> $a[$orderBy];
});

?>

<?php if ($qtd > 0) { ?>
  <div class="container">
    <table class="table table-striped">
      <thead>
        <tr>
          <th><a href="?orderBy=id_equip&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Código</a></th>
          <th><a href="?orderBy=nome&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Nome</a></th>
          <th><a href="?orderBy=serial&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Serial</a></th>
          <th><a href="?orderBy=tipo&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Tipo</a></th>
          <th><a href="?orderBy=data_cadastro&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Data Cadastro</a></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $row) : ?>
          <tr>
            <td><?= $row['id_equip'] ?></td>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['serial'] ?></td>
            <td>
              <?php
              switch ($row['tipo']) {
                case 1:
                  echo "Tensão";
                  break;
                case 2:
                  echo "Corrente";
                  break;
                case 3:
                  echo "Óleo";
                  break;
                default:
                  echo "";
                  break;
              }
              ?>
            </td>
            <td><?= $row['data_cadastro'] ?></td>
            <td><a href="/alarmes/?form-action=editar&id=<?= $row['id_equip'] ?>"}>Editar</a></td>
            <td><a href="includes/equipamento.php?form-action=excluir&id=<?= $row['id_equip'] ?>"}>Excluir</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php } ?>