<?php

include 'conexao.php';

switch ($_REQUEST['form-action']) {

  case 'cadastrar':

    $descricao = $_POST["descricao"];
    $classificacao = $_POST["classificacao"];
    $equipamento_relacionado = $_POST["equipamento_relacionado"];
    $data_cadastro = date("Y-m-d");

    $stt = $conn->prepare("INSERT INTO alarmes (descricao, classificacao, equipamento_relacionado, data_cadastro) VALUES (?, ?, ?, ?)");

    $stt->bind_param("ssss", $descricao, $classificacao, $equipamento_relacionado, $data_cadastro);

    if ($stt->execute()) {

      $stt_equip = $conn->prepare("UPDATE equipamentos SET `ativo` = 1 WHERE `id_equip` = ?");
      $stt_equip->bind_param("i", $equipamento_relacionado);
      $stt_equip->execute();

      print "<script>alert('Alarme cadastrado com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes';</script>";
    } else {
      print "<script>alert('Erro ao cadastrar alarme!')</script>";
    }

    $stt->close();
    $conn->close();
    break;

  case 'editar':

    $id = $_POST["id_alarmes"];
    $descricao = $_POST["descricao"];
    $classificacao = $_POST["classificacao"];
    $equipamento_relacionado = $_POST["equipamento_relacionado"];

    $stt_equip = $conn->prepare("UPDATE alarmes SET `descricao` = ?, `classificacao` = ?, `equipamento_relacionado` = ? WHERE `id_alarmes` = ?");
    $stt_equip->bind_param("sssi", $descricao, $classificacao, $equipamento_relacionado, $id);

    if ($stt_equip->execute()) {
      print "<script>alert('Alarme editado com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes/listar_alarmes.php';</script>";
    } else {
      print "<script>alert('Erro ao editar alarme!')</script>";
    }

    break;

  case 'excluir':

    $id = $_REQUEST['id'];
    $sql = "SELECT * from alarmes WHERE id_alarmes = $id";
    $res = $conn->query($sql)->fetch_assoc();

    if ($res['equipamento_relacionado'] > 0) {
      $equipamento_relacionado = $res['equipamento_relacionado'];
      $stt_equip = $conn->prepare("UPDATE equipamentos SET `ativo` = 0 WHERE `id_equip` = ?");
      $stt_equip->bind_param("i", $equipamento_relacionado);
      $stt_equip->execute();
    }

    $stt = $conn->prepare("DELETE FROM sistema_alarme.alarmes WHERE id_alarmes = ?");

    $stt->bind_param("i", $id);

    if ($stt->execute()) {
      print "<script>alert('Alarme excluído com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes/listar_alarmes.php';</script>";
    } else {
      print "<script>alert('Erro ao excluir alarme!')</script>";
    }

    break;

  case 'ativar':

    $id = $_REQUEST["id"];

    $stt = $conn->prepare("UPDATE alarmes SET `status` = 1 WHERE `id_alarmes` = ?");
    $stt->bind_param("i", $id);

    if ($stt->execute()) {
      print "<script>alert('Alarme ativado com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes/listar_alarmes.php';</script>";
    } else {
      print "<script>alert('Erro ao ativar alarme!')</script>";
    }

    break;

  case 'desativar':

    $id = $_REQUEST["id"];

    $stt = $conn->prepare("UPDATE alarmes SET `status` = 0 WHERE `id_alarmes` = ?");
    $stt->bind_param("i", $id);

    if ($stt->execute()) {
      print "<script>alert('Alarme desativado com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes/listar_alarmes.php';</script>";
    } else {
      print "<script>alert('Erro ao desativar alarme!')</script>";
    }

    break;

  case 'simulardisparo':

    $id = $_REQUEST["id"];

    $id = $_REQUEST['id'];
    $sql = "SELECT * from alarmes WHERE id_alarmes = $id";
    $res = $conn->query($sql)->fetch_assoc();

    $fk_equipamento = $res['equipamento_relacionado'];
    $data_entrada = new DateTime();
    $data_entrada_string = $data_entrada->format('Y-m-d H:i:s');
    $data_saida = clone $data_entrada;
    $data_saida->add(new DateInterval('PT20M'));
    $data_saida_string = $data_saida->format('Y-m-d H:i:s');

    $stt = $conn->prepare("INSERT INTO disparos (fk_alarme, fk_equipamento, data_entrada, data_saida) VALUES (?, ?, ?, ?)");
    $stt->bind_param("iiss", $id, $fk_equipamento, $data_entrada_string, $data_saida_string);

    if ($stt->execute()) {
      print "<script>alert('Simulação feita com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes/listar_alarmes.php';</script>";
    } else {
      print "<script>alert('Erro fazer simulação alarme!')</script>";
    }

    break;
}
