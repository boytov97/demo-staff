<?php

class PermissionsController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('admin');
        parent::initialize();
    }

    public function indexAction()
    {
        if ($this->request->isPost()) {

            $profile = Profiles::findFirstById($this->request->getPost('profileId'));

            if ($profile) {
                if ($this->request->hasPost('permissions') && $this->request->hasPost('submit')) {

                    $profile->getPermissions()->delete();

                    foreach ($this->request->getPost('permissions') as $permission) {

                        $parts = explode('.', $permission);

                        $permission = new Permissions();
                        $permission->profilesId = $profile->id;
                        $permission->resource = $parts[0];
                        $permission->action = $parts[1];

                        $permission->save();
                    }

                    $this->flash->success('Permissions was successfully updated!');
                }

                $this->acl->rebuild();

                $this->view->permissions = $this->acl->getPermissions($profile);
            }

            $this->view->profile = $profile;
        }

        $this->view->profiles = Profiles::find();
    }
}

