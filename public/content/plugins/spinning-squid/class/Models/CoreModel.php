<?php

namespace SpinningSquid\Models;

// abstract, la classe CoreModel n'est pas instanciable
abstract class CoreModel
{

    /**
     * @var wpdb
     */
    protected $database;

    /**
     * @var string
     */
    protected $tableName;

    // BONUS méthode abstraite, nous obligeons les développeur à coder une méthode _getTableName() qui va nous retourner le nom de la table sans le préfixe
    abstract public function _getTableName();


    public function __construct()
    {
        // STEP MODEL $wpdb est une variable globale qui est le "PDO" de wordpress. Il va nous permettre de commiquer avec la base de données
        // DOC WP wp db https://developer.wordpress.org/reference/classes/wpdb/
        global $wpdb;
        $this->database = $wpdb;
    }

    // STEP MODEL récupération du nom de la table avec le bon préfixe (par défaut wordpress utilise le préfixe wp_)
    public function getTableName()
    {
        return $this->database->prefix . $this->_getTableName();
    }


    // la requête pour tous sélectionner est toujours la même
    public function findAll()
    {
        $sql = "
            SELECT * FROM " . $this->getTableName() . "
        ";

        return $this->executeQuery($sql);
    }
}