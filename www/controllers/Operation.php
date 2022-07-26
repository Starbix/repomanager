<?php

namespace Controllers;

use Exception;

class Operation
{
    public $log;     // pour instancier un objet Log
    private $model;
    private $action;
    private $status;
    private $error;
    private $id;
    private $type;
    private $date;
    private $time;
    private $id_plan; // Si une opération est lancée par une planification alors on peut stocker l'ID de cette planification dans cette variable
    private $targetGpgCheck;
    private $targetGpgResign;
    private $timeStart;
    private $timeEnd;
    private $stepName;
    private $stepNumber = 0;
    private $stepTimeStart;

    public function __construct()
    {
        $this->model = new \Models\Operation();
    }

    public function setPlanId(string $id_plan)
    {
        $this->id_plan = $id_plan;
    }

    public function setAction(string $action)
    {
        $this->action = \Controllers\Common::validateData($action);
    }

    public function setType(string $type)
    {
        if ($type !== 'manual' and $type !== 'plan') {
            throw new Exception("Le type d'opération est invalide");
        }

        $this->type = \Controllers\Common::validateData($type);
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function setError(string $error)
    {
        $this->error = $error;
    }

    public function setTargetGpgCheck(string $gpgCheck)
    {
        $this->targetGpgCheck = $gpgCheck;
    }

    public function setTargetGpgResign(string $gpgResign)
    {
        $this->targetGpgResign = $gpgResign;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getTargetGpgCheck()
    {
        return $this->targetGpgCheck;
    }

    public function getTargetGpgResign()
    {
        return $this->targetGpgResign;
    }

    public function getPlanId()
    {
        return $this->id_plan;
    }

    /**
     *  Retourne les opérations exécutées ou en cours d'exécution par une planification à partir de son Id
     */
    public function getOperationsByPlanId(string $planId, string $status)
    {
        return $this->model-> getOperationsByPlanId($planId, $status);
    }

    /**
     *  Lister les opérations en cours d'exécution en fonction du type souhaité (opérations manuelles ou planifiées)
     */
    public function listRunning(string $type = '')
    {
        return $this->model->listRunning($type);
    }

    /**
     *  Lister les opérations terminées (avec ou sans erreurs)
     *  Il est possible de filtrer le type d'opération ('manual' ou 'plan')
     *  Il est possible de filtrer si le type de planification qui a lancé cette opération ('plan' ou 'regular' (planification unique ou planification récurrente))
     */
    public function listDone(string $type = '', string $planType = '')
    {
        return $this->model->listDone($type, $planType);
    }

    /**
     *  Retourne true si une opération est en cours d'exécution
     */
    public function somethingRunning()
    {
        return $this->model->somethingRunning();
    }

    /**
     *  Stoppe l'opération en fonction du PID spécifié
     */
    public function kill(string $pid)
    {
        if (file_exists(PID_DIR . '/' . $pid . '.pid')) {
            /**
             *  Récupération du nom de fichier de log car on va avoir besoin d'indiquer dedans que l'opération a été stoppée
             */
            $logFile = exec("grep '^LOG=' " . PID_DIR . '/' . $pid . ".pid | sed 's/LOG=//g' | sed 's/\"//g'");

            /**
             *  Récupération des subpid car il va falloir les tuer aussi
             */
            $subpids = shell_exec("grep -h '^SUBPID=' " . PID_DIR . '/' . $pid . ".pid | sed 's/SUBPID=//g' | sed 's/\"//g'");

            /**
             *  Kill des subpids si il y en a
             */
            if (!empty($subpids)) {
                $subpids = explode("\n", trim($subpids));

                foreach ($subpids as $subpid) {
                    exec('kill -9 ' . $subpid);
                }
            }

            /**
             *  Suppression du fichier pid principal
             */
            unlink(PID_DIR . '/' . $pid . '.pid');
        }

        /**
         *  Si cette opération a été lancée par une planification, il faudra mettre à jour la planification en BDD
         *  On récupère d'abord l'ID de la planification
         */
        $planId = $this->model->getPlanIdByPid($pid);

        /**
         *  Mise à jour de l'opération en BDD, on la passe en status = stopped
         */
        $this->model->stopRunningOp($pid);

        /**
         *  Mise à jour de la planification en BDD
         */
        if (!empty($planId)) {
            $myplan = new \Controllers\Planification();
            $myplan->stopPlan($planId);
            unset($myplan);
        }

        \Controllers\Common::printAlert("L'opération a été arrêtée", 'success');

        \Controllers\Common::clearCache();
    }

    /**
     *  Vérifie que l'Id spécifié correspond bien à un repo en BDD
     */
    private function checkParamRepoId(string $id)
    {
        if (empty($id)) {
            throw new Exception("L'Id du repo ne peut pas être vide");
        }

        if (!is_numeric($id)) {
            throw new Exception("L'Id du repo doit être un nombre");
        }

        /**
         *  On vérifie que l'ID spécifié existe en BDD
         */
        $myrepo = new \Controllers\Repo();
        if (!$myrepo->existsId($id)) {
            throw new Exception("Le repo spécifié n'existe pas");
        }

        unset($myrepo);
    }

    /**
     *  Vérifie que l'Id spécifié correspond bien à un snapshot en BDD
     */
    private function checkParamSnapId(string $id)
    {
        if (empty($id)) {
            throw new Exception("L'Id du snapshot ne peut pas être vide");
        }

        if (!is_numeric($id)) {
            throw new Exception("L'Id du snapshot doit être un nombre");
        }

        /**
         *  On vérifie que l'ID spécifié existe en BDD
         */
        $myrepo = new Repo();
        if (!$myrepo->existsSnapId($id)) {
            throw new Exception("Le snapshot spécifié n'existe pas");
        }

        unset($myrepo);
    }

    /**
     *  Vérifie que l'Id spécifié correspond bien à un environnement de repo en BDD
     */
    private function checkParamEnvId(string $id)
    {
        if (empty($id)) {
            throw new Exception("L'Id de l'environnement ne peut pas être vide");
        }

        if (!is_numeric($id)) {
            throw new Exception("L'Id de l'environnement doit être un nombre");
        }

        /**
         *  On vérifie que l'ID spécifié existe en BDD
         */
        $myrepo = new Repo();
        if (!$myrepo->existsEnvId($id)) {
            throw new Exception("L'Id d'environnement spécifié n'existe pas");
        }

        unset($myrepo);
    }

    private function checkParamSource(string $source)
    {
        if (empty($source)) {
            throw new Exception("La source ne peut pas être vide");
        }

        if (!\Controllers\Common::isAlphanumDash($source, array('.'))) {
            throw new Exception('La source du repo contient des caractères invalides');
        }
    }

    private function checkParamType(string $type)
    {
        if (empty($type)) {
            throw new Exception("Le type du repo ne peut pas être vide");
        }

        if ($type !== "mirror" and $type !== "local") {
            throw new Exception('Le type du repo est invalide');
        }
    }

    private function checkParamAlias(string $alias)
    {
        if (!empty($alias)) {
            if (!\Controllers\Common::isAlphanum($alias, array('-'))) {
                throw new Exception('Le nom du repo ne peut pas contenir de caractères spéciaux hormis le tiret -');
            }
        }
    }

    /**
     *  Vérifie que le type de paquets spécifié est valide
     */
    private function checkParamPackageType(string $packageType)
    {
        /**
         *  Pour le moment seuls les types de paquets 'rpm' et 'deb' sont supportés
         */
        if ($packageType != 'rpm' and $packageType != 'deb') {
            throw new Exception('Le type de paquets spécifié est invalide');
        }
    }

    private function checkParamName(string $name)
    {
        if (empty($name)) {
            throw new Exception('Le nom du repo ne peut pas être vide');
        }

        if (!\Controllers\Common::isAlphanum($name, array('-'))) {
            throw new Exception('Le nom du repo ne peut pas contenir de caractères spéciaux hormis le tiret -');
        }
    }

    private function checkParamTargetName(string $targetName)
    {
        if (empty($targetName)) {
            throw new Exception('Vous devez spécifier un nouveau nom de repo');
        }
        if (!\Controllers\Common::isAlphanum($targetName, array('-'))) {
            throw new Exception('Le nouveau nom du repo ne peut pas contenir de caractères spéciaux hormis le tiret -');
        }
    }

    private function checkParamDist(string $dist)
    {
        if (empty($dist)) {
            throw new Exception('Le nom de la distribution ne peut pas être vide');
        }

        if (!\Controllers\Common::isAlphanum($dist, array('-', '/'))) {
            throw new Exception('Le nom de la distribution ne peut pas contenir de caractères spéciaux hormis le tiret -');
        }
    }

    private function checkParamSection(string $section)
    {
        if (empty($section)) {
            throw new Exception('Le nom de la section ne peut pas être vide');
        }

        if (!\Controllers\Common::isAlphanum($section, array('-'))) {
            throw new Exception('Le nom de la section ne peut pas contenir de caractères spéciaux hormis le tiret -');
        }
    }

    private function checkParamGpgCheck(string $gpgCheck)
    {
        if ($gpgCheck !== "yes" and $gpgCheck !== "no") {
            throw new Exception('Le paramètre de vérification des signatures GPG est invalide');
        }
    }

    private function checkParamGpgResign(string $gpgResign)
    {
        if ($gpgResign !== "yes" and $gpgResign !== "no") {
            throw new Exception('Le paramètre de signature avec GPG est invalide');
        }
    }

    private function checkParamGroup(string $group)
    {
        if (!empty($group)) {
            if (!\Controllers\Common::isAlphanumDash($group, array('-'))) {
                throw new Exception('Le groupe comporte des caractères invalides');
            }
        }
    }

    private function checkParamDescription(string $description)
    {
        if (!empty($description)) {
            /**
             *  Vérification du contenu de la description
             *  On accepte certains caractères spéciaux
             */
            if (!\Controllers\Common::isAlphanumDash($description, array('.', '(', ')', '@', 'é', 'è', 'à', 'ç', 'ù', 'ê', 'ô', '+', '\'', ' '))) {
                throw new Exception('La description comporte des caractères invalides');
            }
        }
    }

    private function checkParamEnv(string $env)
    {
        if (empty($env)) {
            throw new Exception("Le nom de l'environnement ne peut pas être vide");
        }
        if (!\Controllers\Common::isAlphanum($env, array('-'))) {
            throw new Exception("L'environnement comporte des caractères invalides");
        }
    }

    // PHP 8.0 : multiple parameter types
    //private function checkParamTargetEnv(string|array $targetEnv)
    private function checkParamTargetEnv(string $targetEnv)
    {
        if (empty($targetEnv)) {
            throw new Exception("Le nom de l'environnement cible ne peut pas être vide");
        }
        if (!\Controllers\Common::isAlphanum($targetEnv, array('-'))) {
            throw new Exception("L'environnement comporte des caractères invalides");
        }
    }

    private function checkParamDate(string $date)
    {
        if (empty($date)) {
            throw new Exception("La date ne peut pas être vide");
        }
        if (preg_match('#^(\d\d\d\d)-(\d\d)-(\d\d)$#', $date) == false) {
            throw new Exception("Le format de la date est invalide");
        }
    }

    private function checkParamIncludeArch(array $targetIncludeArch)
    {
        if (empty($targetIncludeArch)) {

        }

        foreach ($targetIncludeArch as $arch) {
            if (!\Controllers\Common::isAlphanumdash($arch)) {
                throw new Exception("L'architecture à inclure comporte des caractères invalides");
            }
        }
    }

    private function checkParamIncludeSource(string $targetIncludeSource)
    {
        if ($targetIncludeSource !== "yes" and $targetIncludeSource !== "no") {
            throw new Exception('Le paramètre d\'inclusion des sources de paquets est invalide');
        }
    }

    private function checkParamIncludeTranslation(array $targetIncludeTranslation)
    {
        foreach ($targetIncludeTranslation as $translation) {
            if (!\Controllers\Common::isAlphanum($translation)) {
                throw new Exception("La traduction à inclure comporte des caractères invalides");
            }
        }
    }

    /**
     *  NOUVELLE OPERATION
     *  Ajout d'une nouvelle entrée en BDD
     */
    public function startOperation(array $variables = [])
    {
        extract($variables);

        $this->date = date("Y-m-d");
        $this->time = date("H:i:s");
        $this->timeStart = microtime(true); // timeStart sera destiné à calculer le temps écoulé pour l'opération.
        $this->status = 'running';
        $this->log = new \Models\Log('repomanager');

        $this->model->add($this->date, $this->time, $this->action, $this->type, $this->log->pid, $this->log->name, $this->status);

        /**
         *  Récupération de l'ID de l'opération précédemment créée en BDD car on en aura besoin pour clore l'opération
         */
        $this->id = $this->model->getLastInsertRowID();

        /**
         *  Si un ID de planification a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_plan)) {
            $this->model->updatePlanId($this->id, $id_plan);
        }

        /**
         *  Si un ID de repo source a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_repo_source)) {
            $this->model->updateIdRepoSource($this->id, $id_repo_source);
        }

        /**
         *  Si un ID de snapshot source a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_snap_source)) {
            $this->model->updateIdSnapSource($this->id, $id_snap_source);
        }

        /**
         *  Si un ID d'environnement source a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_env_source)) {
            $this->model->updateIdEnvSource($this->id, $id_env_source);
        }

        /**
         *  Si un ID de repo cible a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_repo_target)) {
            $this->model->updateIdRepoTarget($this->id, $id_repo_target);
        }

        /**
         *  Si un ID de snapshot cible a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_snap_target)) {
            $this->model->updateIdSnapTarget($this->id, $id_snap_target);
            ;
        }

        /**
         *  Si un ID d'environnement cible a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_env_target)) {
            $this->model->updateIdEnvTarget($this->id, $id_env_target);
        }

        /**
         *  Si un ID de groupe a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($id_group)) {
            $this->model->updateIdGroup($this->id, $id_group);
        }

        /**
         *  Si gpgCheck a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($gpgCheck)) {
            $this->model->updateGpgCheck($this->id, $gpgCheck);
        }

        /**
         *  Si gpgResign a été renseigné en appelant startOperation alors on l'ajoute directement en BDD
         */
        if (!empty($gpgResign)) {
            $this->model->updateGpgResign($this->id, $gpgResign);
        }

        unset($variables);
    }

    /**
     *  Cloture d'une opération
     */
    public function closeOperation()
    {
        /**
         *  Calcul et conversion du temps total
         */
        $this->duration = microtime(true) - $this->timeStart; // $this->duration = nombre de secondes totales pour l'exécution de l'opération

        $this->step('DUREE TOTALE', false);
        $this->log->steplogDuration($this->stepId, \Controllers\Common::convertMicrotime($this->duration));

        /**
         *  Génère un fichier 'completed' dans le répertoire temporaire des étapes de l'opération, ceci afin que logbuilder.php s'arrête
         */
        touch(TEMP_DIR . '/' . $this->log->getPid() . '/completed');

        $this->deletePid();

        $this->model->closeOperation($this->id, $this->status, $this->duration);

        /**
         *  Nettoyage du cache de repos-list
         */
        \Controllers\Common::clearCache();
    }

    /**
     *  Création d'un nouvelle étape dans l'opération et donc un nouveau fichier de log pour cette étape
     */
    public function step(string $name = null, bool $printLoading = true)
    {
        /**
         *  Incrémentation du numéro d'étape
         */
        $this->stepNumber++;

        /**
         *  Initialisation de l'heure de démarrage de cette étape
         */
        $this->stepTimeStart = microtime(true);

        /**
         *  Création d'un fichier de log pour cette étape
         */
        $this->log->steplog($this->stepNumber);

        if (!empty($name)) {
            $this->stepName = $name;
            $this->stepId = \Controllers\Common::randomString(24);

            /**
             *  Initialisation du fichier de configuration
             */
            $this->log->steplogInitialize($this->stepId);

            /**
             *  Affichage du titre de l'étape
             */
            $this->log->steplogName($this->stepName);

            /**
             *  Affichage d'une icone de chargement
             */
            if ($printLoading === true) {
                $this->log->steplogLoading($this->stepId);
            }
        }
    }

    /**
     *  Ecrire les données capturées dans le fichier de log de l'étape en cours
     *  Ou spécifier un message à écrire dans le fichier de log de l'étape en cours
     */
    public function stepWriteToLog(string $message = null)
    {
        $this->log->steplogWrite($message);
    }

    /**
     *  Affichage d'un message de succès pour l'étape en cours
     */
    public function stepOK(string $message = null)
    {
        $this->log->steplogOK($this->stepId, \Controllers\Common::convertMicrotime(microtime(true) - $this->stepTimeStart), $message);
    }

    /**
     *  Affichage d'un message d'erreur pour l'étape en cours
     */
    public function stepError(string $error)
    {
        $this->log->steplogError($this->stepId, \Controllers\Common::convertMicrotime(microtime(true) - $this->stepTimeStart), $error);
    }

    /**
     *  Affichage d'une icône de warning pour l'étape en cours
     */
    public function stepWarning()
    {
        $this->log->steplogWarning();
    }

    private function getStepDuration()
    {
        return \Controllers\Common::convertMicrotime(microtime(true) - $this->stepTimeStart);
    }

    private function deletePid()
    {
        /**
         *  Suppression du fichier PID
         */
        if (file_exists(PID_DIR . '/' . $this->log->getPid() . '.pid')) {
            unlink(PID_DIR . '/' . $this->log->getPid() . '.pid');
        }
    }

    /**
     *  Retourne le nom du repo ou du groupe en cours de traitement
     */
    public function printRepoOrGroup(string $id)
    {
        /**
         *  Récupération de toutes les informations concernant l'opération en base de données
         */
        $opInfo = $this->model->getAll($id);

        $myrepo = new \Controllers\Repo();
        $mygroup = new \Controllers\Group('repo');

        if (!empty($opInfo['Id_group'])) {
            $group = $mygroup->getNameById($opInfo['Id_group']);
        }

        if (!empty($opInfo['Id_repo_source'])) {
            if (is_numeric($opInfo['Id_repo_source'])) {
                $myrepo->getAllById($opInfo['Id_repo_source']);
                $repoName = $myrepo->getName();
                $repoDist = $myrepo->getDist();
                $repoSection = $myrepo->getSection();
            } else {
                $repo = explode('|', $opInfo['Id_repo_source']);
                $repoName = $repo[0];
                if (!empty($repo[1]) and !empty($repo[2])) {
                    $repoDist = $repo[1];
                    $repoSection = $repo[2];
                }
            }
        } else if (!empty($opInfo['Id_snap_source'])) {
            if (is_numeric($opInfo['Id_snap_source'])) {
                $myrepo->getAllById('', $opInfo['Id_snap_source']);
                $repoName = $myrepo->getName();
                $repoDist = $myrepo->getDist();
                $repoSection = $myrepo->getSection();
            }
        } else if (!empty($opInfo['Id_repo_target'])) {
            if (is_numeric($opInfo['Id_repo_target'])) {
                $myrepo->getAllById($opInfo['Id_repo_target']);
                $repoName = $myrepo->getName();
                $repoDist = $myrepo->getDist();
                $repoSection = $myrepo->getSection();
            } else {
                $repo = explode('|', $opInfo['Id_repo_target']);
                $repoName = $repo[0];
                if (!empty($repo[1]) and !empty($repo[2])) {
                    $repoDist = $repo[1];
                    $repoSection = $repo[2];
                }
            }
        } else if (!empty($opInfo['Id_snap_target'])) {
            if (is_numeric($opInfo['Id_snap_target'])) {
                $myrepo->getAllById('', $opInfo['Id_snap_target']);
                $repoName = $myrepo->getName();
                $repoDist = $myrepo->getDist();
                $repoSection = $myrepo->getSection();
            }
        }
        if (!empty($opInfo['Id_env_target'])) {
            $repoEnv = $opInfo['Id_env_target'];
        }

        unset($mygroup, $myrepo);

        /**
         *  Affichage du groupe ou du repo concerné par l'opération
         */
        if (!empty($group)) {
            echo '<span class="label-white">Groupe ' . $group . '</span>';
        }

        if (!empty($repoDist) and !empty($repoSection)) {
            echo '<span class="label-white">' . $repoName . ' ❯ ' . $repoDist . ' ❯ ' . $repoSection . '</span>';
        }

        if (!empty($repoName) and empty($repoDist) and empty($repoSection)) {
            echo '<span class="label-white">' . $repoName . '</span>';
        }

        if (!empty($repoEnv)) {
            echo ' ' . \Controllers\Common::envtag($repoEnv);
        }

        return;
    }

    /**
     *  Affiche l'état d'une opération (run.php)
     */
    public function printOperation(string $id, bool $startedByPlan = false)
    {
        /**
         *  Récupération de toutes les informations concernant l'opération en base de données
         */
        $opInfo = $this->model->getAll($id);

        $action = $opInfo['Action'];
        $date = $opInfo['Date'];
        $time = $opInfo['Time'];
        $status = $opInfo['Status'];
        $logfile = $opInfo['Logfile'];

        /**
         *  Défini la position et la couleur du bandeau selon si l'opération a été intiiée par une planification ou non
         */
        if ($startedByPlan === true) {
            $containerClass = 'op-header-container';
            $subContainerClass = 'header-light-blue';
        } else {
            $containerClass = 'header-container';
            $subContainerClass = 'header-blue';
        }
        ?>

        <div class="<?=$containerClass?>">
            <div class="<?=$subContainerClass?>">
                <table>
                    <tr>
                        <td class="td-fit">
                            <?php
                            if ($action == "new") {
                                echo '<img class="icon" src="resources/icons/plus.png" title="Nouveau" />';
                            }
                            if ($action == "update") {
                                echo '<img class="icon" src="resources/icons/update.png" title="Mise à jour" />';
                            }
                            if ($action == "reconstruct") {
                                echo '<img class="icon" src="resources/icons/update.png" title="Reconstruction des métadonnées" />';
                            }
                            if ($action == "env" or strpos(htmlspecialchars_decode($action), '->') !== false) {
                                echo '<img class="icon" src="resources/icons/link.png" title="Nouvel environnement" />';
                            }
                            if ($action == "duplicate") {
                                echo '<img class="icon" src="resources/icons/duplicate.png" title="Duplication" />';
                            }
                            if ($action == "delete" or $action == "removeEnv") {
                                echo '<img class="icon" src="resources/icons/bin.png" title="Suppression" />';
                            } ?>
                        </td>
                        <td class="td-small">
                            <a href="run.php?logfile=<?=$logfile?>">Le <b><?=$date?></b> à <b><?=$time?></b></a>
                        </td>

                        <td>
                            <?php
                                $this->printRepoOrGroup($id);
                            ?>
                        </td>

                        <td class="td-fit">
                            <?php
                            /**
                             *  Affichage de l'icone en cours ou terminée ou en erreur
                             */
                            if ($status == "running") {
                                echo 'en cours <img src="resources/images/loading.gif" class="icon" title="en cours d\'exécution" />';
                            }
                            if ($status == "done") {
                                echo '<img class="icon-small" src="resources/icons/greencircle.png" title="Opération terminée" />';
                            }
                            if ($status == "error") {
                                echo '<img class="icon-small" src="resources/icons/redcircle.png" title="Opération en erreur" />';
                            }
                            if ($status == "stopped") {
                                echo '<img class="icon-small" src="resources/icons/redcircle.png" title="Opération stoppée par l\'utilisateur" />';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }

    /**
     *  Renvoyer le formulaire d'opération à l'utilisateur en fonction de sa sélection
     */
    public function getForm(string $action, array $repos_array)
    {
        $action = \Controllers\Common::validateData($action);

        if ($action == 'update') {
            $title = '<h3>UPDATE</h3>';
        }
        if ($action == 'env') {
            $title = '<h3>NEW ENVIRONMENT</h3>';
        }
        if ($action == 'duplicate') {
            $title = '<h3>DUPLICATE</h3>';
        }
        if ($action == 'delete') {
            $title = '<h3>DELETE</h3>';
        }
        if ($action == 'reconstruct') {
            $title = '<h3>REBUILD REPO</h3>';
        }

        $content = $title . '<form class="operation-form-container" autocomplete="off">';

        foreach ($repos_array as $repo) {
            $repoId = \Controllers\Common::validateData($repo['repoId']);
            $snapId = \Controllers\Common::validateData($repo['snapId']);

            /**
             *  Lorsque qu'aucun environnement ne pointe vers le snapshot (snapId), il n'y a aucun envId transmis.
             *  On set envId = null dans ce cas là
             */
            if (empty($repo['envId'])) {
                $envId = null;
            } else {
                $envId = \Controllers\Common::validateData($repo['envId']);
            }

            /**
             *  Vérification de l'id spécifié
             */
            if (!is_numeric($repoId)) {
                throw new Exception("L'Id du repo est invalide");
            }
            if (!is_numeric($snapId)) {
                throw new Exception("L'Id du snapshot est invalide");
            }
            if (!empty($envId)) {
                if (!is_numeric($envId)) {
                    throw new Exception("L'Id de l'environnement est invalide");
                }
            }

            $myrepo = new \Controllers\Repo();
            $myrepo->setRepoId($repoId);
            $myrepo->setSnapId($snapId);
            if (!empty($envId)) {
                $myrepo->setEnvId($envId);
            }

            /**
             *  On vérifie que les Id spécifiés existent en base de données
             */
            if (!$myrepo->model->existsId($repoId)) {
                throw new Exception("L'Id de repo spécifié n'existe pas");
            }
            if (!$myrepo->model->existsSnapId($snapId)) {
                throw new Exception("L'Id de snapshot spécifié n'existe pas");
            }
            // if (!empty($envId)) {
            //     if (!$myrepo->model->existsEnvId($envId)) {
            //         throw new Exception("L'Id d'environnement spécifié n'existe pas");
            //     }
            // }

            /**
             *  On récupère toutes les données du repo à partir des Id transmis
             */
            if (!empty($envId)) {
                $myrepo->getAllById($repoId, $snapId, $envId);
            } else {
                $myrepo->getAllById($repoId, $snapId);
            }

            /**
             *  Récupération du type ede paquets du repo
             */
            $packageType = $myrepo->getPackageType();

            /**
             *  Construction du formulaire à partir d'un template
             */
            ob_start();

            //echo '<div class="operation-form" repo-id="' . $repoId . '" snap-id="' . $snapId . '" env-id="' . $envId . '" action="' . $action . '">';
            echo '<div class="operation-form" snap-id="' . $snapId . '" env-id="' . $envId . '" action="' . $action . '">';
            echo '<table>';

            /**
             *  Si l'action est 'update'
             */
            if ($action == 'update') {
                include(ROOT . '/templates/forms/op-form-update.inc.php');
            }
            /**
             *  Si l'action est duplicate
             */
            if ($action == 'duplicate') {
                include(ROOT . '/templates/forms/op-form-duplicate.inc.php');
            }
            /**
             *  Si l'action est 'env'
             */
            if ($action == 'env') {
                include(ROOT . '/templates/forms/op-form-env.inc.php');
            }
            /**
             *  Si l'action est 'delete'
             */
            if ($action == 'delete') {
                include(ROOT . '/templates/forms/op-form-delete.inc.php');
            }
            /**
             *  Si l'action est 'reconstruct'
             */
            if ($action == 'reconstruct') {
                include(ROOT . '/templates/forms/op-form-reconstruct.inc.php');
            }
            echo '</table>';
            echo '</div>';

            $content .= ob_get_clean();
        }

        $content .= '<br><button class="btn-large-red">Confirmer et exécuter<img src="resources/icons/rocket.png" class="icon" /></button></form>';

        return $content;
    }

    /**
     *  Valider un formulaire d'opération complété par l'utilisateur
     */
    public function validateForm(array $operations_params)
    {
        /**
         *  L'array contient tous les paramètres de l'opération sur le(s) repo(s) à vérifier : l'action, l'id du repo et les paramètres nécéssaires que l'utilisateur a complété par le formulaire
         */
        foreach ($operations_params as $operation_params) {
            /**
             *  Récupération de l'action à exécuter sur le repo
             */
            if (empty($operation_params['action'])) {
                throw new Exception("Aucune action n'a été spécifié.");
            }

            $action = \Controllers\Common::validateData($operation_params['action']);

            /**
             *  On vérifie que l'action spécifiée est valide
             */
            if (
                $action !== 'new' and
                $action !== 'update' and
                $action !== 'duplicate' and
                $action !== 'reconstruct' and
                $action !== 'delete' and
                $action !== 'env'
            ) {
                throw new Exception("L'action spécifiée est invalide.");
            }

            /**
             *  Récupération de l'id de repo et de snapshot, sauf quand l'action est 'new'
             */
            if ($action !== 'new') {
                if (empty($operation_params['snapId'])) {
                    throw new Exception("Aucun Id de snapshot n'a été spécifié.");
                }

                $snapId = \Controllers\Common::validateData($operation_params['snapId']);

                /**
                 *  On vérifie la validité des paramètres transmis
                 */
                $this->checkParamSnapId($snapId);
            }

            /**
             *  Lorsque qu'aucun environnement ne pointe vers le snapshot (snapId), il n'y a aucun envId transmis.
             */
            if (!empty($envId)) {
                $envId = \Controllers\Common::validateData($operation_params['envId']);
                $this->checkParamEnvId($envId);
            }

            if ($action == 'new') {
                /**
                 *  On récupère le type de paquet du repo à créer
                 */
                if (empty($operation_params['packageType'])) {
                    throw new Exception("Le type de paquets du repo n'est pas spécifié.");
                } else {
                    $packageType = $operation_params['packageType'];
                    $this->checkParamPackageType($packageType);
                }
            }

            /**
             *  Récupération de toutes les informations du repo et du snapshot à traiter à partir de leur Id, sauf quand l'action est 'new'
             */
            if ($action !== 'new') {
                $myrepo = new \Controllers\Repo();
                $myrepo->setSnapId($snapId);

                if (!empty($envId)) {
                    $myrepo->setEnvId($envId);
                    $myrepo->getAllById('', $snapId, $envId);
                } else {
                    $myrepo->getAllById('', $snapId);
                }

                /**
                 *  Récupération du type de paquet
                 */
                $packageType = $myrepo->getPackageType();
            }

            /**
             *  Si l'action est 'new'
             */
            if ($action == 'new') {
                $myrepo = new \Controllers\Repo();

                $this->checkParamType($operation_params['type']);

                if ($packageType == 'deb') {
                    $this->checkParamDist($operation_params['dist']);
                    $this->checkParamSection($operation_params['section']);
                }
                $this->checkParamDescription($operation_params['targetDescription']);
                if (!empty($operation_params['targetGroup'])) {
                    $this->checkParamGroup($operation_params['targetGroup']);
                }
                /**
                 *  Si le type de repo sélectionné est 'local' alors on vérifie qu'un nom a été fourni (peut rester vide dans le cas d'un miroir)
                 */
                if ($operation_params['type'] == "local") {
                    $targetName = $operation_params['alias'];

                    $this->checkParamName($targetName);
                }
                /**
                 *  Si le type de repo sélectionné est 'mirror' alors on vérifie des paramètres supplémentaires
                 */
                if ($operation_params['type'] == "mirror") {
                    /**
                     *  Si un alias a été donné, on vérifie sa syntaxe
                     */
                    if (!empty($operation_params['alias'])) {
                        $targetName = $operation_params['alias'];
                    } else {
                        $targetName = $operation_params['source'];
                    }
                    $this->checkParamName($targetName);
                    $this->checkParamSource($operation_params['source']);
                    $this->checkParamGpgCheck($operation_params['targetGpgCheck']);
                    $this->checkParamGpgResign($operation_params['targetGpgResign']);

                    if (empty($operation_params['targetIncludeArch'])) {
                        throw new Exception('You must specify architecture.');
                    }
                    if (empty($operation_params['targetIncludeSource'])) {
                        throw new Exception('You must specify if package source should be mirrored or not.');
                    }
                    $this->checkParamIncludeArch($operation_params['targetIncludeArch']);
                    $this->checkParamIncludeSource($operation_params['targetIncludeSource']);

                    if ($packageType == 'deb') {
                        $this->checkParamIncludeTranslation($operation_params['targetIncludeTranslation']);
                    }
                }
                /**
                 *  On vérifie qu'un/une repo/section du même nom n'est pas déjà actif avec des snapshots
                 */
                if ($packageType == 'rpm' and $myrepo->isActive($operation_params['alias']) === true) {
                    throw new Exception('Un repo de même nom existe déjà');
                }
                if ($packageType == 'deb' and $myrepo->isActive($operation_params['alias'], $operation_params['dist'], $operation_params['section']) === true) {
                    throw new Exception('Une section de repo du même nom existe déjà');
                }

                /**
                 *  On vérifie que le repo source existe
                 */
                if ($operation_params['type'] == 'mirror') {
                    /**
                     *  Sur Redhat on vérifie que le nom de la source spécifiée apparait bien dans un des fichiers de repo source
                     */
                    if ($packageType == 'rpm') {
                        $checkifRepoRealnameExist = exec("grep '^\\[" . $operation_params['source'] . "\\]' " . REPOMANAGER_YUM_DIR . "/*.repo");
                        if (empty($checkifRepoRealnameExist)) {
                            throw new Exception("Il n'existe aucun repo source nommé " . $operation_params['source']);
                        }
                    }
                    /**
                     *  Sur Debian on vérifie en base de données que la source spécifiée existe bien
                     */
                    if ($packageType == 'deb') {
                        $mysource = new \Models\Source();
                        if ($mysource->exists($operation_params['source']) === false) {
                            throw new Exception("Il n'existe aucun repo source nommé " . $operation_params['source']);
                        }
                    }
                }

                if ($packageType == 'rpm') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : Nouveau repo <span class="label-white">' . $targetName . '</span> (' . $operation_params['type'] . ')', 'success');
                }
                if ($packageType == 'deb') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : Nouveau repo <span class="label-white">' . $targetName . ' ❯ ' . $operation_params['dist'] . ' ❯ ' . $operation_params['section'] . '</span> (' . $operation_params['type'] . ')', 'success');
                }
            }

            /**
             *  Si l'action est 'update'
             */
            if ($action == 'update') {
                $this->checkParamGpgCheck($operation_params['targetGpgCheck']);
                $this->checkParamGpgResign($operation_params['targetGpgResign']);

                if ($packageType == 'rpm') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : mise à jour du repo <span class="label-white">' . $myrepo->getName() . '</span> (' . $myrepo->getType() . ')', 'success');
                }
                if ($packageType == 'deb') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : mise à jour du repo <span class="label-white">' . $myrepo->getName() . ' ❯ ' . $myrepo->getDist() . ' ❯ ' . $myrepo->getSection() . '</span> (' . $myrepo->getType() . ')', 'success');
                }

                if (empty($operation_params['targetIncludeArch'])) {
                    throw new Exception('You must specify architecture.');
                }
                if (empty($operation_params['targetIncludeSource'])) {
                    throw new Exception('You must specify if package source should be mirrored or not.');
                }
                $this->checkParamIncludeArch($operation_params['targetIncludeArch']);
                $this->checkParamIncludeSource($operation_params['targetIncludeSource']);

                if ($packageType == 'deb') {
                    $this->checkParamIncludeTranslation($operation_params['targetIncludeTranslation']);
                }
            }

            /**
             *  Si l'action est 'duplicate'
             */
            if ($action == 'duplicate') {
                $this->checkParamTargetName($operation_params['targetName']);

                if (!empty($operation_params['targetEnv'])) {
                    $this->checkParamEnv($operation_params['targetEnv']);
                }

                if (!empty($operation_params['targetDescription'])) {
                    $this->checkParamDescription($operation_params['targetDescription']);
                }

                if (!empty($operation_params['targetGroup'])) {
                    $this->checkParamGroup($operation_params['targetGroup']);
                }
                /**
                 *  On vérifie qu'un repo du même nom n'existe pas déjà
                 */
                if ($packageType == 'rpm') {
                    if ($myrepo->isActive($operation_params['targetName']) === true) {
                        throw new Exception('un repo <span class="label-black">' . $operation_params['targetName'] . '</span> existe déjà');
                    }
                }
                if ($packageType == 'deb') {
                    if ($myrepo->isActive($operation_params['targetName'], $myrepo->getDist(), $myrepo->getSection()) === true) {
                        throw new Exception('un repo <span class="label-black">' . $operation_params['targetName'] . ' ❯ ' . $myrepo->getDist() . ' ❯ ' . $myrepo->getSection() . '</span> existe déjà');
                    }
                }

                if ($packageType == 'rpm') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : duplication d\'un repo <span class="label-white">' . $myrepo->getName() . '</span>⟶<span class="label-black">' . $myrepo->getDateFormatted() . '</span> ➡ <span class="label-white">' . $operation_params['targetName'] . '</span>', 'success');
                }
                if ($packageType == 'deb') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : duplication d\'un repo <span class="label-white">' . $myrepo->getName() . ' ❯ ' . $myrepo->getDist() . ' ❯ ' . $myrepo->getSection() . '</span>⟶<span class="label-black">' . $myrepo->getDateFormatted() . '</span> ➡ <span class="label-white">' . $operation_params['targetName'] . ' ❯ ' . $myrepo->getDist() . ' ❯ ' . $myrepo->getSection() . '</span>', 'success');
                }
            }

            /**
             *  Si l'action est 'delete'
             */
            if ($action == 'delete') {
                /**
                 *  On vérifie que le repo mentionné existe
                 */
                if ($myrepo->existsSnapId($snapId) === false) {
                    throw new Exception("Il n'existe aucun Id de snapshot " . $snapId);
                }

                if ($packageType == 'rpm') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : suppression du snapshot de repo <span class="label-white">' . $myrepo->getName() . '</span>⟶<span class="label-black">' . $myrepo->getDateFormatted() . '</span>', 'success');
                }
                if ($packageType == 'deb') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : suppression du snapshot de repo <span class="label-white">' . $myrepo->getName() . ' ❯ ' . $myrepo->getDist() . ' ❯ ' . $myrepo->getSection() . '</span>⟶<span class="label-black">' . $myrepo->getDateFormatted() . '</span>', 'success');
                }
            }

            /**
             *  Si l'action est 'env'
             */
            if ($action == 'env') {
                $this->checkParamEnv($operation_params['targetEnv']);
                $this->checkParamDescription($operation_params['targetDescription']);

                if ($packageType == 'rpm') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : nouvel environnement <span class="label-white">' . $myrepo->getName() . '</span>⟶<span class="label-black">' . $myrepo->getDateFormatted() . '</span>⟵' . \Controllers\Common::envtag($operation_params['targetEnv']), 'success');
                }
                if ($packageType == 'deb') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : nouvel environnement <span class="label-white">' . $myrepo->getName() . ' ❯ ' . $myrepo->getDist() . ' ❯ ' . $myrepo->getSection() . '</span>⟶<span class="label-black">' . $myrepo->getDateFormatted() . '</span>⟵' . \Controllers\Common::envtag($operation_params['targetEnv']), 'success');
                }
            }

            /**
             *  Si l'action est 'reconstruct'
             */
            if ($action == 'reconstruct') {
                $this->checkParamGpgResign($operation_params['targetGpgResign']);

                if ($packageType == 'rpm') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : reconstruction des métadonnées du repo <span class="label-white">' . $myrepo->getName() . '</span>' . \Controllers\Common::envtag($myrepo->getEnv()), 'success');
                }
                if ($packageType == 'deb') {
                    \Models\History::set($_SESSION['username'], 'Lancement d\'une opération : reconstruction des métadonnées du repo <span class="label-white">' . $myrepo->getName() . ' ❯ ' . $myrepo->getDist() . ' ❯ ' . $myrepo->getSection() . '</span>' . \Controllers\Common::envtag($myrepo->getEnv()), 'success');
                }
            }
        }
    }

    /**
     *  Exécution d'une opération dont les paramètres ont été validés par validateForm()
     */
    public function execute(array $operations_params)
    {
        /**
         *  Création d'un Id principal pour identifier l'opération asynchrone
         */
        while (true) {
            $operation_id = \Controllers\Common::generateRandom();

            /**
             *  On crée le fichier JSON et on sort de la boucle si le numéro est disponible
             */
            if (!file_exists(ROOT . '/operations/pool/' . $operation_id . '.json')) {
                touch(ROOT . '/operations/pool/' . $operation_id . '.json');
                break;
            }
        }

        /**
         *  Ajout du contenu de l'array dans un fichier au format JSON
         */
        file_put_contents(ROOT . '/operations/pool/' . $operation_id . '.json', json_encode($operations_params, JSON_PRETTY_PRINT));

        /**
         *  Lancement de execute.php qui va s'occuper de traiter le fichier JSON
         */
        exec("php " . ROOT . "/operations/execute.php --id='$operation_id' >/dev/null 2>/dev/null &");

        return $operation_id;
    }
}
