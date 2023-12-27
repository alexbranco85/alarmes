<?php
include './includes/head.php';
include './includes/conexao.php';

$sql = "SELECT disparos.*, equipamentos.*, alarmes.* FROM disparos
INNER JOIN equipamentos ON disparos.fk_equipamento = equipamentos.id_equip
INNER JOIN alarmes ON disparos.fk_alarme = alarmes.id_alarmes";

$res = $conn->query($sql);

$qtd = $res->num_rows;

$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'id_disparo';
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
    <h4><strong>Todos os Alarmes</strong></h4>
    <table class="table table-striped">
      <thead>
        <tr>
          <th><a href="?orderBy=id_disparo&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Código</a></th>
          <th><a href="?orderBy=descricao&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Alarme</a></th>
          <th><a href="?orderBy=nome&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Nome Equipamento</a></th>
          <th><a href="?orderBy=data_entrada&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Data Entrada</a></th>
          <th><a href="?orderBy=data_saida&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Data Saída</a></th>
          <th><a href="?orderBy=status&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>">Status Alarme</a></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $row) : ?>
          <tr>
            <td><?= $row['id_disparo'] ?></td>
            <td><?= $row['descricao'] ?></td>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['data_entrada'] ?></td>
            <td><?= $row['data_saida'] ?></td>
            <td><?php $row['status'] == 1 ? print "Ativo" : print "Inativo" ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php } ?>