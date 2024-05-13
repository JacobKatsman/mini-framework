<?php

namespace AppName;
use AppName\Connect;
use AppName\TemplateEgngine;


class BalanceProcessor
{
    public $pdo;
    public $template;

    function __construct() {
        $this->template = new TemplateEgngine();
        $this->pdo = Connect::$pdo;
    }

    // TODO распихать по разным классам
    public function dBselectListUsers($data)
    {
        try {
            $query = "select id, name from users";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $this->template->dBselectListUsersTemplate($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            die('Запрос не удался: ' . $e->getMessage());
        }
    }

    public function dBselect($data)
    {
        // TODO ???
        if  (!empty($data)) {
            $id_account = $data->id;
        } else {
            throw new \Exception("error  request");
        }

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

            die('Запрос не удался: ' . $e->getMessage());
        }
    }
}
