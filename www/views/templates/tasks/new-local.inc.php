<div id="log-op-title" class="div-generic-blue flex justify-space-between align-item-center">
    <h3>LOCAL <?= strtoupper($this->repo->getPackageType()) ?> REPOSITORY</h3>

    <div class="text-right">
        <p title="Task Id">
            <b>#<?= $this->task->getId() ?></b>
        </p>
        <p title="Task execution date">
            <b><?= DateTime::createFromFormat('Y-m-d', $this->task->getDate())->format('d-m-Y') . ' ' . $this->task->getTime() ?></b>
        </p>
    </div>
</div>

<div class="div-generic-blue">
    <table class="op-table">
        <tr>
            <th>REPOSITORY</th>
            <td><?= $this->repo->getName() ?></td>
        </tr>
        <?php
        if (!empty($this->repo->getDist()) and !empty($this->repo->getSection())) : ?>
            <tr>
                <th>DISTRIBUTION</th>
                <td><?= $this->repo->getDist() ?></td>
            </tr>
            <tr>
                <th>SECTION</th>
                <td><?= $this->repo->getSection() ?></td>
            </tr>
            <?php
        endif;
        if (!empty($this->repo->getDescription())) : ?>
            <tr>
                <th>DESCRIPTION</th>
                <td><?= $this->repo->getDescription() ?></td>
            </tr>
            <?php
        endif;
        if (!empty($this->repo->getGroup())) : ?>
            <tr>
                <th>ADD TO GROUP</th>
                <td><?= $this->repo->getGroup() ?></td>
            </tr>
            <?php
        endif ?>
    </table>
</div>