<?php
include './includes/head.php';
include './includes/conexao.php';

$action = '';
$res = '';

if(isset($_REQUEST['form-action'])) {
  $action = $_REQUEST['form-action'];
  $id = $_REQUEST['id'];
  $sql = "SELECT * from equipamentos WHERE id_equip = $id";
  $res = $conn->query($sql)->fetch_assoc();
}
?>

<div class="container d-flex align-items-center justify-content-center">
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 bg-gray bg-teste p-4">
    <div class="row">
      <p class="fw-bold">Cadastrar Equipamentos</p>
    </div>
    <form action="includes/equipamento.php" method="POST">
      <input type="hidden" name="form-action" value=<?php $res ? print "editar" : print "cadastrar" ?> />
      <input type="hidden" name="id_equip" value=<?php $res && print $res['id_equip'] ?> />
      <div class="row mb-3">
        <label for="nome" class="col-sm-12 col-form-label">Nome do equipamento</label>
        <div class="col-sm-12">
          <input type="text" name="nome" class="form-control" id="nome" value="<?php $res && print $res['nome'] ?>">
        </div>
      </div>
      <div class="row mb-3">
        <label for="serial" class="col-sm-12 col-form-label">Número de Série</label>
        <div class="col-sm-12">
          <input type="text" name="serial" class="form-control" id="serial" value="<?php $res && print $res['serial'] ?>">
        </div>
      </div>
      <div class="row mb-3">
        <label for="tipo" class="col-sm-12 col-form-label">Tipo</label>
        <div class="col-sm-12">
          <select class="form-select" name="tipo" aria-label="Default select">
            <option value="1" selected="<?php isset($res['tipo']) && $res['tipo'] == 1 ? true : false ?>">Tensão</option>
            <option value="2" selected="<?php isset($res['tipo']) && $res['tipo'] == 2 ? true : false ?>">Corrente</option>
            <option value="3" selected="<?php isset($res['tipo']) && $res['tipo'] == 3 ? true : false ?>">Óleo</option>
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