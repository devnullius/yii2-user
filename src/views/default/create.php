<?php

use devnullius\helper\widgets\Select2;
use devnullius\user\forms\UserCreateForm;
use devnullius\user\Module;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

assert($this instanceof View);
assert($model instanceof UserCreateForm);

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
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
        <div class="row">
            <div class="col-6">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
                <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput(['maxLength' => true]) ?>
                <?= $form->field($model, 'phone')->textInput(['maxLength' => true]) ?>
                <?= $form->field($model, 'role')->widget(
                    Select2::class,
                    [
                        'data' => $model->rolesList(),
                        //                                    'tags' => ['multiple' => true],
                        'prompt' => Module::t('basic', 'Role ...'),
                    ]
                ) ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">

    </div>
    <!-- /.card-footer-->
</div>
<!-- /.card -->
