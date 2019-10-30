<?php

require_once "funcionario.PDO.php";
$bd = new Table_Funcionario();

class Funcionario
{
    private $nome;
    private $login;
    private $senha;
    private $acesso;

    // ------------------ Getters ---------------
    function getNome()
    {
        return $this->nome;
    }

    function getLogin()
    {
        return $this->login;
    }

    function getSenha()
    {
        return $this->senha;
    }

    function getAcesso()
    {
        return $this->acesso;
    }

    // --------------------- Setters ---------------
    function setNome($n)
    {
        $this->nome = $n;
    }

    function setLogin($l)
    {
        $this->login = $l;
    }

    function setSenha($s)
    {
        $this->senha = $s;
    }

    function setAcesso($a)
    {
        $this->acesso = $a;
    }
    // --------------------------------------------------

// Insere os dados do cliente no objeto
    function dadosFuncionario($nome, $login, $senha, $acesso = 'CM')
    {
        $this->setNome($nome);
        $this->setLogin($login);
        $this->setSenha($senha);
        $this->setAcesso($acesso);
    }

// Faz o login no sistema caso o login e a senha estejam corretas e faz
// a diferenciação de nivel de acesso
    function logar($login, $senha)
    {
        require_once "../../model/pdo.Banco.class.php";
        global $bd;
        $dados = $bd->login($login, $senha);
        if ($dados == 1) {
            $dados = $bd->selectFuncionario($login, $senha);
            print_r($dados);
            session_start();
            $_SESSION['login'] = $login;
            $_SESSION['senha'] = $senha;
            $_SESSION['acesso'] = $dados[4];
            if ($_SESSION['acesso'] == "CM") {
                header("location: ../../view/funcionario/funcionario.Usuario.php");
            } else if ($_SESSION['acesso'] == "US") {
                header("location: ../../view/funcionario/funcionario.Gerente.php");
            } else {
                session_destroy();
                header("location: ../../view/funcionario/funcionario.Main.php");
            }
        } else {
            session_destroy();
            header("location: ../../view/login.php");
        }
    }

// Testa se o login foi realizado, evita o acesso por URL
    function log_teste()
    {
        global $bd;

        if (!isset($_SESSION)) {
            header("../../view/logar.php");
        } else {

            if (!isset($_SESSION['login']) && !isset($_SESSION['senha'])) {
                header("../../view/logar.php");
            } else {
                $dados = $bd->selectFuncionario($_SESSION['login'], $_SESSION['senha']);
                if ($dados[2] != $_SESSION['login'] || $dados[3] != $_SESSION['senha']) {
                    session_destroy();
                    header("../../view/logar.php");
                }
            }
        }
    }

// Salva os dados de um Funcionario através do objeto
    function salvarFuncionario($nome, $login, $senha, $acesso = 'CM')
    {
        global $bd;
        $this->dadosFuncionario($nome, $login, $senha, $acesso);
        $bd->insertFuncionario($this);
    }
}