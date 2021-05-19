<?php

/* @var $this yii\web\View */
/* @var $result array */


$this->title = 'My Yii Application';
?>
<div class="site-index">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Full_name</th>
                <th>Updated_at</th>
                <th>Html_url</th>
            </tr>
        </thead>
        <tbody class="table-hover">
            <?php foreach ($result as $r): ?>
                <tr>
                    <td><?= $r->id; ?></th>
                    <td><?= $r->full_name; ?></td>
                    <td><?= $r->updated_at; ?></td>
                    <td><?= $r->html_url; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
