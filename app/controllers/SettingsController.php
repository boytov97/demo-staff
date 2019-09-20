<?php

class SettingsController extends \Phalcon\Mvc\Controller
{
    public function createOrUpdateAction()
    {
        if($this->request->isPost()) {

            $saveUpdateMessage = [];
            $settings = $this->request->getPost('settings');

            foreach ($settings as $key => $value) {
                $setting = $this->getModel()->getByKey($key);

                if($setting) {
                    $setting->assign([
                        'key' => $key,
                        'value' => $value
                    ]);

                    if(!$setting->save()) {
                        $saveUpdateMessage['update_error_message'] =  $setting->getMessages();
                    }
                } else {
                    $setting = $this->getModel();

                    $setting->key = $key;
                    $setting->value = $value;

                    if(!$setting->save()) {
                        $saveUpdateMessage['save_error_message'] =  $setting->getMessages();
                    }
                }
            }

            if (count($saveUpdateMessage)) {
                $this->session->set('setting_message', $saveUpdateMessage);
            } else {
                $this->session->set('setting_message', ['success' => 'Successfully saved']);
            }

            return $this->response->redirect('admin');
        }
    }

    protected function getModel()
    {
        return new Settings();
    }
}

