<?php

include 'conexao.php';

switch ($_REQUEST['form-action']) {

  case 'cadastrar':

    $nome = $_POST["nome"];
    $tipo = $_POST["tipo"];
    $serial = $_POST["serial"];
    $data_cadastro = date("Y-m-d");

    $stt = $conn->prepare("INSERT INTO equipamentos (nome, tipo, serial, data_cadastro) VALUES (?, ?, ?, ?)");

    $stt->bind_param("ssss", $nome, $tipo, $serial, $data_cadastro);

    if ($stt->execute()) {
      print "<script>alert('Equipamento cadastrado com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes';</script>";
    } else {
      print "<script>alert('Erro ao cadastrar equipamento!')</script>";
    }

    $stt->close();
    $conn->close();
    break;

  case 'editar':

    $id = $_POST["id_equip"];
    $nome = $_POST["nome"];
    $tipo = $_POST["tipo"];
    $serial = $_POST["serial"];

    $stt_equip = $conn->prepare("UPDATE equipamentos SET `nome` = ?, `tipo` = ?, `serial` = ? WHERE `id_equip` = ?");
    $stt_equip->bind_param("sssi", $nome, $tipo, $serial, $id);

    if ($stt_equip->execute()) {
      print "<script>alert('Equipamento editado com sucesso!')</script>";
      echo "<script>window.location.href='/alarmes/listar_equipamentos.php';</script>";
    } else {
      print "<script>alert('Erro ao editar equipamento!')</script>";
    }

    break;

  case 'excluir':

    $id = $_REQUEST['id'];
    $sql = "SELECT * from alarmes WHERE equipamento_relacionado = $id";
    $res = $conn->query($sql)->fetch_assoc();

    if (isset($res['equipamento_relacionado'])) {
      print '<script> alert("Não é possível excluir o equipamento porque ele está relacionado a um alarme. Edite o alarme antes de excluir o equipamento."); </script>';
      echo "<script>window.location.href='/alarmes/';</script>";
    } else {

      $stt_equip = $conn->prepare("DELETE FROM sistema_alarme.equipamentos WHERE id_equip = ?");

      $stt_equip->bind_param("i", $id);

      if ($stt_equip->execute()) {
        print "<script>alert('Equipamento excluído com sucesso!')</script>";
        echo "<script>window.location.href='/alarmes/listar_equipamentos.php';</script>";
      } else {
        print "<script>alert('Erro ao excluir equipamento!')</script>";
      }
    }

    break;
}
