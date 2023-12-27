<?php
include './includes/head.php';
include './includes/conexao.php';

$termo = $_POST['termo'];
$termo_completo = "%$termo%";

$sql = "SELECT alarmes.*, equipamentos.* FROM alarmes INNER JOIN equipamentos ON alarmes.equipamento_relacionado = equipamentos.id_equip WHERE descricao LIKE '$termo'";
$res = $conn->query($sql);

$qtd = $res->num_rows;

$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'id_alarmes';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$results = [];

while ($row = $res->fetch_assoc()) {
  $results[] = $row;
}

?>

<?php if ($qtd > 0) { ?>
  <div class="container">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Código</th>
          <th>Descrição</th>
          <th>Equipamento</th>
          <th>Classificação</th>
          <th>Equipamento</th>
          <th>Status</th>
          <th>Data Cadastro</th>
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
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php } ?>