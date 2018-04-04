<?php

use hipanel\modules\client\grid\ClientGridLegend;
use hipanel\modules\client\grid\ClientGridView;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use hiqdev\assets\flagiconcss\FlagIconCssAsset;
use yii\helpers\Html;

FlagIconCssAsset::register($this);

$this->title = Yii::t('hipanel.debt', 'Debtors');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

    <?= $page->setSearchFormData(compact(['types', 'states', 'uiModel', 'sold_services'])) ?>

    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel:client', 'Create client'), ['@client/create'], ['class' => 'btn btn-sm btn-success']) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('legend') ?>
        <?= GridLegend::widget(['legendItem' => new ClientGridLegend($model)]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'login',
                'name',
                'seller',
                'type',
                'balance',
                'credit',
                'tariff',
                'create_time',
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('support')) : ?>
            <?= $page->renderBulkButton(Yii::t('hipanel:client', 'Payment notification'), 'create-payment-ticket', 'danger')?>

            <?php
            $dropDownItems = [
                [
                    'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel', 'Enable block'),
                    'linkOptions' => ['data-toggle' => 'modal'],
                    'url' => '#bulk-enable-block-modal',
                ],
                [
                    'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel', 'Disable block'),
                    'url' => '#bulk-disable-block-modal',
                    'linkOptions' => ['data-toggle' => 'modal'],
                ],
            ];
            $ajaxModals = [
                [
                    'id' => 'bulk-enable-block-modal',
                    'scenario' => 'bulk-enable-block-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:client', 'Block clients'), ['class' => 'modal-title']),
                    'headerOptions' => ['class' => 'label-warning'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                ],
                [
                    'id' => 'bulk-disable-block-modal',
                    'scenario' => 'bulk-disable-block-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:client', 'Unblock clients'), ['class' => 'modal-title']),
                    'headerOptions' => ['class' => 'label-warning'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                ],
            ];
            if (Yii::$app->user->can('manage')) {
                array_push($dropDownItems, [
                    'label' => '<i class="fa fa-trash"></i> ' . Yii::t('hipanel', 'Delete'),
                    'url' => '#bulk-delete-modal',
                    'linkOptions' => ['data-toggle' => 'modal']
                ]);
                array_push($ajaxModals, [
                    'id' => 'bulk-delete-modal',
                    'scenario' => 'bulk-delete-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel', 'Delete'), ['class' => 'modal-title label-danger']),
                    'headerOptions' => ['class' => 'label-danger'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                ]);
            }
            if (Yii::$app->user->can('manage')) {
                echo $page->renderBulkButton(Yii::t('hipanel', 'Edit'), 'update');
            }
            ?>
            <div class="dropdown" style="display: inline-block">
                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('hipanel', 'Basic actions') ?>
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'pull-right'],
                    'items' => $dropDownItems,
                ]) ?>
                <div class="text-left">
                    <?php foreach ($ajaxModals as $ajaxModal) : ?>
                        <?= AjaxModal::widget($ajaxModal) ?>
                    <?php endforeach ?>
               </div>
            </div>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= ClientGridView::widget([
                'boxed' => false,
                'rowOptions' => function ($model) {
                    return  GridLegend::create(new ClientGridLegend($model))->gridRowOptions();
                },
                'dataProvider' => $dataProvider,
                'filterModel'  => $model,
                'columns' => [
                    'checkbox', 'login_without_note', 'note',
                    'sold_services',
                    'balance',
                    'last_deposit',
                    'debt_depth',
                    'payment_ticket',
                    'lang',
                ],
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
<?php Pjax::end() ?>
