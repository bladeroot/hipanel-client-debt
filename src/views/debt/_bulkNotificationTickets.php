<?php
/**
 * Client module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */
use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'create-payment-ticket',
    'affectedObjects' => Yii::t('hipanel:client', 'Affected clients'),
    'formatterField' => 'client',
    'hiddenInputs' => ['id', 'client'],
    'submitButton' => Yii::t('hipanel', 'Send ticket notification'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
    'dropDownInputs' => ['template_id' => $templates],
]);
