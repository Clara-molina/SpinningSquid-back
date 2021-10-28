<?php

namespace SpinningSquid\Models;



class NewsLetterCustomerModel extends CoreModel
{

    protected $tableName = "NewsLetter_customer";


    public function _getTableName()
    {
        return $this->tableName;
    }

    public function createTable()
    {
        $sql = "
            CREATE TABLE " . $this->getTableName() . " (
                `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `email` VARCHAR(64) NOT NULL,
                `created_at` datetime NULL,
                `updated_at` datetime NULL
            );
        ";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function insert($email)
    {
        $data = [
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->database->insert(
            $this->getTableName(),
            $data
        );
    }
}