<?php

namespace Controllers;

use Exception;
use Datetime;

class Repo
{
    public $model;
    private $op;
    private $repoId;
    private $snapId;
    private $envId;
    private $planId;
    private $name;
    private $source;
    private $packageType;
    private $dist;
    private $section;
    private $date;
    private $dateFormatted;
    private $time;
    private $env;
    private $description;
    private $signed;
    private $type; // miroir ou local
    private $status;
    private $reconstruct;
    // private $sourceFullUrl;
    private $hostUrl;
    private $rootUrl;
    private $gpgCheck;
    private $gpgResign;
    private $rpmSignMethod = RPM_SIGN_METHOD;

    private $targetName;
    private $targetDate;
    private $targetTime;
    private $targetEnv;
    private $targetGroup;
    private $targetDescription;
    private $targetGpgCheck;
    private $targetGpgResign;
    private $targetIncludeSource = 'no';
    private $targetIncludeTranslation;

    private $repoLastName;
    private $repoLastDist;
    private $repoLastSection;
    private $repoLastEnv;
    private $lastSnapId;
    private $lastPackageType;

    public function __construct()
    {
        $this->model = new \Models\Repo();
    }

    public function setRepoId(string $id)
    {
        $this->repoId = \Controllers\Common::validateData($id);
    }

    public function setSnapId(string $id)
    {
        $this->snapId = \Controllers\Common::validateData($id);
    }

    public function setEnvId(string $id)
    {
        $this->envId = \Controllers\Common::validateData($id);
    }

