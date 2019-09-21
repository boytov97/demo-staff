<?php

class SettingsController extends \Phalcon\Mvc\Controller
{
    public function createOrUpdateAction()
    {
        if($this->request->isPost()) {

            $saveUpdateMessage = null;
            $settings = $this->request->getPost('settings');

            foreach ($settings as $key => $value) {
                $setting = $this->getModel()->getByKey($key);

                if($setting) {
                    $setting->assign([
                        'key' => $key,
                        'value' => $value
                    ]);

                    if(!$setting->save()) {
                        $saveUpdateMessage = $setting->getMessages();
                    }
                } else {
                    $setting = $this->getModel();

                    $setting->key = $key;
                    $setting->value = $value;

                    if(!$setting->save()) {
                        $saveUpdateMessage = $setting->getMessages();
                    }
                }
            }

            if ($saveUpdateMessage) {
                $this->session->set('error_message', $saveUpdateMessage);
            } else {
                $this->session->set('success_message', ['success' => 'Successfully saved']);
            }

            return $this->response->redirect('admin');
        }
    }

    protected function getModel()
    {
        return new Settings();
    }
}

