<?php

use devnullius\user\entities\User;
use devnullius\user\helpers\UserHelper;
use devnullius\user\search\UserSearch;
use devnullius\user\widgets\grid\ActionColumn;
use devnullius\user\widgets\grid\RoleColumn;
use kartik\date\DatePicker;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

assert($this instanceof View);
assert($searchModel instanceof UserSearch);
assert($dataProvider instanceof ActiveDataProvider);

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Default box -->
<div class="card card-outline card-success">
    <div class="card-header">
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']), ['create'], ['class' => 'btn btn-success']) ?>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fas fa-times"></i></button>
        </div>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-bordered table-responsive-md table-striped'],
            'footerRowOptions' => ['class' => 'box box-success', 'style' => 'font-weight:bold;'],
            'columns' => [
                'id',
                [
                    'attribute' => 'created_at',
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_from',
                        'attribute2' => 'date_to',
                        'type' => DatePicker::TYPE_RANGE,
                        'separator' => false,
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                    ]),
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'username',
                    'value' => function (User $model) {
                        return Html::a(Html::encode($model->username), ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                'email:email',
                [
                    'attribute' => 'role',
                    'class' => RoleColumn::class,
                    'filter' => $searchModel->rolesList(),
                ],
                [
                    'attribute' => 'status',
                    'filter' => UserHelper::statusList(),
                    'value' => function (User $model) {
                        return UserHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view} {update} {delete} ',
                ],
            ],
        ]); ?>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">

    </div>
    <!-- /.card-footer-->
</div>
<!-- /.card -->
