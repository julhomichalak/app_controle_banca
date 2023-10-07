<?php

class database
{
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "app_controle_banca";
    private $user = "postgres";
    private $password = "123";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};user={$this->user};password={$this->password}");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        }
    }

    public function executarConsulta($sql)
    {
        try {
            $stmt = $this->conn->query($sql);
            return $stmt;
        } catch (PDOException $e) {
            echo "Erro ao executar consulta: " . $e->getMessage();
            return false;
        }
    }

    public function getApostas()
    {
        try {
            $query = "SELECT * FROM apostas";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getCompeticoes()
    {
        try {
            $query = "SELECT * FROM competicoes";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getInfosBanca()
    {
        try {
            $query = "SELECT * FROM infos_banca";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getApostasByStatus($status)
    {
        try {
            $query = "SELECT a.*, sa.id AS status_id, sa.status_nome AS status_nome FROM apostas a INNER JOIN status_aposta sa ON sa.id = a.status WHERE a.status = '$status' ORDER BY a.data_aposta DESC";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function deuGreen($id)
    {
        try {
            $queryGetAposta = "SELECT valor, odd, unidade FROM apostas WHERE id = :id";
            $stmtGetAposta = $this->conn->prepare($queryGetAposta);
            $stmtGetAposta->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtGetAposta->execute();
            $aposta = $stmtGetAposta->fetch(PDO::FETCH_ASSOC);

            if ($aposta) {
                $lucro = ($aposta['valor'] * $aposta['odd']) - ($aposta['valor'] * $aposta['unidade']);
                $queryUpdateAposta = "UPDATE apostas SET status = 1, lucro = :lucro WHERE id = :id";
                $stmtUpdateAposta = $this->conn->prepare($queryUpdateAposta);
                $stmtUpdateAposta->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtUpdateAposta->bindParam(':lucro', $lucro, PDO::PARAM_STR);
                $stmtUpdateAposta->execute();

                $mensagem = "Aposta atualizada com sucesso!";
                echo '<script>alert("' . $mensagem . '");</script>';
                echo '<script>window.location.href = "index.php";</script>';
            } else {
                echo "Aposta não encontrada.";
            }
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }


    public function deuRed($id)
    {
        try {
            $queryGetAposta = "SELECT valor FROM apostas WHERE id = :id";
            $stmtGetAposta = $this->conn->prepare($queryGetAposta);
            $stmtGetAposta->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtGetAposta->execute();
            $aposta = $stmtGetAposta->fetch(PDO::FETCH_ASSOC);

            if ($aposta) {
                $valorNegativo = -$aposta['valor'];
                $queryUpdateAposta = "UPDATE apostas SET status = 2, lucro = :valor WHERE id = :id";
                $stmtUpdateAposta = $this->conn->prepare($queryUpdateAposta);
                $stmtUpdateAposta->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtUpdateAposta->bindParam(':valor', $valorNegativo, PDO::PARAM_STR);
                $stmtUpdateAposta->execute();

                $mensagem = "Aposta atualizada com sucesso!";
                echo '<script>alert("' . $mensagem . '");</script>';
                echo '<script>window.location.href = "index.php";</script>';
            } else {
                echo "Aposta não encontrada.";
            }
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }



    public function devolveuAposta($id)
    {
        try {
            $query = "UPDATE apostas SET status = 3, lucro = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $mensagem = "Aposta atualizada com sucesso!";
            echo '<script>alert("' . $mensagem . '");</script>';
            echo '<script>window.location.href = "index.php";</script>';
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function calcularSomaLucro()
    {
        try {
            $query = "SELECT SUM(lucro) AS soma_lucro FROM apostas";
            $stmt = $this->conn->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['soma_lucro'])) {
                return $result['soma_lucro'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }


    public function getApostasFinalizadas()
    {
        try {
            $query = "SELECT a.*, sa.status_nome FROM apostas a INNER JOIN status_aposta sa ON sa.id = a.status WHERE CAST(status AS INTEGER) = 1 OR CAST(status AS INTEGER) = 2 OR CAST(status AS INTEGER) = 3 ORDER BY a.data_aposta DESC";

            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getValorInicialBanca()
    {
        try {
            $infosBanca = $this->getInfosBanca()[0];
            return $infosBanca['valor_inicial_mes'];
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getBancaAtual()
    {
        try {
            $valorInicialMes = $this->getValorInicialBanca();
            $lucroBanca = $this->getLucroBanca();

            $bancaAtual = $valorInicialMes + $lucroBanca;
            $bancaAtual = number_format($bancaAtual, 2, '.', '');

            return $bancaAtual;
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getLucroBanca()
    {
        try {
            $valorInicialBanca = $this->getValorInicialBanca();
            $apostasFinalizadas = $this->getApostasFinalizadas();
            $apostasEmAguardo = $this->getApostasByStatus(4);

            $lucroTotal = 0;

            foreach ($apostasFinalizadas as $aposta) {
                $status = $aposta['status'];
                $lucro = $aposta['lucro'];

                if ($status == 1) {
                    $lucroTotal += $lucro;
                } elseif ($status == 2) {
                    $lucroTotal += $lucro;
                }
            }

            $apostasEmAguardoValor = array_sum(array_column($apostasEmAguardo, 'valor'));
            $lucroTotal -= $apostasEmAguardoValor;

            $diferenca = $lucroTotal;

            if ($valorInicialBanca > 0) {
                $porcentagem = ($diferenca / $valorInicialBanca) * 100;
                $porcentagem = number_format($porcentagem, 2, '.', '');
            } else {
                $porcentagem = 0;
            }

            return $porcentagem;
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getLucroBancaSemApostasAtuais()
    {
        try {
            $valorInicialBanca = $this->getValorInicialBanca();
            $apostasFinalizadas = $this->getApostasFinalizadas();

            $lucroTotal = 0;

            foreach ($apostasFinalizadas as $aposta) {
                $status = $aposta['status'];
                $lucro = $aposta['lucro'];

                if ($status == 1) {
                    $lucroTotal += $lucro;
                } elseif ($status == 2) {
                    $lucroTotal += $lucro;
                }
            }

            $diferenca = $lucroTotal;

            if ($valorInicialBanca > 0) {
                $porcentagem = ($diferenca / $valorInicialBanca) * 100;
                $porcentagem = number_format($porcentagem, 2, '.', '');
            } else {
                $porcentagem = 0;
            }

            return $porcentagem;
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getBancaAtualSemApostasAbertas()
    {
        try {
            $valorInicialMes = $this->getValorInicialBanca();
            $lucroBanca = $this->getLucroBancaSemApostasAtuais();

            $bancaAtual = $valorInicialMes + $lucroBanca;
            $bancaAtual = number_format($bancaAtual, 2, '.', '');

            return $bancaAtual;
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getLucroBancaReais()
    {
        try {
            $apostasFinalizadas = $this->getApostasFinalizadas();
            $apostasEmAguardo = $this->getApostasByStatus(4);

            $lucroTotal = 0;

            foreach ($apostasFinalizadas as $aposta) {
                $status = $aposta['status'];
                $lucro = $aposta['lucro'];

                if ($status == 1 || $status == 2) {
                    $lucroTotal += $lucro;
                }
            }

            $apostasEmAguardoValor = array_sum(array_column($apostasEmAguardo, 'valor'));
            $lucroTotal -= $apostasEmAguardoValor;
            $lucroTotal = number_format($lucroTotal, 2, '.', '');

            return $lucroTotal;
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function getLucroBancaReaisSemApostasAtuais()
    {
        try {
            $apostasFinalizadas = $this->getApostasFinalizadas();

            $lucroTotal = 0;

            foreach ($apostasFinalizadas as $aposta) {
                $status = $aposta['status'];
                $lucro = $aposta['lucro'];

                if ($status == 1 || $status == 2) {
                    $lucroTotal += $lucro;
                }
            }

            $lucroTotal = number_format($lucroTotal, 2, '.', '');

            return $lucroTotal;
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function calcularPorcentagemApostasPorStatus($status)
    {
        try {
            $queryApostasPorStatus = "SELECT COUNT(*) as total FROM apostas WHERE status = :status";
            $stmtApostasPorStatus = $this->conn->prepare($queryApostasPorStatus);
            $stmtApostasPorStatus->bindParam(':status', $status, PDO::PARAM_INT);
            $stmtApostasPorStatus->execute();
            $apostasPorStatus = $stmtApostasPorStatus->fetchColumn();

            $apostasFinalizadas = $this->getApostasFinalizadas();
            $totalApostas = count($apostasFinalizadas);

            if ($totalApostas > 0) {
                $porcentagem = ($apostasPorStatus / $totalApostas) * 100;
                return number_format($porcentagem, 2) . '%';
            } else {
                return '0%';
            }
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }





    public function contarRegistrosPorStatus()
    {
        try {
            $query = "SELECT status, COUNT(*) as total FROM apostas GROUP BY status";
            $stmt = $this->conn->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $statusCounts = array();
            foreach ($result as $row) {
                $status = $row['status'];
                $total = $row['total'];
                $statusCounts[$status] = $total;
            }

            return $statusCounts;
        } catch (PDOException $e) {
            echo "Erro na consulta SQL: " . $e->getMessage();
            return false;
        }
    }
}