    public function setPlanId(string $id)
    {
        $this->planId = \Controllers\Common::validateData($id);
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDist(string $dist)
    {
        $this->dist = $dist;
    }

    public function setSection(string $section)
    {
        $this->section = $section;
    }

    public function setEnv(string $env)
    {
        $this->env = $env;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
        $this->dateFormatted = DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y');
    }

    public function setTargetDate(string $date)
    {
        $this->targetDate = $date;
        $this->targetDateFormatted = DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y');
    }

    public function setTime(string $time)
    {
        $this->time = $time;
    }

    public function setTargetTime(string $time)
    {
        $this->targetTime = $time;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setSigned(string $signed)
    {
        $this->signed = $signed;
    }

    public function setReconstruct(string $reconstruct)
    {
        $this->reconstruct = $reconstruct;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function setDescription($description = '')
    {
        if ($description == 'nodescription') {
            $description = '';
        }

        $this->description = \Controllers\Common::validateData($description);
    }

    public function setSource(string $source)
    {
        $this->source = $source;
    }

    // public function setSourceFullUrl(string $fullUrl)
    // {
    //     $this->sourceFullUrl = $fullUrl;
    // }

    public function setSourceHostUrl(string $hostUrl)
    {
        $this->hostUrl = $hostUrl;
    }

    public function setSourceRoot(string $root)
    {
        $this->rootUrl = $root;
    }

    public function setPackageType(string $type)
    {
        $this->packageType = $type;
    }

    public function setTargetName(string $name)
    {
        $this->targetName = $name;
    }

    public function setTargetEnv(string $env)
    {
        $this->targetEnv = $env;
    }

    public function setTargetGroup(string $group)
    {
        if ($group == 'nogroup') {
            $this->targetGroup = '';
        } else {
            $this->targetGroup = $group;
        }
    }

    public function setTargetDescription(string $description)
    {
        if ($description == 'nodescription') {
            $this->targetDescription = '';
        } else {
            $this->targetDescription = $description;
        }
    }

    public function setTargetGpgCheck(string $gpgCheck)
    {
        $this->targetGpgCheck = $gpgCheck;
    }

    public function setTargetGpgResign(string $gpgResign)
    {
        $this->targetGpgResign = $gpgResign;
    }

    public function setTargetIncludeSource(string $targetIncludeSource)
    {
        $this->targetIncludeSource = $targetIncludeSource;
    }

    public function setTargetIncludeTranslation(array $targetIncludeTranslation)
    {
        $this->targetIncludeTranslation = $targetIncludeTranslation;
    }

    public function getRepoId()
    {
        return $this->repoId;
    }

    public function getSnapId()
    {
        return $this->snapId;
    }

    public function getEnvId()
    {
        return $this->envId;
    }

    public function getPlanId()
    {
        return $this->planId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDist()
    {
        return $this->dist;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function getPackageType()
    {
        return $this->packageType;
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function getTargetEnv()
    {
        return $this->targetEnv;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getDateFormatted()
    {
        return DateTime::createFromFormat('Y-m-d', $this->date)->format('d-m-Y');
    }

    public function getTargetDate()
    {
        return $this->targetDate;
    }

    public function getTargetDateFormatted()
    {
        return DateTime::createFromFormat('Y-m-d', $this->targetDate)->format('d-m-Y');
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getTargetTime()
    {
        return $this->targetTime;
    }

    public function getReconstruct()
    {
        return $this->reconstruct;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getHostUrl()
    {
        return $this->hostUrl;
    }

    public function getRootUrl()
    {
        return $this->rootUrl;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSigned()
    {
        return $this->signed;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTargetName()
    {
        return $this->targetName;
    }

    public function getTargetGroup()
    {
        return $this->targetGroup;
    }

    public function getTargetDescription()
    {
        return $this->targetDescription;
    }

    public function getTargetGpgCheck()
    {
        return $this->targetGpgCheck;
    }

    public function getTargetGpgResign()
    {
        return $this->targetGpgResign;
    }

    public function getOpLogLocation()
    {
        return $this->op->log->location;
    }

    public function getOpStatus()
    {
        return $this->op->getStatus();
    }

    public function getOpError()
    {
        return $this->op->getError();
    }

    /**
     *  Récupère toutes les informations d'un repo, snapshot en env en base de données
     */
    public function getAllById(string $repoId = null, string $snapId = null, string $envId = null)
    {
        $data = $this->model->getAllById($repoId, $snapId, $envId);

        $this->getAllByParser($data);
    }

    /**
     *  Fonction qui parse et récupère les résultats des fonctions getAllBy*
     */
    private function getAllByParser(array $data)
    {
        if (!empty($data['Source'])) {
            $this->setSource($data['Source']);
        }
        if (!empty($data['Name'])) {
            $this->setName($data['Name']);
        }
        if (!empty($data['Dist'])) {
            $this->setDist($data['Dist']);
        }
        if (!empty($data['Section'])) {
            $this->setSection($data['Section']);
        }
        if (!empty($data['Package_type'])) {
            $this->setPackageType($data['Package_type']);
        }
        if (!empty($data['Date'])) {
            $this->setDate($data['Date']);
        }
        if (!empty($data['Time'])) {
            $this->setTime($data['Time']);
        }
        if (!empty($data['Status'])) {
            $this->setStatus($data['Status']);
        }
        if (!empty($data['Env'])) {
            $this->setEnv($data['Env']);
        }
        if (!empty($data['Type'])) {
            $this->setType($data['Type']);
        }
        if (!empty($data['Signed'])) {
            $this->setSigned($data['Signed']);
        }
        if (!empty($data['Reconstruct'])) {
            $this->setReconstruct($data['Reconstruct']);
        }
        if (!empty($data['Description'])) {
            $this->setDescription($data['Description']);
        }
        if (!empty($data['repoId'])) {
            $this->setRepoId($data['repoId']);
        }
        if (!empty($data['snapId'])) {
            $this->setSnapId($data['snapId']);
        }
        if (!empty($data['envId'])) {
            $this->setEnvId($data['envId']);
        }
        if ($this->packageType == 'deb' and $this->type == "mirror") {
            $this->getFullSource($this->source);
        }
    }

    /**
     *  Retourne l'Id d'un repo en base de données, à partir de son nom
     */
    public function getIdByName(string $name, string $dist = null, string $section = null)
    {
        return $this->model->getIdByName($name, $dist, $section);
    }

    /**
     *  Récupère l'url source complete avec la racine du dépot (Debian uniquement)
     */
    private function getFullSource(string $source)
    {
        /**
         *  Récupère l'url complète en base de données
         */
        $fullUrl = $this->model->getFullSource($source);

        if (empty($fullUrl)) {
            throw new Exception('impossible de déterminer l\'URL du repo source');
        }

        /**
         *  On retire http:// ou https:// du début de l'URL
         */
        $fullUrl = str_replace(array("http://", "https://"), '', $fullUrl);

        /**
         *  Extraction de l'adresse de l'hôte (server.domain.net) à partir de l'url http
         */
        $hostUrl = exec("echo '$fullUrl' | cut -d'/' -f1");

        /**
         *  Extraction de la racine de l'hôte (ex pour : ftp.fr.debian.org/debian ici la racine sera debian)
         */
        $root = str_replace($hostUrl, '', $fullUrl);

        if (empty($hostUrl)) {
            throw new Exception('impossible de déterminer l\'adresse du repo source');
        }
        if (empty($root)) {
            throw new Exception('impossible de déterminer la racine de l\'URL du repo source');
        }

        // $this->setSourceFullUrl($fullUrl);
        $this->setSourceHostUrl($hostUrl);
        $this->setSourceRoot($root);
    }

    /**
     *  Retourne l'Id du snapshot le + récent du repo
     */
    public function getLastSnapshotId(string $repoId)
    {
        return $this->model->getLastSnapshotId($repoId);
    }

    /**
     *  Retoune true si l'Id de repo existe en base de données
     */
    public function existsId(string $repoId)
    {
        return $this->model->existsId($repoId);
    }

    /**
     *  Retourne true si un Id de snapshot existe en base de données
     */
    public function existsSnapId(string $snapId)
    {
        return $this->model->existsSnapId($snapId);
    }

    /**
     *  Vérifie si un repo existe et est actif (contient des snapshots actifs)
     */
    public function isActive(string $name, string $dist = null, string $section = null)
    {
        return $this->model->isActive($name, $dist, $section);
    }

    /**
     *  Retourne true si une opération est en cours sur l'Id de snapshot spécifié
     */
    public function snapOpIsRunning(string $snapId)
    {
        return $this->model->snapOpIsRunning($snapId);
    }

    /**
     *  Retourne un array de tous les noms de repos, sans informations des snapshots et environnements associés
     *  Si le paramètre 'true' est passé alors la fonction renverra uniquement les noms des repos qui ont un snapshot actif rattaché
     *  Si le paramètre 'false' est passé alors la fonction renverra tous les noms de repos avec ou sans snapshot rattaché
     */
    public function listNameOnly(bool $bool = false)
    {
        return $this->model->listNameOnly($bool);
    }

    /**
     *  Retourne la liste des repos, leurs snapshots et leur environnements
     *  N'affiche pas les repos qui n'ont aucun environnement actif
     */
    public function list()
    {
        return $this->model->list();
    }

    /**
     *  Retourne la liste des repos éligibles aux planifications
     *  Il s'agit des repos ayant au moins 1 snapshot actif
     */
    public function listForPlan()
    {
        return $this->model->listForPlan();
    }

    /**
     *  Retourne la liste des repos par groupes
     */
    public function listByGroup(string $groupName)
    {
        return $this->model->listByGroup($groupName);
    }

    /**
     *  Retourne le liste des noms de repos actifs, par groupe
     *  Utilisée notamment pour les planifications de groupes
     */
    public function listNameByGroup(string $groupName)
    {
        return $this->model->listNameByGroup($groupName);
    }

    /**
     *  Retourne le nombre total de repos
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     *  Création d'un nouveau miroir de repo / section
     */
    public function new()
    {
        /**
         *  On défini la date du jour et l'environnement par défaut sur lesquels sera basé le nouveau miroir
         */
        $this->setTargetDate(date('Y-m-d'));
        $this->setTargetTime(date("H:i"));

        /**
         *  Démarrage de l'opération
         *  On indique à startOperation, le nom du repo/section en cours de création. A la fin de l'opération, on remplacera cette valeur directement par
         *  l'ID en BDD de ce repo/section créé.
         *  On indique également si on a activé ou non gpgCheck et gpgResign.
         */
        $this->op = new \Controllers\Operation();
        $this->op->setAction('new');
        $this->op->setType('manual');

        if ($this->packageType == "rpm") {
            $this->op->startOperation(
                array(
                    'id_repo_target' => $this->name,
                    'gpgCheck' => $this->targetGpgCheck,
                    'gpgResign' => $this->targetGpgResign
                )
            );
        }
        if ($this->packageType == "deb") {
            $this->op->startOperation(
                array(
                    'id_repo_target' => $this->name . '|' . $this->dist . '|' . $this->section,
                    'gpgCheck' => $this->targetGpgCheck,
                    'gpgResign' => $this->targetGpgResign
                )
            );
        }

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 7;
        exec('php ' . ROOT . '/operations/logbuilder.php ' . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        try {
            /**
             *  Etape 1 : Afficher les détails de l'opération
             */
            $this->printDetails("CREATION D'UN NOUVEAU REPO");

            /**
             *   Etape 2 : récupération des paquets
             */
            $this->getPackages();

            /**
             *   Etape 3 : signature des paquets/du repo
             */
            $this->signPackages();

            /**
             *   Etape 4 : Création du repo et liens symboliques
             */
            $this->createRepo();

            /**
             *   Etape 5 : Finalisation du repo (ajout en BDD et application des droits)
             */
            $this->finalize();

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            $this->op->stepError($e->getMessage()); // On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());
        }

        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
     *  Création d'un nouveau repo / section local
     */
    public function newLocalRepo()
    {
        /**
         *  On défini la date du jour et l'environnement par défaut sur lesquels sera basé le nouveau miroir
         */
        $this->setTargetDate(date('Y-m-d'));
        $this->setTargetTime(date("H:i"));

        /**
         *  Démarrage de l'opération
         */
        $this->op = new \Controllers\Operation();
        $this->op->setAction('new');
        $this->op->setType('manual');

        if ($this->packageType == "rpm") {
            $this->op->startOperation(array('id_repo_target' => $this->name));
        }
        if ($this->packageType == "deb") {
            $this->op->startOperation(array('id_repo_target' => $this->name . '|' . $this->dist . '|' . $this->section));
        }

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 2;
        exec('php ' . ROOT . '/operations/logbuilder.php ' . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        try {
            ob_start();

            /**
             *  1. Génération du tableau récapitulatif de l'opération
             */
            include(ROOT . '/templates/tables/op-new-local.inc.php');

            $this->op->step('CREATION DU REPO');

            /**
             *  2. On vérifie que le nom du repo n'est pas vide
             */
            if (empty($this->name)) {
                throw new Exception('le nom du repo ne peut être vide');
            }

            /**
             *  3. Création du répertoire avec le nom du repo, et les sous-répertoires permettant d'acceuillir les futurs paquets
             */
            if ($this->packageType == 'rpm') {
                if (!file_exists(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/Packages')) {
                    if (!mkdir(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/Packages', 0770, true)) {
                        throw new Exception("impossible de créer le répertoire du repo {$this->name}");
                    }
                }
            }
            if ($this->packageType == 'deb') {
                if (!file_exists(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->targetDateFormatted . '_' . $this->section . '/pool/' . $this->section)) {
                    if (!mkdir(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->targetDateFormatted . '_' . $this->section . '/pool/' . $this->section, 0770, true)) {
                        throw new Exception('impossible de créer le répertoire de la section');
                    }
                }
            }

            /**
             *   4. Création du lien symbolique, si un environnement a été spécifié par l'utilisateur
             */
            if (!empty($this->targetEnv)) {
                if ($this->packageType == 'rpm') {
                    exec('cd ' . REPOS_DIR . '/ && ln -sfn ' . $this->targetDateFormatted . '_' . $this->name . ' ' . $this->name . '_' . $this->targetEnv, $output, $result);
                }
                if ($this->packageType == 'deb') {
                    exec('cd ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/ && ln -sfn ' . $this->targetDateFormatted . '_' . $this->section . ' ' . $this->section . '_' . $this->targetEnv, $output, $result);
                }
                if ($result != 0) {
                    throw new Exception('impossible de créer le repo');
                }
            }

            /**
             *  Vérification de l'existance du repo en base de données
             */
            if ($this->packageType == "rpm") {
                $exists = $this->model->exists($this->name);
            }
            if ($this->packageType == "deb") {
                $exists = $this->model->exists($this->name, $this->dist, $this->section);
            }

            /**
             *  Si actuellement aucun repo de ce nom n'existe en base de données alors on l'ajoute
             *  Note : ici on renseigne la source comme étant $this->name
             */
            if ($exists === false) {
                if ($this->packageType == "rpm") {
                    $this->model->add($this->name, 'rpm', $this->name);
                }
                if ($this->packageType == "deb") {
                    $this->model->add($this->name, 'deb', $this->name, $this->dist, $this->section);
                }

                /**
                 *  L'Id du repo devient alors l'Id de la dernière ligne insérée en base de données
                 */
                $this->repoId = $this->model->getLastInsertRowID();

            /**
             *  Sinon si un repo de même nom existe, on rattache ce nouveau snapshot et ce nouvel env à ce repo
             */
            } else {
                /**
                 *  D'abord on récupère l'Id en base de données du repo
                 */
                if ($this->packageType == "rpm") {
                    $this->repoId = $this->model->getIdByName($this->name, '', '');
                }

                if ($this->packageType == "deb") {
                    $this->repoId = $this->model->getIdByName($this->name, $this->dist, $this->section);
                }
            }
            unset($exists);

            /**
             *  Ajout du snapshot en base de données
             */
            $this->model->addSnap($this->targetDate, $this->targetTime, 'no', $this->type, 'active', $this->repoId);

            /**
             *  Récupération de l'Id du snapshot ajouté précédemment
             */
            $this->setSnapId($this->model->getLastInsertRowID());

            /**
             *  Ajout de l'env en base de données si un env a été spécifié par l'utilisateur
             */
            if (!empty($this->targetEnv)) {
                $this->model->addEnv($this->targetEnv, $this->targetDescription, $this->snapId);
            }

            /**
             *  6. Application des droits sur le nouveau repo créé
             */
            if ($this->packageType == 'rpm') {
                exec('find ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/ -type f -exec chmod 0660 {} \;');
                exec('find ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/ -type d -exec chmod 0770 {} \;');
                exec('chown -R ' . WWW_USER . ':repomanager ' . REPOS_DIR . "/{$this->targetDateFormatted}_{$this->name}");
            }
            if ($this->packageType == 'deb') {
                exec('find ' . REPOS_DIR . '/' . $this->name . '/ -type f -exec chmod 0660 {} \;');
                exec('find ' . REPOS_DIR . '/' . $this->name . '/ -type d -exec chmod 0770 {} \;');
                exec('chown -R ' . WWW_USER . ':repomanager ' . REPOS_DIR . "/{$this->name}");
            }

            $this->op->stepOK();

            /**
             *  7. Ajout de la section à un groupe si un groupe a été renseigné
             */
            if (!empty($this->targetGroup)) {
                $this->op->step('AJOUT A UN GROUPE');
                $this->addRepoIdToGroup($this->repoId, $this->targetGroup);
                $this->op->stepOK();
            }

            /**
             *  Nettoyage des repos inutilisés dans les groupes
             */
            $this->cleanGroups();

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            /**
             *  On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log
             */
            $this->op->stepError($e->getMessage());

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());
        }

        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
     *  Mise à jour d'un miroir de repo / section
     */
    public function update()
    {
        /**
         *  On défini la date du jour et l'environnement par défaut sur lesquels sera basé le nouveau miroir
         */
        $this->setTargetDate(date('Y-m-d'));
        $this->setTargetTime(date("H:i"));

        /**
         *  Création d'une opération en BDD, on indique également si on a activé ou non gpgCheck et gpgResign
         *  Si cette fonction est appelée par une planification, alors l'id de cette planification est stockée dans $this->id_plan, on l'indique également à startOperation()
         */
        $this->op = new \Controllers\Operation();
        $this->op->setAction('update');

        /**
         *  Si un Id de planification a été spécifié alors ça signifie que l'action a été initialisée par une planification
         */
        if (!empty($this->planId)) {
            $this->op->setType('plan');
        } else {
            $this->op->setType('manual');
        }

        if ($this->op->getType() == 'manual') {
            $this->op->startOperation(
                array(
                    'id_snap_target' => $this->snapId,
                    'gpgCheck' => $this->targetGpgCheck,
                    'gpgResign' => $this->targetGpgResign
                )
            );
        }
        if ($this->op->getType() == 'plan') {
            $this->op->startOperation(
                array(
                    'id_snap_target' => $this->snapId,
                    'gpgCheck' => $this->targetGpgCheck,
                    'gpgResign' => $this->targetGpgResign,
                    'id_plan' => $this->planId
                )
            );
        }

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 7;
        exec('php ' . ROOT . '/operations/logbuilder.php ' . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        try {
            /**
             *  Etape 1 : Afficher les détails de l'opération
             */
            $this->printDetails("MISE A JOUR D'UN REPO");

            /**
            *   Etape 2 : récupération des paquets
            */
            $this->getPackages();

            /**
            *   Etape 3 : signature des paquets/du repo
            */
            $this->signPackages();

            /**
            *   Etape 4 : Création du repo et liens symboliques
            */
            $this->createRepo();

            /**
            *   Etape 6 : Finalisation du repo (ajout en BDD et application des droits)
            */
            $this->finalize();

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            $this->op->stepError($e->getMessage()); // On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());

            /**
             *  Cloture de l'opération
             */
            $this->op->closeOperation();

            /**
             *  Cas où cette fonction est lancée par une planification : la planif attend un retour, on lui renvoie false pour lui indiquer qu'il y a eu une erreur
             */
            return false;
        }
        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
     *  Dupliquer un snapshot de repo
     */
    public function duplicate()
    {
        /**
         *  Démarrage de l'opération
         */
        $this->op = new \Controllers\Operation();
        $this->op->setAction('duplicate');
        $this->op->setType('manual');

        if ($this->packageType == "rpm") {
            $this->op->startOperation(
                array(
                    'id_snap_source' => $this->snapId,
                    'id_repo_target' => $this->targetName
                )
            );
        }
        if ($this->packageType == "deb") {
            $this->op->startOperation(
                array(
                    'id_snap_source' => $this->snapId,
                    'id_repo_target' => $this->targetName . '|' . $this->dist . '|' . $this->section
                )
            );
        }

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 4;
        exec("php " . ROOT . "/operations/logbuilder.php " . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        try {
            ob_start();

            /**
             *  1. Génération du tableau récapitulatif de l'opération
             */
            include(ROOT . '/templates/tables/op-duplicate.inc.php');

            $this->op->step('DUPLICATION');

            /**
             *  On vérifie que le snapshot source existe
             */
            if ($this->model->existsSnapId($this->snapId) === false) {
                throw new Exception("Le snapshot de repo source n'existe pas");
            }

            /**
             *  On vérifie qu'un repo de même nom cible n'existe pas déjà
             */
            if ($this->packageType == "rpm") {
                if ($this->model->isActive($this->targetName) === true) {
                    throw new Exception('un repo <span class="label-black">' . $this->targetName . '</span> existe déjà');
                }
            }
            if ($this->packageType == "deb") {
                if ($this->model->isActive($this->targetName, $this->dist, $this->section) === true) {
                    throw new Exception('un repo <span class="label-black">' . $this->targetName . ' ❯ ' . $this->dist . ' ❯ ' . $this->section . '</span> existe déjà');
                }
            }

            /**
             *  Création du nouveau répertoire avec le nouveau nom du repo :
             */
            if ($this->packageType == "rpm") {
                if (!file_exists(REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->targetName)) {
                    if (!mkdir(REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->targetName, 0770, true)) {
                        throw new Exception("impossible de créer le répertoire du nouveau repo <b>" . $this->targetName . "</b>");
                    }
                }
            }
            if ($this->packageType == "deb") {
                if (!file_exists(REPOS_DIR . '/' . $this->targetName . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section)) {
                    if (!mkdir(REPOS_DIR . '/' . $this->targetName . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section, 0770, true)) {
                        throw new Exception("impossible de créer le répertoire du nouveau repo <b>" . $this->targetName . "</b>");
                    }
                }
            }

            /**
             *  Copie du contenu du repo/de la section
             *  Anti-slash devant la commande cp pour forcer l'écrasement si un répertoire de même nom trainait par là
             */
            if ($this->packageType == "rpm") {
                exec('\cp -r ' . REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->name . '/* ' . REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->targetName . '/', $output, $result);
            }
            if ($this->packageType == "deb") {
                exec('\cp -r ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section . '/* ' . REPOS_DIR . '/' . $this->targetName . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section . '/', $output, $result);
            }
            if ($result != 0) {
                throw new Exception('impossible de copier les données du repo source vers le nouveau repo');
            }

            $this->op->stepOK();

            /**
             *  Sur Debian il faut reconstruire les données du repo avec le nouveau nom du repo.
             */
            if ($this->packageType == "deb") {
                /**
                 *  Pour les besoins de la fonction createRepo(), il faut que le nom du repo à créer soit dans $name.
                 *  Du coup on backup temporairement le nom actuel et on le remplace par $this->targetName
                 */
                $backupName = $this->name;
                $this->setName($this->targetName);
                $this->setTargetDate($this->date);

                $this->op->log->steplog(2);
                $this->createRepo();

                /**
                 *  On remets en place le nom tel qu'il était
                 */
                $this->setName($backupName);
            }

            $this->op->step('FINALISATION');

            /**
             *  Création du lien symbolique
             *  Seulement si l'utilisateur a spécifié un environnement
             */
            if (!empty($this->targetEnv)) {
                if ($this->packageType == "rpm") {
                    exec('cd ' . REPOS_DIR . '/ && ln -sfn ' . $this->dateFormatted . '_' . $this->targetName . ' ' .  $this->targetName . '_' . $this->targetEnv, $output, $result);
                }
                if ($this->packageType == "deb") {
                    exec('cd ' . REPOS_DIR . '/' . $this->targetName . '/' . $this->dist . '/ && ln -sfn ' . $this->dateFormatted . '_' . $this->section . ' ' . $this->section . '_' . $this->targetEnv, $output, $result);
                }
                if ($result != 0) {
                    throw new Exception('impossible de créer le nouveau repo');
                }
            }

            /**
             *  8. Insertion du nouveau repo en base de données
             */
            if ($this->packageType == "rpm") {
                $this->model->add($this->source, 'rpm', $this->targetName);
            }
            if ($this->packageType == "deb") {
                $this->model->add($this->source, 'deb', $this->targetName, $this->dist, $this->section);
            }

            /**
             *  On récupère l'Id du repo créé en base de données
             */
            $targetRepoId = $this->model->getLastInsertRowID();

            /**
             *  On ajoute le snapshot copié en base de données
             */
            $this->model->addSnap($this->date, $this->time, $this->signed, $this->type, $this->status, $targetRepoId);

            /**
             *  On récupère l'Id du snapshot créé en base de données
             */
            $targetSnapId = $this->model->getLastInsertRowID();

            /**
             *  On ajoute l'environnement créé
             *  Seulement si l'utilisateur a spécifié un environnement
             */
            if (!empty($this->targetEnv)) {
                $this->model->addEnv($this->targetEnv, $this->targetDescription, $targetSnapId);
            }

            /**
             *  9. Application des droits sur le nouveau repo créé
             */
            if ($this->packageType == "rpm") {
                exec('find ' . REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->targetName . '/ -type f -exec chmod 0660 {} \;');
            }
            if ($this->packageType == "deb") {
                exec('find ' . REPOS_DIR . '/' . $this->targetName . '/ -type d -exec chmod 0770 {} \;');
            }
            exec('chown -R ' . WWW_USER . ':repomanager ' . REPOS_DIR . '/' . $this->targetName . '/');

            $this->op->stepOK();

            /**
             *  10. Ajout de la section à un groupe si un groupe a été renseigné
             */
            if (!empty($this->targetGroup)) {
                $this->op->step('AJOUT A UN GROUPE');

                /**
                 *  Ajout du repo créé au groupe spécifié
                 */
                $this->addRepoIdToGroup($targetRepoId, $this->targetGroup);

                $this->op->stepOK();
            }

            /**
             *  Nettoyage des repos inutilisés dans les groupes
             */
            $this->cleanGroups();

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            /**
             *  On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log
             */
            $this->op->stepError($e->getMessage());

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());
        }

        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
     *  Reconstruire les métadonnées d'un snapshot
     */
    public function reconstruct()
    {
        $this->setTargetDate($this->getDate());

        /**
         *  Création d'une opération en BDD, on indique également si on a activé ou non gpgCheck et gpgResign
         *  Si cette fonction est appelée par une planification, alors l'id de cette planification est stockée dans $this->id_plan, on l'indique également à startOperation()
         */
        $this->op = new \Controllers\Operation();
        $this->op->setAction('reconstruct');
        $this->op->setType('manual');

        $this->op->startOperation(
            array(
                'id_snap_target' => $this->snapId,
                'gpgResign' => $this->targetGpgResign
            )
        );

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 3;
        exec('php ' . ROOT . '/operations/logbuilder.php ' . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        /**
         *  Modification de l'état de reconstruction des métadonnées du snapshot en base de données
         */
        $this->model->snapSetReconstruct($this->snapId, 'running');

        try {
            /**
             *  Etape 1 : Afficher les détails de l'opération
             */
            $this->printDetails('RECONSTRUCTION DES METADONNÉES DU REPO');
            /**
            *   Etape 2 : signature des paquets/du repo
            */
            $this->signPackages();
            /**
            *   Etape 3 : Création du repo et liens symboliques
            */
            $this->createRepo();

            /**
             *  Etape 4 : on modifie l'état de la signature du repo en BDD
             *  Comme on a reconstruit les fichiers du repo, il est possible qu'on soit passé d'un repo signé à un repo non-signé, ou inversement
             *  Il faut donc modifier l'état en BDD
             */
            $this->model->snapSetSigned($this->snapId, $this->targetGpgResign);

            /**
             *  Modification de l'état de reconstruction des métadonnées du snapshot en base de données
             */
            $this->model->snapSetReconstruct($this->snapId, '');

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            $this->op->stepError($e->getMessage()); // On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());

            /**
             *  Modification de l'état de reconstruction des métadonnées du snapshot en base de données
             */
            $this->model->snapSetReconstruct($this->snapId, 'failed');
        }
        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
     *  Suppression d'un snapshot de repo
     */
    public function delete()
    {
        $this->op = new \Controllers\Operation();
        $this->op->setAction('delete');
        $this->op->setType('manual');
        $this->op->startOperation(array('id_snap_target' => $this->snapId));

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 1;
        exec('php ' . ROOT . '/operations/logbuilder.php ' . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        try {
            ob_start();

            /**
             *  1. Génération du tableau récapitulatif de l'opération
             */
            include(ROOT . '/templates/tables/op-delete.inc.php');

            $this->op->step('SUPPRESSION');

            /**
             *  2. Suppression du snapshot
             */
            $result = 0;

            if ($this->packageType == "rpm") {
                if (is_dir(REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->name)) {
                    exec('rm ' . REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->name . ' -rf', $output, $result);
                }
            }
            if ($this->packageType == "deb") {
                if (is_dir(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section)) {
                    exec('rm ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section . ' -rf', $output, $result);
                }
            }

            if ($result != 0) {
                throw new Exception('impossible de supprimer le snapshot du <span class="label-black">' . $this->dateFormatted . '</span>');
            }

            $this->op->stepOK();

            /**
             *  Passage du snapshot en état 'deleted' en base de données
             */
            $this->model->snapSetStatus($this->snapId, 'deleted');

            /**
             *  Récupération des Id d'environnements qui pointaient vers ce snapshot
             */
            $envIds = $this->model->getEnvIdBySnapId($this->snapId);

            /**
             *  On traite chaque Id d'environnement qui pointait vers ce snapshot
             */
            if (!empty($envIds)) {
                foreach ($envIds as $envId) {
                    /**
                     *  Suppression des environnements pointant vers ce snapshot en base de données
                     */
                    $myrepo = new Repo();
                    $myrepo->getAllById('', '', $envId);

                    /**
                     *  Si un lien symbolique de cet environnement pointait vers le snapshot supprimé alors on peut supprimer le lien symbolique.
                     */
                    if ($this->getPackageType() == 'rpm') {
                        if (is_link(REPOS_DIR . '/' . $this->name . '_' . $myrepo->getEnv())) {
                            if (readlink(REPOS_DIR . '/' . $this->name . '_' . $myrepo->getEnv()) == $this->dateFormatted . '_' . $this->name) {
                                unlink(REPOS_DIR . '/' . $this->name . '_' . $myrepo->getEnv());
                            }
                        }
                    }
                    if ($this->getPackageType() == 'deb') {
                        if (is_link(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $myrepo->getEnv())) {
                            if (readlink(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $myrepo->getEnv()) == $this->dateFormatted . '_' . $this->section) {
                                unlink(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $myrepo->getEnv());
                            }
                        }
                    }
                    unset($myrepo);
                }
            }

            /**
             *  Nettoyage des repos inutilisés dans les groupes
             */
            $this->cleanGroups();

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            /**
             *  On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log
             */
            $this->op->stepError($e->getMessage());

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());
        }

        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
     *  Suppression d'un environnement
     */
    public function removeEnv()
    {
        $this->op = new \Controllers\Operation();
        $this->op->setAction('removeEnv');
        $this->op->setType('manual');

        $this->op->startOperation(array(
            'id_snap_target' => $this->snapId,
            'id_env_target' => $this->env));

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 2;
        exec('php ' . ROOT . '/operations/logbuilder.php ' . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        try {
            ob_start();

            /**
             *  1. Génération du tableau récapitulatif de l'opération
             */
            include(ROOT . '/templates/tables/op-remove-env.inc.php');

            $this->op->step('SUPPRESSION');

            /**
             *  2. Suppression du lien symbolique de l'environnement
             */
            if ($this->packageType == "rpm") {
                if (file_exists(REPOS_DIR . '/' . $this->name . '_' . $this->env)) {
                    unlink(REPOS_DIR . '/' . $this->name . '_' . $this->env);
                }
            }
            if ($this->packageType == "deb") {
                if (file_exists(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $this->env)) {
                    unlink(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $this->env);
                }
            }

            /**
             *  3. Suppression de l'environnement en base de données
             */
            $this->model->deleteEnv($this->envId);

            $this->op->stepOK();

            /**
             *  Nettoyage automatique des snapshots inutilisés
             */
            $snapshotsRemoved = $this->cleanSnapshots();

            if (!empty($snapshotsRemoved)) {
                $this->op->step('NETTOYAGE');
                $this->op->stepOK($snapshotsRemoved);
            }

            /**
             *  Nettoyage des repos inutilisés dans les groupes
             */
            $this->cleanGroups();

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            /**
             *  On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log
             */
            $this->op->stepError($e->getMessage());

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());
        }

        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
     *  Création d'un nouvel environnement de repo
     */
    public function env()
    {
        /**
         *  Démarrage d'une nouvelle opération
         */
        $this->op = new \Controllers\Operation();
        $this->op->setAction('env');
        $this->op->setType('manual');
        if ($this->op->getType() == 'manual') {
            $this->op->startOperation(array(
                'id_snap_target' => $this->snapId,
                'id_env_target' => $this->targetEnv));
        }

        /**
         *  Ajout du PID de ce processus dans le fichier PID
         */
        $this->op->log->addsubpid(getmypid());

        /**
         *  Nettoyage du cache
         */
        \Controllers\Common::clearCache();

        /**
         *  Lancement du script externe qui va construire le fichier de log principal à partir des petits fichiers de log de chaque étape
         */
        $steps = 4;
        exec('php ' . ROOT . '/operations/logbuilder.php ' . PID_DIR . "/{$this->op->log->pid}.pid {$this->op->log->location} " . TEMP_DIR . "/{$this->op->log->pid} $steps >/dev/null 2>/dev/null &");

        try {
            ob_start();

            /**
             *  1. Génération du tableau récapitulatif de l'opération
             */
            include(ROOT . '/templates/tables/op-env.inc.php');

            $this->op->step('NOUVEL ENVIRONNEMENT ' . \Controllers\Common::envtag($this->targetEnv));

            /**
             *  2. On vérifie si le snapshot source existe
             */
            if ($this->model->existsSnapId($this->snapId) === false) {
                throw new Exception('Le snapshot cible n\'existe pas');
            }

            /**
             *  3. On vérifie qu'un même environnement pointant vers le snapshot cible n'existe pas déjà
             */
            if ($this->model->existsSnapIdEnv($this->snapId, $this->targetEnv) === true) {
                if ($this->packageType == 'rpm') {
                    throw new Exception('Un environnement ' . \Controllers\Common::envtag($this->targetEnv) . ' existe déjà sur <span class="label-white">' . $this->name . '</span>⟶<span class="label-black">' . $this->dateFormatted . '</span>');
                }

                if ($this->packageType == 'deb') {
                    throw new Exception('Un environnement ' . \Controllers\Common::envtag($this->targetEnv) . ' existe déjà sur <span class="label-white">' . $this->name . ' ❯ ' . $this->dist . ' ❯ ' . $this->section . '</span>⟶<span class="label-black">' . $this->dateFormatted . '</span>');
                }
            }

            /**
             *  Si l'utilisateur n'a précisé aucune description alors on récupère celle actuellement en place sur l'environnement de même nom (si l'environnement existe et si il possède une description)
             */
            if (empty($this->targetDescription)) {
                if ($this->packageType == 'rpm') {
                    $actualDescription = $this->model->getDescriptionByName($this->name, '', '', $this->targetEnv);
                }
                if ($this->packageType == 'deb') {
                    $actualDescription = $this->model->getDescriptionByName($this->name, $this->dist, $this->section, $this->targetEnv);
                }

                /**
                 *  Si la description récupérée est vide alors la description restera vide
                 */
                if (!empty($actualDescription)) {
                    $this->targetDescription = $actualDescription;
                } else {
                    $this->targetDescription = '';
                }
            }

            /**
             *  4. Traitement
             *  Deux cas possibles :
             *   1. Ce repo/section n'avait pas d'environnement pointant vers le snapshot cible, on crée simplement un lien symbo et on crée le nouvel environnement en base de données.
             *   2. Ce repo/section avait déjà un environnement pointant vers un snapshot, on le supprime et on fait pointer l'environnement vers le nouveau snapshot.
             */
            if ($this->packageType == 'rpm') {
                /**
                 *  Cas 1 : pas d'environnement de même nom existant sur ce snapshot
                 */
                if ($this->model->existsEnv($this->name, null, null, $this->targetEnv) === false) {
                    /**
                     *  Suppression du lien symbolique (on sait ne jamais si il existe)
                     */
                    if (is_link(REPOS_DIR . '/' . $this->name . '_' . $this->targetEnv)) {
                        unlink(REPOS_DIR . '/' . $this->name . '_' . $this->targetEnv);
                    }

                    /**
                     *  Création du lien symbolique
                     */
                    exec('cd ' . REPOS_DIR . '/ && ln -sfn ' . $this->dateFormatted . '_' . $this->name . ' ' . $this->name . '_' . $this->targetEnv);

                    /**
                     *  Ajout de l'environnement en BDD
                     */
                    $this->model->addEnv($this->targetEnv, $this->targetDescription, $this->snapId);

                    /**
                     *  Clôture de l'étape en cours
                     */
                    $this->op->stepOK();

                /**
                 *  Cas 2 : Il y a déjà un environnement de repo du même nom pointant vers un snapshot.
                 */
                } else {
                    /**
                     *  On récupère l'Id de l'environnement déjà existant
                     */
                    $actualEnvId = $this->model->getEnvIdFromRepoName($this->name, null, null, $this->targetEnv);

                    /**
                     *  On supprime l'éventuel environnement de même nom pointant déjà vers un snapshot de ce repo (si il y en a un)
                     */
                    if (!empty($actualEnvId)) {
                        $this->model->deleteEnv($actualEnvId);
                    }

                    /**
                     *  Suppression du lien symbolique
                     */
                    if (is_link(REPOS_DIR . '/' . $this->name . '_' . $this->targetEnv)) {
                        unlink(REPOS_DIR . '/' . $this->name . '_' . $this->targetEnv);
                    }

                    /**
                     *  Création du nouveau lien symbolique, pointant vers le snapshot cible
                     */
                    exec('cd ' . REPOS_DIR . '/ && ln -sfn ' . $this->dateFormatted . '_' . $this->name . ' ' . $this->name . '_' . $this->targetEnv);

                    /**
                     *  Puis on déclare le nouvel environnement et on le fait pointer vers le snapshot précédemment créé
                     */
                    $this->model->addEnv($this->targetEnv, $this->targetDescription, $this->snapId);

                    /**
                     *  Clôture de l'étape en cours
                     */
                    $this->op->stepOK();
                }
            }

            if ($this->packageType == 'deb') {
                /**
                 *  Cas 1 : pas d'environnement de même nom existant sur ce snapshot
                 */
                if ($this->model->existsEnv($this->name, $this->dist, $this->section, $this->targetEnv) === false) {
                    /**
                     *  Suppression du lien symbolique (on ne sait jamais si il existe)
                     */
                    if (is_link(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $this->targetEnv)) {
                        unlink(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $this->targetEnv);
                    }

                    /**
                     *  Création du lien symbolique
                     */
                    exec('cd ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . ' && ln -sfn ' . $this->dateFormatted . '_' . $this->section . ' ' . $this->section . '_' . $this->targetEnv);

                    /**
                     *  Ajout de l'environnement en BDD
                     */
                    $this->model->addEnv($this->targetEnv, $this->targetDescription, $this->snapId);

                    /**
                     *  Clôture de l'étape en cours
                     */
                    $this->op->stepOK();

                /**
                 *  Cas 2 : Il y a déjà un environnement de repo du même nom pointant vers un snapshot.
                 */
                } else {
                    /**
                     *  D'abord on récupère l'Id de l'environnement déjà existant car on en aura besoin pour modifier son snapshot lié en base de données.
                     */
                    $actualEnvId = $this->model->getEnvIdFromRepoName($this->name, $this->dist, $this->section, $this->targetEnv);

                    /**
                     *  On supprime l'éventuel environnement de même nom pointant déjà vers un snapshot de ce repo (si il y en a un)
                     */
                    if (!empty($actualEnvId)) {
                        $this->model->deleteEnv($actualEnvId);
                    }

                    /**
                     *  Suppression du lien symbolique
                     */
                    if (is_link(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $this->targetEnv)) {
                        unlink(REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->section . '_' . $this->targetEnv);
                    }

                    /**
                     *  Création du nouveau lien symbolique, pointant vers le snapshot cible
                     */
                    exec('cd ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . ' && ln -sfn ' . $this->dateFormatted . '_' . $this->section . ' ' . $this->section . '_' . $this->targetEnv);

                    /**
                     *  Puis on déclare le nouvel environnement et on le fait pointer vers le snapshot précédemment créé
                     */
                    $this->model->addEnv($this->targetEnv, $this->targetDescription, $this->snapId);

                    /**
                     *  Clôture de l'étape en cours
                     */
                    $this->op->stepOK();
                }
            }

            $this->op->step('FINALISATION');

            /**
             *  8. Application des droits sur le repo/la section modifié
             */
            if ($this->packageType == 'rpm') {
                exec('find ' . REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->name . '/ -type f -exec chmod 0660 {} \;');
                exec('find ' . REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->name . '/ -type d -exec chmod 0770 {} \;');
            }

            if ($this->packageType == 'deb') {
                exec('find ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section . '/ -type f -exec chmod 0660 {} \;');
                exec('find ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section . '/ -type d -exec chmod 0770 {} \;');
            }

            /**
             *  Clôture de l'étape en cours
             */
            $this->op->stepOK();

            /**
             *  Nettoyage automatique des snapshots inutilisés
             */
            $snapshotsRemoved = $this->cleanSnapshots();

            if (!empty($snapshotsRemoved)) {
                $this->op->step('NETTOYAGE');
                $this->op->stepOK($snapshotsRemoved);
            }

            /**
             *  Nettoyage des repos inutilisés dans les groupes
             */
            $this->cleanGroups();

            /**
             *  Nettoyage du cache
             */
            \Controllers\Common::clearCache();

            /**
             *  Passage du status de l'opération en done
             */
            $this->op->setStatus('done');
        } catch (\Exception $e) {
            /**
             *  On transmets l'erreur à $this->op->stepError() qui va se charger de l'afficher en rouge dans le fichier de log
             */
            $this->op->stepError($e->getMessage());

            /**
             *  Passage du status de l'opération en erreur
             */
            $this->op->setStatus('error');
            $this->op->setError($e->getMessage());

            /**
             *  Cloture de l'opération
             */
            $this->op->closeOperation();

            /**
             *  Cas où cette fonction est lancée par une planification : la planif attend un retour, on lui renvoie false pour lui indiquer qu'il y a eu une erreur
             */
            return false;
        }

        /**
         *  Cloture de l'opération
         */
        $this->op->closeOperation();
    }

    /**
    *   Génération d'un tableau récapitulatif de l'opération
    *   Valide pour :
    *    - un nouveau repo/section
    *    - une mise à jour de repo/section
    *    - une reconstruction des métadonnées d'un repo/section
    */
    private function printDetails(string $title)
    {
        $this->op->step();

        ob_start();

        /**
         *  Affichage du tableau récapitulatif de l'opération
         */
        include(ROOT . '/templates/tables/op-new-update-reconstruct.inc.php');

        $this->op->stepWriteToLog(ob_get_clean());

        return true;
    }

    /**
     *   Récupération des paquets à partir d'un repo source
     *   $this->action = new ou update en fonction de si il s'agit d'un nouveau repo ou d'une mise à jour
     */
    private function getPackages()
    {
        ob_start();

        $this->op->step('RÉCUPÉRATION DES PAQUETS');

        /**
         *  Le type d'opération doit être renseigné pour cette fonction (soit "new" soit "update")
         */
        if (empty($this->op->getAction())) {
            throw new Exception("Type d'opération inconnu (vide)");
        }
        if ($this->op->getAction() != "new" and $this->op->getAction() != "update") {
            throw new Exception("Erreur : Type d'opération invalide");
        }

        //// VERIFICATIONS ////

        /**
         *  1 : Récupération du type du repo :
         *  Si il s'agit d'un repo de type 'local' alors on quitte à cette étape car on ne peut pas mettre à jour ce type de repo
         */
        if ($this->type == 'local') {
            throw new Exception("Il n'est pas possible de mettre à jour un snapshot de repo local");
        }

        /**
         *  2 : Debian seulement : Si la section est un miroir alors il faut récupérer l'URL complète de sa source si ce n'est pas déjà fait
         */
        if ($this->packageType == 'deb') {
            $this->getFullSource($this->source);
        }

        /**
         *  2. Si il s'agit d'un nouveau repo, on vérifie qu'un repo du même nom avec un ou plusieurs snapshots actifs n'existe pas déjà.
         *  Un repo peut exister et n'avoir aucun snapshot / environnement rattachés (il sera invisible dans la liste) mais dans ce cas cela ne doit pas empêcher la création d'un nouveau repo
         *
         *  Cas nouveau snapshot de repo :
         */
        if ($this->op->getAction() == "new") {
            if ($this->packageType == "rpm") {
                //if ($this->model->exists($this->name) === true) {
                if ($this->model->isActive($this->name) === true) {
                    throw new Exception('Un repo <span class="label-white">' . $this->name . '</span> existe déjà');
                }
            }
            if ($this->packageType == "deb") {
                // if ($this->model->sectionExists($this->name, $this->dist, $this->section) == true) {
                if ($this->model->isActive($this->name, $this->dist, $this->section) == true) {
                    throw new Exception('Un repo <span class="label-white">' . $this->name . ' ❯ ' . $this->dist . ' ❯ ' . $this->section . '</span> existe déjà');
                }
            }
        }

        /**
         *  Si il s'agit d'une mise à jour de snapshot de repo on vérifie que l'id du snapshot existe en base de données
         */
        if ($this->op->getAction() == "update") {
            /**
             *  Vérifie si le snapshot qu'on souhaite mettre à jour existe bien en base de données
             */
            if ($this->model->existsSnapId($this->snapId) === false) {
                throw new Exception("Le snapshot de repo spécifié n'existe pas");
            }

            /**
             *  On peut remettre à jour un snapshot dans la même journée, mais on ne peut pas mettre à jour un autre snapshot si un snapshot à la date du jour existe déjà
             *
             *  Du coup si la date du snapshot en cours de mise à jour == date du jour ($this->targetDate) alors on peut poursuivre l'opération
             *  Sinon on vérifie qu'un autre snapshot à la date du jour n'existe pas déjà, si c'est le cas on quitte
             */
            if ($this->model->getSnapDateById($this->snapId) != $this->targetDate) {
                if ($this->packageType == 'rpm') {
                    if ($this->model->existsRepoSnapDate($this->targetDate, $this->name) === true) {
                        throw new Exception('Un snapshot existe déjà en date du <span class="label-black">' . $this->targetDateFormatted . '</span>');
                    }
                }
                if ($this->packageType == 'deb') {
                    if ($this->model->existsRepoSnapDate($this->targetDate, $this->name, $this->dist, $this->section) === true) {
                        throw new Exception('Un snapshot existe déjà en date du <span class="label-black">' . $this->targetDateFormatted . '</span>');
                    }
                }
            }
        }

        $this->op->stepWriteToLog();

        //// TRAITEMENT ////

        /**
         *  2. Création du répertoire du repo/section
         */
        if ($this->packageType == "rpm") {
            $repoPath = REPOS_DIR . '/' . DATE_DMY . '_' . $this->name;
        }
        if ($this->packageType == "deb") {
            $repoPath = REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . DATE_DMY . '_' . $this->section;
        }

        /**
         *  Si le répertoire existe déjà, on le supprime
         */
        if (is_dir($repoPath)) {
            exec("rm -rf " . $repoPath);
        }
        /**
         *  Création du répertoire
         */
        if (!mkdir($repoPath, 0770, true)) {
            throw new Exception("la création du répertoire <b>" . $repoPath . "</b> a échouée");
        }

        /**
         *  3. Récupération des paquets
         */
        echo '<div class="hide getPackagesDiv"><pre>';
        $this->op->stepWriteToLog();

        // File descriptors for each subprocess. http://phptutorial.info/?proc-open
        /* $descriptors = [
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", "{$this->op->log->steplog}", "a") // stderr is a file to write to
        ];*/
        // https://gist.github.com/swichers/027d5ae903350cbd4af8
        $descriptors = array(
            // Must use php://stdin(out) in order to allow display of command output
            // and the user to interact with the process.
            0 => array('file', 'php://stdin', 'r'),
            1 => array('file', 'php://stdout', 'w'),
            2 => array('pipe', 'w'),
        );

        if ($this->packageType == "rpm") {
            /**
             *  Vérification de la présence d'un lock de yum (autre tâche yum déjà en cours)
             *  Si c'est le cas on attend que le lock soit libéré
             */
            // $lockFile = 0;

            /**
             *  Compte le nombre de répertoires de lock présents
             */
            // foreach (glob("/var/tmp/yum-*") as $lockFound) {
            //     $lockFile++;
            // }

            // if ($lockFile != 0) {
            //     echo 'En attente de la libération du lock yum...';

            //     $this->op->stepWriteToLog();

            //     /**
            //      *  On boucle tant que le lock est en place
            //      */
            //     while ($lockFile != 0) {
            //         sleep(2);

            //         $lockFile = 0;

            //         foreach (glob("/var/tmp/yum-*") as $lockFound) {
            //             $lockFile++;
            //         }
            //     }
            // }

            /**
             *  Détermine la version de yum-utils installée sur le système pour être en mesure de passer les bons paramètres à reposync
             */
            if (OS_FAMILY == 'Debian') {
                $yumUtilsVersion = exec("dpkg -l | grep yum-utils | awk '{print $3}'");
            }
            if (OS_FAMILY == 'Redhat') {
                $yumUtilsVersion = exec("rpm -qi yum-utils | grep 'Version' | awk '{print $3}'");
            }
            if (empty($yumUtilsVersion)) {
                throw new Exception('Impossible de déterminer la version de yum-utils installée.');
            }

            /**
             *  Cas où la version de yum-utils est 1.1.31 (généralement le cas sur CentOS7 et Debian)
             */
            if (preg_match('/1.1.31/', $yumUtilsVersion)) {
                $reposyncGlobalParams = '-l --norepopath';

                if ($this->getTargetGpgCheck() == "no") {
                    $reposyncGpgParam = '';
                } else {
                    $reposyncGpgParam = '--gpgcheck';
                }

            /**
             *  Cas où la version de yum-utils est 4.x (généralement le cas sur CentOS8 et 9)
             */
            } elseif (preg_match('/^4/', $yumUtilsVersion)) {
                $reposyncGlobalParams = '--norepopath';

                if ($this->targetGpgCheck == "no") {
                    $reposyncGpgParam = '--nogpgcheck';
                } else {
                    $reposyncGpgParam = '';
                }
            } else {
                throw new Exception('La version de yum-utils installée est incompatible ou invalide.');
            }

            /**
             *  Instanciation d'un nouveau Process reposync
             */
            $myprocess = new \Controllers\Process('/usr/bin/reposync --config=' . REPOMANAGER_YUM_DIR . '/repomanager.conf ' . $reposyncGlobalParams . ' ' . $reposyncGpgParam . ' --repoid=' . $this->source . ' -p "' . $repoPath . '/"');
        }

        if ($this->packageType == "deb") {
            /**
             *  debmirror global params
             */

            /**
             *  Case we want packages sources to be synced
             */
            if ($this->targetIncludeSource == 'yes') {
                $debmirrorGlobalParams = '--source';
            } else {
                $debmirrorGlobalParams = '--nosource';
            }

            /**
             *  Case we want some packages translations to be synced
             */
            if (!empty($this->targetIncludeTranslation)) {
                /**
                 *  Add --i18n then include each translation required
                 */
                $debmirrorGlobalParams .= ' --i18n';

                foreach($this->targetIncludeTranslation as $translation) {
                    $debmirrorGlobalParams .= ' --include="Translation-' . $translation . '.*"';
                }
            }

            /**
             *  Case we don't want GPG signature check
             */
            if ($this->targetGpgCheck == "no") {
                $debmirrorGpgParam = '--no-check-gpg';
            } else {
                $debmirrorGpgParam = '--check-gpg --keyring=' . GPGHOME . '/trustedkeys.gpg';
            }

            /**
             *  Instanciation d'un nouveau Process debmirror
             */
            $myprocess = new \Controllers\Process('/usr/bin/debmirror ' . $debmirrorGpgParam . ' ' . $debmirrorGlobalParams . ' --passive --method=http --rsync-extra=none --host="' . $this->hostUrl . '" --root="' . $this->rootUrl . '" --dist="' . $this->dist . '" --section="' . $this->section . '" --arch="amd64" ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . DATE_DMY . '_' . $this->section . ' --getcontents --progress --postcleanup');
        }

        /**
         *  Exécution
         */
        $myprocess->exec();

        /**
         *  Récupération du pid du process lancé
         *  Puis écriture du pid de reposync/debmirror (lancé par proc_open) dans le fichier PID principal, ceci afin qu'il puisse être killé si l'utilisateur le souhaite
         */
        file_put_contents(PID_DIR . "/{$this->op->log->pid}.pid", 'SUBPID="' . $myprocess->getPid() . '"' . PHP_EOL, FILE_APPEND);

        /**
         *  Affichage de l'output du process en continue dans un fichier
         */
        $myprocess->getOutput($this->op->log->steplog);

        echo '</pre></div>';

        $this->op->stepWriteToLog();

        /*
         *  Si il y a un pb avec reposync, celui-ci renvoie systématiquement le code 0 même si il est en erreur.
         *  Du coup on vérifie directement dans l'output du programme qu'il n'y a pas eu de message d'erreur et si c'est le cas alors on incrémente
         */
        $reposyncError = 0;
        if (preg_match('/due to missing GPG key/', file_get_contents($this->op->log->steplog))) {
            ++$reposyncError;
        }

        /**
         *  Si l'exécution de reposync ou debmirror s'est mal terminée, on supprime ce qui a été fait et on quitte avec un message d'erreur
         */
        if ($myprocess->getReturnCode() != 0 or $reposyncError != 0) {
            /**
             *  Suppression de ce qui a été fait :
             */
            if ($this->packageType == "rpm") {
                exec('rm -rf "' . REPOS_DIR . '/' . DATE_DMY . '_' . $this->name . '"');
            }
            if ($this->packageType == "deb") {
                exec('rm -rf "' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . DATE_DMY . '_' . $this->section . '"');
            }

            throw new Exception('erreur lors de la récupération des paquets');
        }

        $this->op->stepOK();

        return true;
    }

    /**
     *  Signature des paquets (Redhat) avec GPG
     *  Opération exclusive à Redhat car sous Debian c'est le fichier Release du repo qu'on signe
     */
    private function signPackages()
    {
        $warning = 0;

        ob_start();

        /**
         *  Signature des paquets du repo avec GPG
         *  Redhat seulement car sur Debian c'est le fichier Release qui est signé lors de la création du repo
         */
        if ($this->packageType == "rpm" and $this->targetGpgResign == "yes") {
            $this->op->step('SIGNATURE DES PAQUETS (GPG)');

            echo '<div class="hide signRepoDiv"><pre>';
            $this->op->stepWriteToLog();

            /**
             *  Récupération de tous les fichiers RPMs de manière récursive
             */
            $dir = new \RecursiveDirectoryIterator(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/');
            $rpmFiles = new \RecursiveIteratorIterator($dir);
            $signErrors = 0;

            /**
             *  On traite chaque fichier trouvé
             */
            foreach ($rpmFiles as $rpmFile) {
                /**
                 *  On traite uniquement si le fichier a bien une extension .rpm
                 */
                if ($rpmFile->getExtension() == 'rpm') {
                    /**
                     *  Cas où on souhaite utiliser rpmresign pour signer
                     */
                    if ($this->rpmSignMethod == 'rpmresign') {
                        if (file_exists("/usr/bin/rpmresign")) {
                            /**
                             *  Instanciation d'un nouveau Process
                             */
                            $myprocess = new \Controllers\Process('/usr/bin/rpmresign --path "' . GPGHOME . '" --name "' . RPM_SIGN_GPG_KEYID . '" --passwordfile "' . PASSPHRASE_FILE . '" ' . $rpmFile->getPath() . '/' . $rpmFile->getFileName());
                        } else {
                            throw new Exception("Le programme rpmresign est introuvable sur le système.");
                        }
                    }

                    /**
                     *  Cas où on souhaite utiliser nativement gpg pour signer, avec rpmsign (équivalent rpm --sign)
                     */
                    if ($this->rpmSignMethod == 'rpmsign') {
                        /**
                         *  On a besoin d'un fichier de macros gpg, on signe uniquement si le fichier de macros est présent, sinon on retourne une erreur
                         */
                        if (file_exists(MACROS_FILE)) {
                            /**
                             *  Instanciation d'un nouveau Process
                             */
                            $myprocess = new \Controllers\Process('/usr/bin/rpmsign --macros=' . MACROS_FILE . ' --addsign ' . $rpmFile->getPath() . '/' . $rpmFile->getFileName(), array('GPG_TTY' => '$(tty)'));
                        } else {
                            throw new Exception("Le fichier de macros pour rpm n'est pas généré.");
                        }
                    }

                    /**
                     *  Exécution
                     */
                    $myprocess->exec();

                    /**
                     *  Récupération du pid du process lancé
                     *  Puis écriture du pid de reposync/debmirror (lancé par proc_open) dans le fichier PID principal, ceci afin qu'il puisse être killé si l'utilisateur le souhaite
                     */
                    file_put_contents(PID_DIR . "/{$this->op->log->pid}.pid", 'SUBPID="' . $myprocess->getPid() . '"' . PHP_EOL, FILE_APPEND);

                    /**
                     *  Affichage de l'output du process en continue dans un fichier
                     */
                    $myprocess->getOutput($this->op->log->steplog);

                    /**
                     *  Si la signature du paquet en cours s'est mal terminée, on incrémente $signErrors pour
                     *  indiquer une erreur et on sort de la boucle pour ne pas traiter le paquet suivant
                     */
                    if ($myprocess->getReturnCode() != 0) {
                        $signErrors++;
                        break;
                    }
                }
            }
            echo '</pre></div>';

            $this->op->stepWriteToLog();

            /**
             *  A vérifier car depuis l'écriture de la class Process, les erreurs semblent mieux gérées :
             *
             *  Si il y a un pb lors de la signature, celui-ci renvoie systématiquement le code 0 même si il est en erreur.
             *  Du coup on vérifie directement dans l'output du programme qu'il n'y a pas eu de message d'erreur et si c'est le cas alors on incrémente $return
             */
            if (preg_match('/gpg: signing failed/', file_get_contents($this->op->log->steplog))) {
                ++$signErrors;
            }
            if (preg_match('/No secret key/', file_get_contents($this->op->log->steplog))) {
                ++$signErrors;
            }
            if (preg_match('/error: gpg/', file_get_contents($this->op->log->steplog))) {
                ++$signErrors;
            }
            if (preg_match("/Can't resign/", file_get_contents($this->op->log->steplog))) {
                ++$signErrors;
            }
            /**
             *  Cas particulier, on affichera un warning si le message suivant a été détecté dans les logs
             */
            if (preg_match("/gpg: WARNING:/", file_get_contents($this->op->log->steplog))) {
                ++$warning;
            }
            if (preg_match("/warning:/", file_get_contents($this->op->log->steplog))) {
                ++$warning;
            }

            if ($warning != 0) {
                $this->op->stepWarning();
            }

            if ($signErrors != 0) {
                /**
                 *  Si l'action est reconstruct alors on ne supprime pas ce qui a été fait (sinon ça supprime le repo!)
                 */
                if ($this->op->getAction() != "reconstruct") {
                    /**
                     *  Suppression de ce qui a été fait :
                     */
                    exec('rm -rf "' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '"');
                }

                throw new Exception('la signature des paquets a échouée');
            }

            $this->op->stepOK();
        }

        return true;
    }

    /**
     *  Création des metadata du repo (Redhat) et des liens symboliques (environnements)
     */
    private function createRepo()
    {
        $createRepoErrors = 0;
        $repreproErrors = 0;

        ob_start();

        $this->op->step('CRÉATION DU REPO');

        echo '<div class="hide createRepoDiv"><pre>';

        $this->op->stepWriteToLog();

        if ($this->packageType == "rpm") {
            /**
             *  Si un répertoire my_uploaded_packages existe, alors on déplace ses éventuels packages
             */
            if (is_dir(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/my_uploaded_packages/')) {
                /**
                 *  Création du répertoire my_integrated_packages qui intègrera les paquets intégrés au repo
                 */
                if (!is_dir(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/my_integrated_packages/')) {
                    mkdir(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/my_integrated_packages/', 0770, true);
                }

                /**
                 *  Déplacement des paquets dans my_uploaded_packages vers my_integrated_packages
                 */
                if (!\Controllers\Common::dirIsEmpty(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/my_uploaded_packages/')) {
                    exec('mv -f ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/my_uploaded_packages/*.rpm ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/my_integrated_packages/');
                }

                /**
                 *  Suppression de my_uploaded_packages
                 */
                rmdir(REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/my_uploaded_packages/');
            }

            /**
             *  Instanciation d'un nouveau Process
             */
            $myprocess = new \Controllers\Process('createrepo -v ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/');

            /**
             *  Exécution
             */
            $myprocess->exec();

            /**
             *  Récupération du pid du process lancé
             *  Puis écriture du pid de reposync/debmirror (lancé par proc_open) dans le fichier PID principal, ceci afin qu'il puisse être killé si l'utilisateur le souhaite
             */
            file_put_contents(PID_DIR . "/{$this->op->log->pid}.pid", 'SUBPID="' . $myprocess->getPid() . '"' . PHP_EOL, FILE_APPEND);

            /**
             *  Affichage de l'output du process en continue dans un fichier
             */
            $myprocess->getOutput($this->op->log->steplog);

            if ($myprocess->getReturnCode() != 0) {
                $createRepoErrors++;
            }

            echo '</pre></div>';

            $this->op->stepWriteToLog();
        }

        if ($this->packageType == "deb") {
            /**
             *  On va créer et utiliser un répertoire temporaire pour travailler
             */
            $TMP_DIR = REPOS_DIR . "/{$this->op->log->pid}_deb_packages";

            if (!mkdir($TMP_DIR, 0770, true)) {
                throw new Exception("impossible de créer le répertoire temporaire <b>" . $TMP_DIR . '</b>');
            }

            $this->op->stepWriteToLog();

            /**
             *  Chemin complet vers la section qu'on est en train de créer
             */
            $sectionPath = REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->targetDateFormatted . '_' . $this->section;

            if (!is_dir($sectionPath)) {
                throw new Exception("le répertoire du repo n'existe pas");
            }
            if (!is_dir($TMP_DIR)) {
                throw new Exception("le répertoire temporaire n'existe pas");
            }

            /**
             *  Récupération de tous les fichiers DEBs de manière récursive
             *  On va déplacer ces fichiers deb vers le répertoire temporaire
             */
            $dir = new \RecursiveDirectoryIterator($sectionPath . '/');
            $debFiles = new \RecursiveIteratorIterator($dir);

            /**
             *  On déplace chaque fichier deb trouvé
             */
            foreach ($debFiles as $debFile) {
                /**
                 *  On déplace uniquement si le fichier a bien une extension .deb
                 */
                if ($debFile->getExtension() == 'deb') {
                    rename($debFile->getPath() . '/' . $debFile->getFileName(), $TMP_DIR . '/' . $debFile->getFileName());
                }
            }

            /**
             *  Après avoir déplacé tous les paquets on peut supprimer tout le contenu de la section
             */
            exec("rm -rf $sectionPath/*");

            /**
             *  Création du répertoire 'conf' et des fichiers de conf du repo
             */
            if (!is_dir($sectionPath . '/conf')) {
                if (!mkdir($sectionPath . '/conf', 0770, true)) {
                    throw new Exception("impossible de créer le répertoire de configuration du repo <b>$sectionPath/conf</b>");
                }
            }

            /**
             *  Création du fichier "distributions"
             *  Son contenu sera différent suivant si on a choisi de chiffrer ou non le repo
             */
            if ($this->targetGpgResign == "yes") {
                $file_distributions_content = 'Origin: Repo ' . $this->name . ' sur ' . WWW_HOSTNAME . PHP_EOL . 'Label: apt repository' . PHP_EOL . 'Codename: ' . $this->dist . PHP_EOL . 'Architectures: i386 amd64' . PHP_EOL . 'Components: ' . $this->section . PHP_EOL . 'Description: Repo ' . $this->name . ', miroir du repo ' . $this->source . ', distribution ' . $this->dist . ', section ' . $this->section . PHP_EOL . 'SignWith: ' . DEB_SIGN_GPG_KEYID . PHP_EOL . 'Pull: ' . $this->section;
            } else {
                $file_distributions_content = 'Origin: Repo ' . $this->name . ' sur ' . WWW_HOSTNAME . PHP_EOL . 'Label: apt repository' . PHP_EOL . 'Codename: ' . $this->dist . PHP_EOL . 'Architectures: i386 amd64' . PHP_EOL . 'Components: ' . $this->section . PHP_EOL . 'Description: Repo ' . $this->name . ', miroir du repo ' . $this->source . ', distribution ' . $this->dist . ', section ' . $this->section . PHP_EOL . 'Pull: ' . $this->section;
            }

            if (!file_put_contents($sectionPath . '/conf/distributions', $file_distributions_content . PHP_EOL)) {
                throw new Exception("impossible de créer le fichier de configuration du repo <b>$sectionPath/conf/distributions</b>");
            }

            /**
             *  Création du fichier "options"
             *  Son contenu sera différent suivant si on a choisi de chiffrer ou non le repo
             */
            if ($this->targetGpgResign == "yes") {
                $file_options_content = "basedir $sectionPath" . PHP_EOL . 'ask-passphrase';
            } else {
                $file_options_content = "basedir $sectionPath";
            }

            if (!file_put_contents($sectionPath . '/conf/options', $file_options_content . PHP_EOL)) {
                throw new Exception("impossible de créer le fichier de configuration du repo <b>$sectionPath/conf/options</b>");
            }

            /**
             *  Si le répertoire temporaire ne contient aucun paquet (càd si le repo est vide) alors on ne traite pas et on incrémente $return afin d'afficher une erreur.
             */
            if (\Controllers\Common::dirIsEmpty($TMP_DIR) === true) {
                echo "Il n'y a aucun paquet dans ce repo";
                echo '</pre></div>';

                $return = 1;

            /**
             *  Sinon on peut traiter
             */
            } else {
                /**
                 *  Récupération de tous les fichiers DEBs dans le répertoire temporaire
                 *  On va déplacer ces fichiers deb vers le répertoire temporaire
                 */
                $dir = new \RecursiveDirectoryIterator($TMP_DIR . '/');
                $debFiles = new \RecursiveIteratorIterator($dir);

                /**
                 *  Chaque fichier deb est ajouté au repo
                 */
                foreach ($debFiles as $debFile) {
                    /**
                     *  On déplace uniquement si le fichier a bien une extension .deb
                     */
                    if ($debFile->getExtension() == 'deb') {
                        /**
                         *  Cas où on signe le repo
                         */
                        if ($this->targetGpgResign == "yes") {
                            /**
                             *  Instanciation d'un nouveau Process
                             */
                            $myprocess = new \Controllers\Process('/usr/bin/reprepro --basedir ' . $sectionPath . '/ --gnupghome ' . GPGHOME . ' includedeb ' . $this->dist . ' ' . $debFile->getPath() . '/' . $debFile->getFileName());
                        } else {
                            /**
                             *  Instanciation d'un nouveau Process
                             */
                            $myprocess = new \Controllers\Process('/usr/bin/reprepro --basedir ' . $sectionPath . '/ includedeb ' . $this->dist . ' ' . $debFile->getPath() . '/' . $debFile->getFileName());
                        }

                        /**
                         *  Exécution
                         */
                        $myprocess->exec();

                        /**
                         *  Récupération du pid du process lancé
                         *  Puis écriture du pid de reposync/debmirror (lancé par proc_open) dans le fichier PID principal, ceci afin qu'il puisse être killé si l'utilisateur le souhaite
                         */
                        file_put_contents(PID_DIR . "/{$this->op->log->pid}.pid", 'SUBPID="' . $myprocess->getPid() . '"' . PHP_EOL, FILE_APPEND);

                        /**
                         *  Affichage de l'output du process en continue dans un fichier
                         */
                        $myprocess->getOutput($this->op->log->steplog);

                        /**
                         *  Si la signature du paquet en cours s'est mal terminée, on incrémente $signErrors pour
                         *  indiquer une erreur et on sort de la boucle pour ne pas traiter le paquet suivant
                         */
                        if ($myprocess->getReturnCode() != 0) {
                            $repreproErrors++;
                            break;
                        }
                    }
                }

                echo '</pre></div>';

                $this->op->stepWriteToLog();

                /**
                 *  Suppression du répertoire temporaire
                 */
                if ($this->packageType == "deb" and is_dir($TMP_DIR)) {
                    exec("rm -rf '$TMP_DIR'");
                }
            }
        }

        if ($createRepoErrors != 0 or $repreproErrors != 0) {
            /**
             *  Suppression de ce qui a été fait :
             */
            if ($this->op->getAction() != "reconstruct") {
                if ($this->packageType == "rpm") {
                    exec('rm -rf "' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '"');
                }
                if ($this->packageType == "deb") {
                    exec('rm -rf "' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->targetDateFormatted . '_' . $this->section . '"');
                }
            }

            throw new Exception('la création du repo a échouée');
        }

        $this->op->stepWriteToLog();

        /**
         *  Création du lien symbolique (environnement)
         *  Uniquement si l'utilisateur a spécifié de faire pointer un environnement sur le snapshot créé
         */
        if ($this->op->getAction() == "new" or $this->op->getAction() == "update") {
            if (!empty($this->targetEnv)) {
                if ($this->packageType == "rpm") {
                    exec('cd ' . REPOS_DIR . '/ && ln -sfn ' . $this->targetDateFormatted . '_' . $this->name . ' ' . $this->name . '_' . $this->targetEnv, $output, $result);
                }
                if ($this->packageType == "deb") {
                    exec('cd ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/ && ln -sfn ' . $this->targetDateFormatted . '_' . $this->section . ' ' . $this->section . '_' . $this->targetEnv, $output, $result);
                }
                if ($result != 0) {
                    throw new Exception('la finalisation du repo a échouée');
                }
            }
        }

        $this->op->stepOK();

        return true;
    }

    /**
    *   Finalisation du repo : ajout en base de données et application des droits
    */
    private function finalize()
    {
        ob_start();

        $this->op->step('FINALISATION');

        /**
         *  Le type d'opération doit être renseigné pour cette fonction (soit "new" soit "update")
         */
        if (empty($this->op->getAction())) {
            throw new Exception("type d'opération inconnu (vide)");
        }
        if ($this->op->getAction() != "new" and $this->op->getAction() != "update") {
            throw new Exception("type d'opération invalide");
        }

        /**
         *  1. Mise à jour de la BDD
         *  - Si il s'agit d'un nouveau repo alors on l'ajoute en base de données
         */
        if ($this->op->getAction() == "new") {
            /**
             *  Si actuellement aucun repo rpm de ce nom n'existe en base de données alors on l'ajoute
             */
            if ($this->packageType == "rpm") {
                if ($this->model->exists($this->name) === false) {
                    $this->model->add($this->getSource(), 'rpm', $this->name);

                    /**
                     *  L'Id du repo devient alors l'Id de la dernière ligne insérée en base de données
                     */
                    $this->repoId = $this->model->getLastInsertRowID();

                /**
                 *  Sinon si un repo de même nom existe, on récupère son Id en base de données
                 */
                } else {
                    $this->repoId = $this->model->getIdByName($this->name, '', '');
                }
            }

            /**
             *  Si actuellement aucun repo deb de ce nom n'existe en base de données alors on l'ajoute
             */
            if ($this->packageType == "deb") {
                if ($this->model->exists($this->name, $this->dist, $this->section) === false) {
                    $this->model->add($this->getSource(), 'deb', $this->name, $this->dist, $this->section);

                    /**
                     *  L'Id du repo devient alors l'Id de la dernière ligne insérée en base de données
                     */
                    $this->repoId = $this->model->getLastInsertRowID();

                /**
                 *  Sinon si un repo de même nom existe, on récupère son Id en base de données
                 */
                } else {
                    $this->repoId = $this->model->getIdByName($this->name, $this->dist, $this->section);
                }
            }

            /**
             *  Ajout du snapshot en base de données
             */
            $this->model->addSnap($this->targetDate, $this->targetTime, $this->targetGpgResign, $this->type, 'active', $this->repoId);

            /**
             *  Récupération de l'Id du snapshot ajouté précédemment
             */
            $this->setSnapId($this->model->getLastInsertRowID());

            /**
             *  Ajout de l'env en base de données, si un environnement a été spécifié par l'utilisateur
             */
            if (!empty($this->targetEnv)) {
                $this->model->addEnv($this->targetEnv, $this->targetDescription, $this->snapId);
            }
        }

        if ($this->op->getAction() == "update") {
            /**
             *  Dans le cas où la nouvelle date du snapshot est la même que l'ancienne
             *  (cas où on remet à jour le même snapshot le même jour) alors on met seulement à jour quelques
             *  informations de base du repo en base de données et rien d'autre.
             */
            if ($this->targetDate == $this->date) {
                /**
                 *  Mise à jour de l'état de la signature GPG
                 */
                $this->model->snapSetSigned($this->snapId, $this->targetGpgResign);

                /**
                 *  Mise à jour de la date
                 */
                $this->model->snapSetDate($this->snapId, date('Y-m-d'));

                /**
                 *  Mise à jour de l'heure
                 */
                $this->model->snapSetTime($this->snapId, date('H:i'));

            /**
             *  Sinon on ajoute un nouveau snapshot en base de données à la date du jour
             */
            } else {
                /**
                 *  Cas où un nouveau snapshot a été créé, on l'ajoute en base de données
                 */
                $this->model->addSnap($this->targetDate, $this->targetTime, $this->targetGpgResign, 'mirror', 'active', $this->repoId);

                /**
                 *  On récupère l'Id du snapshot précédemment créé
                 *  Et on peut du coup définir que snapId = cet Id
                 */
                $this->snapId = $this->model->getLastInsertRowID();
            }
        }

        /**
         *  Si l'utilisateur a renseigné un environnement à faire pointer sur le snapshot créé
         */
        if (!empty($this->targetEnv)) {

            /**
             *  Si l'utilisateur n'a précisé aucune description alors on récupère celle actuellement en place sur l'environnement de même nom (si l'environnement existe et si il possède une description)
             */
            if (empty($this->targetDescription)) {
                if ($this->packageType == 'rpm') {
                    $actualDescription = $this->model->getDescriptionByName($this->name, '', '', $this->targetEnv);
                }
                if ($this->packageType == 'deb') {
                    $actualDescription = $this->model->getDescriptionByName($this->name, $this->dist, $this->section, $this->targetEnv);
                }

                /**
                 *  Si la description récupérée est vide alors la description restera vide
                 */
                if (!empty($actualDescription)) {
                    $this->targetDescription = $actualDescription;
                } else {
                    $this->targetDescription = '';
                }
            }

            /**
             *  On récupère l'Id de l'environnement actuellement an place (si il y en a un)
             */
            $actualEnvId = $this->model->getEnvIdFromRepoName($this->name, $this->dist, $this->section, $this->targetEnv);

            /**
             *  On supprime l'éventuel environnement de même nom pointant déjà vers un snapshot de ce repo (si il y en a un)
             */
            if (!empty($actualEnvId)) {
                $this->model->deleteEnv($actualEnvId);
            }

            /**
             *  Puis on déclare le nouvel environnement et on le fait pointer vers le snapshot précédemment créé
             */
            $this->model->addEnv($this->targetEnv, $this->targetDescription, $this->snapId);
        }

        /**
         *  3. Application des droits sur le snapshot créé
         */
        if ($this->packageType == "rpm") {
            exec('find ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/ -type f -exec chmod 0660 {} \;');
            exec('find ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name . '/ -type d -exec chmod 0770 {} \;');
            exec('chown -R ' . WWW_USER . ':repomanager ' . REPOS_DIR . '/' . $this->targetDateFormatted . '_' . $this->name);
            /*if [ $? -ne "0" ];then
                echo "<br><span class=\"redtext\">Erreur :</span>l'application des permissions sur le repo <b>$this->name</b> a échoué"
            fi*/
        }
        if ($this->packageType == "deb") {
            exec('find ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->targetDateFormatted . '_' . $this->section . '/ -type f -exec chmod 0660 {} \;');
            exec('find ' . REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->targetDateFormatted . '_' . $this->section . '/ -type d -exec chmod 0770 {} \;');
            exec('chown -R ' . WWW_USER . ':repomanager ' . REPOS_DIR . '/' . $this->name);
            /*if [ $? -ne "0" ];then
                echo "<br><span class=\"redtext\">Erreur :</span>l'application des permissions sur la section <b>$this->section</b> a échoué"
            fi*/
        }

        $this->op->stepOK();

        /**
         *  Ajout du repo à un groupe si un groupe a été renseigné.
         *  Uniquement si il s'agit d'un nouveau repo/section ($this->op->getAction() = new)
         */
        if ($this->op->getAction() == 'new' and !empty($this->targetGroup)) {
            $this->op->step('AJOUT A UN GROUPE');
            $this->addRepoIdToGroup($this->repoId, $this->targetGroup);
            $this->op->stepOK();
        }

        /**
         *  Nettoyage automatique des snapshots inutilisés
         */
        $snapshotsRemoved = $this->cleanSnapshots();

        if (!empty($snapshotsRemoved)) {
            $this->op->step('NETTOYAGE');
            $this->op->stepOK($snapshotsRemoved);
        }

        /**
         *  Nettoyage des repos inutilisés dans les groupes
         */
        $this->cleanGroups();

        return true;
    }

    /**
     *  Ajouter / supprimer des repos dans un groupe
     */
    public function addReposIdToGroup(array $reposId = null, string $groupName)
    {
        /**
         *  On aura besoin d'un objet Group()
         */
        $mygroup = new \Controllers\Group('repo');
        $groupId = $mygroup->getIdByName($groupName);

        if (!empty($reposId)) {
            foreach ($reposId as $repoId) {
                /**
                 *  On vérifie que l'Id de repo spécifié existe en base de données
                 */
                if ($this->model->existsId($repoId) === false) {
                    throw new Exception("L'Id de repo $repoId spécifié n'existe pas");
                }

                $repo = $this->getAllById($repoId);

                $repoName = $this->name;
                $repoDist = $this->dist;
                $repoSection = $this->section;

                /**
                 *  Ajout du repo au groupe
                 */
                $this->model->addToGroup($repoId, $groupId);
            }
        }

        /**
         *  3. On récupère la liste des repos actuellement dans le groupe afin de supprimer ceux qui n'ont pas été sélectionnés
         */
        $actualReposMembers = $this->model->getReposGroupMembers($groupId);

        /**
         *  4. Parmis cette liste on ne récupère que les Id des repos actuellement membres
         */
        $actualReposId = array();

        foreach ($actualReposMembers as $actualRepoMember) {
            $actualReposId[] = $actualRepoMember['repoId'];
        }

        /**
         *  5. Enfin, on supprime tous les Id de repos actuellement membres qui n'ont pas été spécifiés par l'utilisateur
         */
        foreach ($actualReposId as $actualRepoId) {
            if (!in_array($actualRepoId, $reposId)) {
                $this->model->removeFromGroup($actualRepoId, $groupId);
            }
        }

        \Models\History::set($_SESSION['username'], 'Modifications des repos membres du groupe <span class="label-white">' . $groupName . '</span>', 'success');

        \Controllers\Common::clearCache();
    }

    /**
     *  Ajouter un repo à un groupe par Id
     */
    public function addRepoIdToGroup(string $repoId, string $groupName)
    {
        /**
         *  On aura besoin d'un objet Group()
         */
        $mygroup = new \Controllers\Group('repo');
        $groupId = $mygroup->getIdByName($groupName);

        $this->model->addToGroup($repoId, $groupId);
    }

    public function envSetDescription(string $envId, string $description)
    {
        return $this->model->envSetDescription($envId, $description);
    }

    /**
     *  Génère un <select> contenant la liste des repos par groupe
     */
    public function selectRepoByGroup($groupName)
    {
        /**
         *  On aura besoin d'un objet Group()
         */
        $mygroup = new \Controllers\Group('repo');

        /**
         *  On vérifie que le groupe existe
         */
        if ($mygroup->exists($groupName) === false) {
            throw new Exception("Le groupe $groupName n'existe pas");
        }

        /**
         *  Récupération de l'Id du groupe en base de données
         */
        $groupId = $mygroup->getIdByName($groupName);

        /**
         *  Récupération de tous les repos membres de ce groupe
         */
        $reposIn = $this->model->getReposGroupMembers($groupId);

        /**
         *  Récupération de tous les repos membres d'aucun groupe
         */
        $reposNotIn = $this->model->getReposNotMembersOfAnyGroup();

        echo '<select class="reposSelectList" groupname="' . $groupName . '" name="groupAddRepoName[]" multiple>';

            /**
             *  Les repos membres du groupe seront par défaut sélectionnés dans la liste
             */
        if (!empty($reposIn)) {
            foreach ($reposIn as $repo) {
                $repoId = $repo['repoId'];
                $repoName = $repo['Name'];
                $repoDist = $repo['Dist'];
                $repoSection = $repo['Section'];
                $this->packageType = $repo['Package_type'];

                if ($this->packageType == "rpm") {
                    echo '<option value="' . $repoId . '" selected>' . $repoName . '</option>';
                }
                if ($this->packageType == "deb") {
                    echo '<option value="' . $repoId . '" selected>' . $repoName . ' ❯ ' . $repoDist . ' ❯ ' . $repoSection . '</option>';
                }
            }
        }

            /**
             *  Les repos non-membres du groupe seront dé-sélectionnés dans la liste
             */
        if (!empty($reposNotIn)) {
            foreach ($reposNotIn as $repo) {
                $repoId = $repo['repoId'];
                $repoName = $repo['Name'];
                $repoDist = $repo['Dist'];
                $repoSection = $repo['Section'];
                $this->packageType = $repo['Package_type'];

                if ($this->packageType == "rpm") {
                    echo '<option value="' . $repoId . '">' . $repoName . '</option>';
                }
                if ($this->packageType == "deb") {
                    echo '<option value="' . $repoId . '">' . $repoName . ' ❯ ' . $repoDist . ' ❯ ' . $repoSection . '</option>';
                }
            }
        }

        echo '</select>';

        unset($mygroup, $reposIn, $reposNotIn);
    }

    /**
     *  Nettoyage des snapshots inutilisés
     */
    public function cleanSnapshots()
    {
        $returnOutput = '';
        $removedSnaps = array();
        $removedSnapsError = array();
        $removedSnapsFinalArray = array();

        /**
         *  1. Si le nettoyage automatique n'est pas autorisé alors on quitte la fonction
         */
        if (ALLOW_AUTODELETE_ARCHIVED_REPOS != "yes") {
            return;
        }

        if (!is_int(RETENTION) or RETENTION < 0) {
            return;
        }

        /**
         *  On récupère tous les Id et noms de repos
         */
        $repos = $this->model->listNameOnly(true);

        /**
         *  Pour chaque repo on récupère la liste des snapshots inutilisés (snapshots qui n'ont aucun environnement actif) et on les traite si il y en a
         */
        if (!empty($repos)) {
            foreach ($repos as $repo) {
                $repoId = $repo['Id'];
                $repoName = $repo['Name'];
                if (!empty($repo['Dist'])) {
                    $repoDist = $repo['Dist'];
                }
                if (!empty($repo['Section'])) {
                    $repoSection = $repo['Section'];
                }
                $packageType = $repo['Package_type'];

                /**
                 *  Récupération des snapshots inutilisés de ce repo
                 */
                $unusedSnapshots = $this->model->getUnunsedSnapshot($repoId, RETENTION);

                /**
                 *  Si il y a des snapshots inutilisés alors on traite
                 */
                if (!empty($unusedSnapshots)) {
                    foreach ($unusedSnapshots as $unusedSnapshot) {
                        $snapId = $unusedSnapshot['snapId'];
                        $snapDate = $unusedSnapshot['Date'];
                        $snapDateFormatted = DateTime::createFromFormat('Y-m-d', $snapDate)->format('d-m-Y');
                        $result = '';

                        if ($packageType == 'rpm') {
                            if (is_dir(REPOS_DIR . '/' . $snapDateFormatted . '_' . $repoName)) {
                                exec('rm -rf ' . REPOS_DIR . '/' . $snapDateFormatted . '_' . $repoName, $output, $result);
                            }
                        }
                        if ($packageType == 'deb') {
                            if (is_dir(REPOS_DIR . '/' . $repoName . '/' . $repoDist . '/' . $snapDateFormatted . '_' . $repoSection)) {
                                exec('rm -rf ' . REPOS_DIR . '/' . $repoName . '/' . $repoDist . '/' . $snapDateFormatted . '_' . $repoSection, $output, $result);
                            }
                        }

                        if (is_numeric($result)) {
                            /**
                             *  Cas où le snapshot a été supprimé avec succès
                             */
                            if ($result == 0) {
                                if ($packageType == 'rpm') {
                                    $removedSnaps[] = 'Le snapshot <span class="label-white">' . $repoName . '</span>⟶<span class="label-black">' . $snapDateFormatted . '</span> a été supprimé';
                                }
                                if ($packageType == 'deb') {
                                    $removedSnaps[] = 'Le snapshot <span class="label-white">' . $repoName . ' ❯ ' . $repoDist . ' ❯ ' . $repoSection . '</span>⟶<span class="label-black">' . $snapDateFormatted . '</span> a été supprimé';
                                }

                                /**
                                 *  Changement du status en base de données
                                 */
                                $this->model->snapSetStatus($snapId, 'deleted');

                            /**
                             *  Cas où il y a eu une erreur lors de la suppression
                             */
                            } else {
                                if ($packageType == 'rpm') {
                                    $removedSnapsError[] = 'Erreur lors de la suppression automatique du snapshot <span class="label-white">' . $repoName . '</span>⟶<span class="label-black">' . $snapDateFormatted . '</span>';
                                }
                                if ($packageType == 'deb') {
                                    $removedSnapsError[] = 'Erreur lors de la suppression automatique du snapshot <span class="label-white">' . $repoName . ' ❯ ' . $repoDist . ' ❯ ' . $repoSection . '</span>⟶<span class="label-black">' . $snapDateFormatted . '</span>';
                                }

                                /**
                                 *  On passe au snapshot suivant (et donc on ne change pas le status du snapshot en base de données puisqu'il n'a pas pu être supprimé)
                                 */
                                continue;
                            }
                        }
                    }
                }
            }

            /**
             *  On merge les deux array contenant des messages de suppression ou d'erreur
             */
            if (!empty($removedSnapsError)) {
                $removedSnapsFinalArray = array_merge($removedSnapsFinalArray, $removedSnapsError);
            }

            if (!empty($removedSnaps)) {
                $removedSnapsFinalArray = array_merge($removedSnapsFinalArray, $removedSnaps);
            }

            /**
             *  Si des messages ont été récupérés alors on forge le message qui sera affiché dans le log
             */
            if (!empty($removedSnapsFinalArray)) {
                foreach ($removedSnapsFinalArray as $removedSnap) {
                    $returnOutput .= $removedSnap . '<br>';
                }
            }
        }

        return $returnOutput;
    }

    /**
     *  Retire des groupes les repos qui n'ont plus aucun snapshot actif
     */
    public function cleanGroups()
    {
        /**
         *  D'abord on récupère tous les les Id de repos
         */
        $repoIds = $this->model->getAllRepoId();

        /**
         *  Pour chaque Id on regarde si il y a au moins 1 snapshot actif
         */
        foreach ($repoIds as $repoId) {
            $id = $repoId['Id'];
            $activeSnapshots = $this->model->getSnapByRepoId($id, 'active');

            /**
             *  Si le repo n'a plus aucun snapshot actif alors on le retire des groupes
             */
            if (empty($activeSnapshots)) {
                $this->model->removeFromGroup($id);
            }
        }
    }

    /**
     *  Modification de l'état de reconstruction des métadonnées du snapshot
     */
    public function snapSetReconstruct(string $snapId, string $status = null)
    {
        $this->model->snapSetReconstruct($snapId, $status);
    }

    /**
     *  Construit et affiche la liste des repos au format html
     */
    public function printRepoList(array $reposList)
    {
        $this->repoLastName = '';
        $this->repoLastDist = '';
        $this->repoLastSection = '';
        $this->repoLastEnv = '';
        $this->lastSnapId = '';

        foreach ($reposList as $repoArray) {
            echo '<div class="repos-list-group-flex-div">';

            foreach ($repoArray as $repo) {
                $this->repoId = $repo['repoId'];
                $this->snapId = $repo['snapId'];
                $this->name   = $repo['Name'];
                $this->source = $repo['Source'];
                $this->reconstruct = $repo['Reconstruct'];
                $this->status      = $repo['Status'];
                $this->packageType = $repo['Package_type'];
                if ($this->packageType == 'deb') {
                    $this->dist    = $repo['Dist'];
                    $this->section = $repo['Section'];
                }
                if (!empty($repo['envId'])) {
                    $this->envId = $repo['envId'];
                } else {
                    $this->envId = '';
                }
                if (!empty($repo['Env'])) {
                    $this->env = $repo['Env'];
                } else {
                    $this->env = '';
                }
                $this->dateFormatted = DateTime::createFromFormat('Y-m-d', $repo['Date'])->format('d-m-Y');
                $this->time   = $repo['Time'];
                $this->type   = $repo['Type'];
                $this->signed = $repo['Signed'];
                if (!empty($repo['Description'])) {
                    $this->description = $repo['Description'];
                } else {
                    $this->description = '';
                }

                /**
                 *  On appelle la fonction printRepoLine qui va se charger d'afficher la ligne du repo
                 */
                $this->printRepoLine();

                if (!empty($this->name)) {
                    $this->repoLastName = $this->name;
                }
                if (!empty($this->dist)) {
                    $this->repoLastDist = $this->dist;
                }
                if (!empty($this->section)) {
                    $this->repoLastSection = $this->section;
                }
                if (!empty($this->snapId)) {
                    $this->lastSnapId = $this->snapId;
                }
                if (!empty($this->packageType)) {
                    $this->lastPackageType = $this->packageType;
                }
            }
            echo '</div>';
        }
    }

    /**
     *  Construit et affiche la ligne du repo en cours (sous-fonction de printRepoList)
     */
    private function printRepoLine()
    {
        $printRepoName = 'yes';
        $printRepoDist = 'yes';
        $printRepoSection = 'yes';
        $printRepoEnv = 'yes';
        $printEmptyLine = 'no';

        if ($this->packageType == 'rpm') {
            $repoPath = REPOS_DIR . '/' . $this->dateFormatted . '_' . $this->name;
        }
        if ($this->packageType == 'deb') {
            $repoPath = REPOS_DIR . '/' . $this->name . '/' . $this->dist . '/' . $this->dateFormatted . '_' . $this->section;
        }

        /**
         *  Tests qui vont définir si on affiche une nouvelle fois le nom du repo/dist/section
         *  Utile pour ne pas afficher plusieurs fois l'information et alléger l'affichage
         */
        if ($this->repoLastName == $this->name) {
            $printRepoName = 'no';
        }

        if ($this->packageType == "deb") {
            if ($this->name == $this->repoLastName and !empty($this->repoLastDist) and $this->dist == $this->repoLastDist and !empty($this->repoLastSection) and $this->section == $this->repoLastSection) {
                $printRepoDist = 'no';
                $printRepoSection = 'no';
            }
            if ($this->name == $this->repoLastName and $this->repoLastDist != $this->dist) {
                $printEmptyLine = 'yes';
            }
        }

        /**
         *  Si le type de paquet n'est pas le même que précédemment alors il faut afficher le nom du repo
         */
        if ($this->lastPackageType != $this->packageType) {
            $printRepoName = 'yes';
            $printRepoDist = 'yes';
            $printRepoSection = 'yes';
        }

        if ($printEmptyLine == 'yes') {
            echo '<div class="item-empty-line"></div>';
        }

        /**
         *  Nom du repo
         */
        echo '<div class="item-repo">';
        if ($printRepoName == "yes") {
            echo $this->name . '<span class="item-pkgtype lowopacity" title="Repo de paquets ' . $this->packageType . '"><img src="resources/icons/products/package.png" class="icon-small" /> ' . $this->packageType . '</span>';
        }
        echo '</div>';

        /**
         *  Nom de la distribution et de la section (Debian)
         */
        if ($this->packageType == "deb") {
            if ($printRepoDist == 'yes' or $printRepoSection == 'yes') {
                echo '<div class="item-dist-section">';
                    echo '<div class="item-dist-section-sub">';
                if ($printRepoDist == 'yes') {
                    echo '<span class="item-dist">' . $this->dist . '</span>';
                }
                if ($printRepoSection == 'yes') {
                    echo '<span class="item-section">❯ ' . $this->section . '</span>';
                }
                    echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="item-dist-section"></div>';
            }
        } else {
            echo '<div></div>';
        } ?>

        <?php
        /**
         *  Les checkbox sont affichées uniquement pour les utilisateurs administrateurs
         */
        if (\Controllers\Common::isadmin()) { ?>
            <div class="item-checkbox">
                <?php
                /**
                 *  On affiche la checkbox que lorsque le snapshot est différent du précédent et qu'il n'y a pas d'opération en cours sur le snapshot
                 */
                if ($this->snapId != $this->lastSnapId) :
                    if ($this->snapOpIsRunning($this->snapId) === true) : ?>
                        <img src="resources/images/loading.gif" class="icon" title="Une opération est en cours sur ce snapshot de repo." />
                    <?php else : ?>
                        <input type="checkbox" class="icon-verylowopacity" name="checkbox-repo[]" repo-id="<?= $this->repoId ?>" snap-id="<?= $this->snapId ?>" <?php echo !empty($this->envId) ? 'env-id="' . $this->envId . '"' : ''; ?> repo-type="<?= $this->type ?>">
                    <?php endif ?>
                <?php endif ?>
            </div>   
            <?php
        } else {
            echo '<div class="item-checkbox"></div>';
        }

        /**
         *  Affichage de la taille
         */
        if (PRINT_REPO_SIZE == "yes") {
            if ($this->packageType == "rpm") {
                $repoSize = exec("du -hs " . REPOS_DIR . "/{$this->dateFormatted}_{$this->name} | awk '{print $1}'");
            }
            if ($this->packageType == "deb") {
                $repoSize = exec("du -hs " . REPOS_DIR . "/{$this->name}/{$this->dist}/{$this->dateFormatted}_{$this->section} | awk '{print $1}'");
            }
        }

        /**
         *  Affichage de la date
         */
        echo '<div class="item-snapshot">';
        if ($this->snapId != $this->lastSnapId) {
            echo '<div class="item-date" title="' . $this->dateFormatted . ' ' . $this->time . '">';
                echo '<span>' . $this->dateFormatted . '</span>';
            echo '</div>';

            echo '<div class="item-info">';
            if (PRINT_REPO_SIZE == "yes") {
                echo '<span class="lowopacity">' . $repoSize . '</span>';
            }

            /**
             *  Affichage de l'icone du type de repo (miroir ou local)
             */
            if (PRINT_REPO_TYPE == 'yes') {
                if ($this->type == "mirror") {
                    echo "<img class=\"icon lowopacity\" src=\"resources/icons/world.png\" title=\"Type : miroir (source : $this->source)\" />";
                } elseif ($this->type == "local") {
                    echo '<img class="icon lowopacity" src="resources/icons/pin.png" title="Type : local" />';
                } else {
                    echo '<img class="icon lowopacity" src="resources/icons/unknow.png" title="Type : inconnu" />';
                }
            }
            /**
             *  Affichage de l'icone de signature GPG du repo
             */
            if (PRINT_REPO_SIGNATURE == 'yes') {
                if ($this->signed == "yes") {
                    echo '<img class="icon lowopacity" src="resources/icons/key.png" title="Repo signé avec GPG" />';
                } elseif ($this->signed == "no") {
                    echo '<img class="icon lowopacity" src="resources/icons/key2.png" title="Repo non-signé avec GPG" />';
                } else {
                    echo '<img class="icon lowopacity" src="resources/icons/unknow.png" title="Signature GPG : inconnue" />';
                }
            }
            /**
             *  Affichage de l'icone "explorer"
             */
            if ($this->packageType == "rpm") {
                echo "<a href=\"explore.php?id={$this->snapId}\"><img class=\"icon lowopacity\" src=\"resources/icons/search.png\" title=\"Explorer le repo $this->name ($this->dateFormatted)\" /></a>";
            }
            if ($this->packageType == "deb") {
                echo "<a href=\"explore.php?id={$this->snapId}\"><img class=\"icon lowopacity\" src=\"resources/icons/search.png\" title=\"Explorer la section {$this->section} ($this->dateFormatted)\" /></a>";
            }
            if (!empty($this->reconstruct)) {
                if ($this->reconstruct == 'needed') {
                    echo '<img class="icon" src="resources/icons/warning.png" title="Le repo contient des paquets qui n\'ont pas été intégré. Vous devez reconstruire le repo pour les intégrer." />';
                }
                if ($this->reconstruct == 'failed') {
                    echo '<img class="icon" src="resources/icons/redcircle.png" title="La construction des métadonnées du repo a échouée." />';
                }
            }
                echo '</div>';
        }
        echo '</div>';

        /**
         *  Affichage d'une flèche uniquement si un environnement pointe vers le snapshot
         */
        if ($this->snapId == $this->lastSnapId) {
            echo '<div class="item-arrow-up">';
        } else {
            echo '<div class="item-arrow">';
        }
        if (!empty($this->env)) {
            echo '<span></span>';
        }
        echo '</div>';

        /**
         *  Affichage de l'environnement pointant vers le snapshot si il y en a un
         */
        echo '<div class="item-env">';
        if (!empty($this->env)) {
            echo \Controllers\Common::envtag($this->env, 'fit');
        }
        echo '</div>';

        echo '<div class="item-env-info">';
        if (!empty($this->env)) {
            /**
             *  Affichage de l'icone "terminal" pour afficher la conf repo à mettre en place sur les serveurs
             */
            if ($this->packageType == "rpm") {
                echo '<img class="client-configuration-btn icon-lowopacity" package-type="rpm" repo="' . $this->name . '" env="' . $this->env . '" repo_dir_url="' . WWW_REPOS_DIR_URL . '" repo_conf_files_prefix="' . REPO_CONF_FILES_PREFIX . '" www_hostname="' . WWW_HOSTNAME . '" src="resources/icons/code.png" title="Afficher la configuration client" />';
            }
            if ($this->packageType == "deb") {
                echo '<img class="client-configuration-btn icon-lowopacity" package-type="deb" repo="' . $this->name . '" dist="' . $this->dist . '" section="' . $this->section . '" env="' . $this->env . '" repo_dir_url="' . WWW_REPOS_DIR_URL . '" repo_conf_files_prefix="' . REPO_CONF_FILES_PREFIX . '" www_hostname="' . WWW_HOSTNAME . '" src="resources/icons/code.png" title="Afficher la configuration client" />';
            }

            /**
             *  Affichage de l'icone "statistiques"
             */
            if (STATS_ENABLED == "yes") {
                if ($this->packageType == "rpm") {
                    echo "<a href=\"stats.php?id={$this->envId}\"><img class=\"icon-lowopacity\" src=\"resources/icons/stats.png\" title=\"Voir les stats du repo $this->name ($this->env)\" /></a>";
                }
                if ($this->packageType == "deb") {
                    echo "<a href=\"stats.php?id={$this->envId}\"><img class=\"icon-lowopacity\" src=\"resources/icons/stats.png\" title=\"Voir les stats de la section $this->section ($this->env)\" /></a>";
                }
            }
            /**
             *  Affichage de l'icone "warning" si le répertoire du repo n'existe plus sur le serveur
             */
            if ($this->packageType == "rpm") {
                if (!is_dir(REPOS_DIR . "/{$this->dateFormatted}_{$this->name}")) {
                    echo '<img class="icon" src="resources/icons/warning.png" title="Le répertoire de ce repo semble inexistant sur le serveur" />';
                }
            }
            if ($this->packageType == "deb") {
                if (!is_dir(REPOS_DIR . "/$this->name/$this->dist/{$this->dateFormatted}_{$this->section}")) {
                    echo '<img class="icon" src="resources/icons/warning.png" title="Le répertoire de cette section semble inexistant sur le serveur" />';
                }
            }
        }

            /**
             *  Icone suppression de l'environnement
             */
        if (!empty($this->env) and \Controllers\Common::isadmin()) {
            echo '<img src="resources/icons/bin.png" class="delete-env-btn icon-lowopacity" title="Supprimer l\'environnement ' . $this->env . '" repo-id="' . $this->repoId . '" snap-id="' . $this->snapId . '" env-id="' . $this->envId . '" env-name="' . $this->env . '" />';
        }

        echo '</div>';

        /**
         *  Affichage de la description
         */
        echo '<div class="item-desc">';
        if (!empty($this->env)) {
            echo '<input type="text" class="repoDescriptionInput" env-id="' . $this->envId . '" placeholder="🖉 ajouter une description" value="' . $this->description . '" />';
        }
        echo '</div>';
    }
}
