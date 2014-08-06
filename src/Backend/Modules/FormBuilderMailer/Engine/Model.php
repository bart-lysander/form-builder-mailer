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
use SpoonDatabase;
use SpoonFilter;

/**
 * In this file we store all generic functions that we will be using in the Form Builder Mailer module
 *
 * @author webleads <fork@webleads.nl>
 */
class Model
{
    const MODULE_NAME = 'form_builder_mailer';

    /**
     * @param $params
     */
    public static function afterFormSubmission($params)
    {
        $form_id = isset($params['form_id']) ? $params['form_id'] : null;
        if ($form_id) {
            $data_id = isset($params['data_id']) ? $params['data_id'] : null;
            if ($data_id) {
                $visitorId = isset($params['visitorId']) ? $params['visitorId'] : null;
                if ($visitorId) {
                    $postedFields = isset($params['fields']) ? $params['fields'] : null;
                    if ($postedFields) {
                        $form = BackendFormBuilderModel::get($form_id);
                        if (!empty($form)) {
                            if (isset($form['method']) && $form['method'] == 'database_email') {
                                foreach ($postedFields as $field) {
                                    $value = isset($field['value']) ? unserialize($field['value']) : null;
                                    if ($value) {
                                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                            self::mailEndUser($value, $postedFields, $form, $data_id);
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
     */
    private static function mailEndUser($email, $postedFields, $form, $data_id)
    {
        $field_info = '';
        foreach ($postedFields as $field) {
            $label = isset($field['label']) ? $field['label'] : '';
            $value = isset($field['value']) ? unserialize($field['value']) : '';
            $field_info .= $label . ': ' . $value . "\n";
        }

        $title = sprintf(BackendLanguage::getLabel('Subject', self::MODULE_NAME), $form['name']);
        $data = array(
            'title' => $title,
            'fields' => $field_info,
        );

        $translations = array(
            'ReceivedData',
            'Greetings',
        );
        foreach ($translations as $translation) {
            $data[$translation] = BackendLanguage::getLabel($translation, self::MODULE_NAME);
        }

        $result = BackendMailer::addEmail(
            $title,
            BACKEND_MODULES_PATH . '/form_builder_mailer/layout/templates/mails/notification.tpl',
            $data,
            $email
        );
        $useLog = BackendModel::getModuleSetting(self::MODULE_NAME, 'log', true);
        if ($useLog) {
            $logger = BackendModel::get('logger');
            if ($logger) {
                $logger->notice(sprintf('Sending email to %s, status %s', $email, ($result ? 'OK' : 'FAILED')), $data);
            }
        }
        $addExtraData = BackendModel::getModuleSetting(self::MODULE_NAME, 'add_data', true);
        $error = BackendLanguage::getLabel('Error', self::MODULE_NAME);
        $success = BackendLanguage::getLabel('OK', self::MODULE_NAME);
        if ($addExtraData) {
            _debug($form);
            $label = BackendLanguage::getLabel('DataLabel', self::MODULE_NAME);
            $item = array(
                'data_id' => $data_id,
                'label' => $label,
                'value' => serialize($email . ' - ' . ($result ? $success : $error)),
            );
            _debug($item);
            $db = BackendModel::getContainer()->get('database');
            $db->insert('forms_data_fields', $item);
        }
    }
}
