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

$contagem_ids = [];
foreach ($results as $registro) {
  $id_alarmes = $registro['id_alarmes'];
  if (isset($contagem_ids[$id_alarmes])) {
    $contagem_ids[$id_alarmes]++;
  } else {
    $contagem_ids[$id_alarmes] = 1;
  }
}

arsort($contagem_ids);
$ids_mais_frequentes = array_slice(array_keys($contagem_ids), 0, 3);

$nomes_mais_frequentes = [];

foreach ($ids_mais_frequentes as $id) {
  foreach ($results as $registro) {
    if ($registro['id_alarmes'] == $id) {
      $nomes_mais_frequentes[] = $registro['descricao'];
      break;
    }
  }
}

?>


<?php if ($qtd > 0) { ?>
  <div class="container mb-4">
    <div>
      <h4><strong>Alarmes mais Atuantes</strong></h4>
      <?php foreach ($nomes_mais_frequentes as $row) : ?>
        <span><strong><?= $row ?></strong></span><br />
      <?php endforeach; ?>
    </div>
  </div>
<?php } ?>