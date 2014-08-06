<?php

namespace Backend\Modules\FormBuilderMailer\Engine;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\FormBuilder\Engine\Model as BackendFormBuilderModel;
use Common\Mailer;
use SpoonDatabase;

/**
 * In this file we store all generic functions that we will be using in the Form Builder Mailer module
 *
 * @author webleads <fork@webleads.nl>
 */
class Model
{
    const MODULE_NAME = 'FormBuilderMailer';

    /**
     * @param $params
     */
    public static function afterFormSubmission($params)
    {
        $formId = isset($params['form_id']) ? $params['form_id'] : null;
        if ($formId) {
            $dataId = isset($params['data_id']) ? $params['data_id'] : null;
            if ($dataId) {
                $visitorId = isset($params['visitorId']) ? $params['visitorId'] : null;
                if ($visitorId) {
                    $postedFields = isset($params['fields']) ? $params['fields'] : null;
                    if ($postedFields) {
                        $form = BackendFormBuilderModel::get($formId);
                        if (!empty($form)) {
                            if (isset($form['method']) && $form['method'] == 'database_email') {
                                foreach ($postedFields as $field) {
                                    $value = isset($field['value']) ? unserialize($field['value']) : null;
                                    if ($value) {
                                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                            self::mailEndUser($value, $postedFields, $form, $dataId);
                                            return;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $email
     * @param $postedFields
     * @param $form
     * @param $dataId
     */
    public static function mailEndUser($email, $postedFields, $form, $dataId)
    {
        $field_info = '';
        foreach ($postedFields as $field) {
            $label = isset($field['label']) ? $field['label'] : '';
            $value = isset($field['value']) ? unserialize($field['value']) : '';
            $field_info .= $label . ': ' . $value . "\n";
        }

        $title = sprintf(BL::getLabel('Subject', self::MODULE_NAME), $form['name']);
        $data = array(
            'title' => $title,
            'fields' => $field_info,
        );

        $translations = array(
            'ReceivedData',
            'Greetings',
        );
        foreach ($translations as $translation) {
            $data[$translation] = BL::getLabel($translation, self::MODULE_NAME);
        }

        /** @var $mailer Mailer */
        $mailer = BackendModel::get('mailer');
        if ($mailer) {
            // @TODO remove this when https://github.com/forkcms/forkcms/issues/716 is fixed.
            define('FRONTEND_LANGUAGE', SITE_DEFAULT_LANGUAGE); // work around

            $result = $mailer->addEmail(
                $title,
                BACKEND_MODULES_PATH . '/'.self::MODULE_NAME.'/Layout/Templates/Mails/Notification.tpl',
                $data,
                $email
            );
        }
        $useLog = BackendModel::getModuleSetting(self::MODULE_NAME, 'log', true);
        if ($useLog) {
            $logger = BackendModel::get('logger');
            if ($logger) {
                $logger->notice(sprintf('Sending email to %s, status %s', $email, ($result ? 'OK' : 'FAILED')), $data);
            }
        }
        $addExtraData = BackendModel::getModuleSetting(self::MODULE_NAME, 'add_data', true);
        $error = BL::getLabel('Error', self::MODULE_NAME);
        $success = BL::getLabel('OK', self::MODULE_NAME);
        if ($addExtraData) {
            $label = BL::getLabel('DataLabel', self::MODULE_NAME);
            $item = array(
                'data_id' => $dataId,
                'label' => $label,
                'value' => serialize($email . ' - ' . ($result ? $success : $error)),
            );
            /** @var $db SpoonDatabase */
            $db = BackendModel::getContainer()->get('database');
            $db->insert('forms_data_fields', $item);
        }
    }
}
