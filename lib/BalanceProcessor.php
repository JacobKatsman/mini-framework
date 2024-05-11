<?php

namespace AppName;
use AppName\TemplateEgngine;

class BalanceProcessor
{
    public $pdo;
    public $template;

    function __construct() {
        $this->template = new TemplateEgngine();
    }

    /**
     * Подключение к базе данных и возврат экземпляра объекта \PDO
     */
    public function dBconnect()
    {
        // чтение параметров в файле конфигурации ini
        $params = parse_ini_file('database.ini');
        if ($params === false) {
            throw new \Exception("Error reading database configuration file");
        }
        try {
            $dsn = "mysql:host=".trim($params['host']).";dbname=".trim($params['db']).";charset=".trim($params['charset']);
            $this->pdo = new \PDO($dsn, trim($params['user']), trim($params['pass']), null);
        } catch (\PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
    }

    /**
     * Отключение от базы данных экземпляра объекта \PDO
     */
    public function dBDisconnect()
    {
        $this->pdo = null;
    }

    public function dBselectListUsers($data)
    {
        $this->dBconnect();

        try {
            $query = "select id, name from users";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $this->template->dBselectListUsersTemplate($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            $this->dBDisconnect();
            die('Запрос не удался: ' . $e->getMessage());
        }
    }

    public function dBselect($data)
    {
        if  (!empty($data)) {
            $id_account = $data->id;
        } else {
            throw new \Exception("error  request");
        }

        $this->dBconnect();
        try {

            $query =  "SELECT T1.tr1 as date, sum(T1.sm1) as sum FROM ((select MONTHNAME(trdate) as tr1, (-1) * sum(b3.amount) as sm1 from transactions as b3  
 left join user_accounts as a2  on (a2.id = b3.account_from and a2.user_id = :idaccount)
 where b3.account_from = :idaccount group by MONTHNAME(trdate))
 union
 (select MONTHNAME(trdate) as date,  sum(b3.amount)as sum from transactions as b3  
 left join user_accounts as a2  on (a2.id = b3.account_to and a2.user_id = :idaccount)
 where b3.account_to = :idaccount group by MONTHNAME(trdate))) AS T1
 GROUP BY T1.tr1";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':idaccount' => $id_account]);
            return $this->template->dBselectBalanceTemplate($stmt->fetchAll(\PDO::FETCH_ASSOC));

        } catch (PDOException $e) {
            $this->dBDisconnect();
            die('Запрос не удался: ' . $e->getMessage());
        }
    }
}
