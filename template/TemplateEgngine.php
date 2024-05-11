<?php

namespace AppName;

class TemplateEgngine
{
    /**
     * Возвращаем  json пользователей
     */
    public function dBselectListUsersTemplate($arrUsers)
    {
        $listUser = [];
        foreach ($arrUsers as $row) {
            $listUser[$row['id']] =  $row['name'];
        }
        return json_encode($listUser);
    }

    /**
     * Возвращаем json балансов
     */
    public function dBselectBalanceTemplate($arrBalance)
    {
        return json_encode($arrBalance);
    }
}
