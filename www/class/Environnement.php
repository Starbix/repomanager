<?php
global $WWW_DIR;
require_once("${WWW_DIR}/class/Database.php");

class Environnement {
    public $db;
    public $name;

    public function __construct(array $variables = []) {
        extract($variables);

        /**
         *  Instanciation d'une db car on peut avoir besoin de récupérer certaines infos en BDD
         */
        try {
            $this->db = new Database();
        } catch(Exception $e) {
            die('Erreur : '.$e->getMessage());
        }

        if (!empty($envName)) {
            $this->name = $envName;
        }
    }

    /**
     *  Création d'un nouvel environnement
     */
    public function new() {
        if (!is_alphanumdash($this->name)) {
            printAlert("Erreur : le nom d'environnement contient des caractères invalides", 'error');
            return;
        }

        /**
         *  On ajoute le nouvel env en BDD
         */
        $stmt = $this->db->prepare("INSERT INTO env (Name) VALUES (:name)");
        $stmt->bindValue(':name', $this->name);
        $stmt->execute();

        /**
         *  Puis rechargement de la page pour voir les modifications de configuration
         */
        header('Location: configuration.php');
        exit;
    }

    /**
     *  Suppression d'un environnement
     */
    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM env WHERE Name=:name");
        $stmt->bindValue(':name', validateData($_GET['deleteEnv']));
        $stmt->execute();

        /**
         *  Puis rechargement de la page pour voir les modifications de configuration
         */
        header('Location: configuration.php');
        exit;
    }

    /**
     *  Edite les environnements actuels
     */
    public function edit(array $envs) {
        /**
         *  D'abord on vérifie que les environnements qu'on souhaite insérer sont valides
         *  On place ces environnements valides dans un nouveau array, ceux qui sont invaldes sont ignorés
         */
        foreach ($envs as $env) {
            if (is_alphanumdash(validateData($env))) {
                $envsToInsert[] = $env;
            }
        }

        /**
         *  Si l'array contient des environnements valides à insérer alors on traite
         */
        if (!empty($envsToInsert)) {
            /**
             *  D'abord on supprime tous les environnements actuels avant d'insérer les nouveaux
             */
            $this->db->exec("DELETE FROM env");

            foreach($envsToInsert as $env) {
                if (!is_alphanumdash($env)) {
                    printAlert("Erreur : le nom d'environnement '$env' contient des caractères invalides", 'error');
                    return;
                }
        
                /**
                 *  On ajoute le nouvel env en BDD
                 */
                $stmt = $this->db->prepare("INSERT INTO env (Name) VALUES (:name)");
                $stmt->bindValue(':name', $env);
                $stmt->execute();
            }
        }

        /**
         *  Puis rechargement de la page pour voir les modifications de configuration
         */
        header('Location: configuration.php');
        exit;
    }

    /**
     *  Liste tous les environnements
     */
    public function listAll() {
        $result = $this->db->query("SELECT Name FROM env");
        
        $datas = array();
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) $datas[] = $row['Name'];

        return $datas;
    }

    /**
     *  Liste l'environnement par défaut
     */
    public function default() {
        $result = $this->db->query("SELECT Name FROM env LIMIT 1");
        
        $default = '';
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) $default = $row['Name'];

        return $default;
    }

    /**
     *  Liste le dernier environnement de la liste
     */
    public function last() {
        $result = $this->db->query("SELECT Id, Name FROM env ORDER BY Id DESC LIMIT 1");
        
        $last = '';
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) $last = $row['Name'];

        return $last;
    }

    /**
     *  Compte le nombre total d'environnements
     */
    public function total() {
        $result = $this->db->query("SELECT Name FROM env");

        $total = $this->db->count($result);

        return $total;
    }
}
?>