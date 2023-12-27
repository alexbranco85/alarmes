<?php

include './includes/head.php';
include './includes/conexao.php';

$sql = "SELECT * from equipamentos WHERE ativo = 0";
$res = $conn->query($sql);

$tiposEquipamento = [];

if ($res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    $tiposEquipamento[] = $row;
  }
} else {
  echo '<div class="container d-flex align-items-center justify-content-center"><div class="alert alert-danger" role="alert">Não existem equipamentos disponíveis.</div></div>';
}

$action = '';
$res = '';

if (isset($_REQUEST['form-action'])) {
  $action = $_REQUEST['form-action'];
  $id = $_REQUEST['id'];
  $sql = "SELECT * from alarmes WHERE id_alarmes = $id";
  $res = $conn->query($sql)->fetch_assoc();

  $sql_equip = "SELECT * FROM equipamentos WHERE id_equip = " . $res['equipamento_relacionado'];
  $res_equip = $conn->query($sql_equip)->fetch_assoc();

  $tiposEquipamento[] = $res_equip;
}

?>

<div class="container d-flex align-items-center justify-content-center">
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 bg-gray bg-teste p-4">
    <div class="row">
      <p class="fw-bold">Cadastrar Alarmes</p>
    </div>
    <form action="includes/alarme.php" method="POST">
      <input type="hidden" name="form-action" value=<?php $res ? print "editar" : print "cadastrar" ?> />
      <input type="hidden" name="id_alarmes" value=<?php $res && print $res['id_alarmes'] ?> />

      <div class="row mb-3">
        <label for="descricao">Descrição</label>
        <div class="col-sm-12">
          <textarea class="form-control" name="descricao" id="descricao" style="height: 100px"><?php echo $res ? $res['descricao'] : ''; ?></textarea>
        </div>
      </div>

      <div class="row mb-3">
        <label for="classificacao" class="col-sm-12 col-form-label">Classificação</label>
        <div class="col-sm-12">
          <select class="form-select" name="classificacao" id="classificacao">
            <option value="1" selected="<?php isset($res['classificacao']) && $res['classificacao'] == 1 ? true : false ?>">Urgente</option>
            <option value="2" selected="<?php isset($res['classificacao']) && $res['classificacao'] == 2 ? true : false ?>">Emergente</option>
            <option value="3" selected="<?php isset($res['classificacao']) && $res['classificacao'] == 3 ? true : false ?>">Ordinário</option>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <label for="equipamento_relacionado" class="col-sm-12 col-form-label">Equipamento relacionado</label>
        <div class="col-sm-12">
          <select class="form-select" name="equipamento_relacionado">
            <?php foreach ($tiposEquipamento as $tipo) : ?>
              <option selected="<?php isset($res['equipamento_relacionado']) && $res['equipamento_relacionado'] == $tipo['id_equip'] ? true : false ?>" value="<?= $tipo['id_equip'] ?>"><?= $tipo['nome'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary"><?php $res ? print "Editar" : print "Cadastrar" ?></button>
        </div>
      </div>
    </form>
  </div>
</div>