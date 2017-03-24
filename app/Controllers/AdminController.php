<?php

namespace ResponsiveMenuTest\Controllers;
use ResponsiveMenuTest\View\View;
use ResponsiveMenuTest\Management\OptionManager;
use ResponsiveMenuTest\Validation\Validator;

class AdminController {

    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    public function index() {
        return $this->view->render(
            'admin/main.html',
            [
                'options' => $this->manager->all()
            ]
        );
    }

    public function update($new_options) {
        $validator = new Validator();

        if($validator->validate($new_options)):
            try {
                $this->manager->updateOptions($new_options);
                $alert = ['success' => 'Responsive Menu Options Updated Successfully.'];
            } catch (\Exception $e) {
                $alert = ['danger' => $e->getMessage()];
            }
            $options = $this->manager->all();
        else:
            $options = $new_options;
            $alert = ['danger' => $validator->getErrors()];
        endif;

        return $this->view->render(
            'admin/main.html',
            [
                'options' => $options,
                'alert' => $alert
            ]
        );
    }

    public function reset($default_options) {

        $this->manager->updateOptions($default_options);

        return $this->view->render(
            'admin/main.html',
            [
                'options' => $this->manager->all(),
                'alert' => [
                    'success' => 'Responsive Menu Options Reset Successfully'
                ]
            ]
        );
    }

    public function import($imported_options) {
        if(!empty($imported_options)):
            try {
                $this->manager->updateOptions($imported_options);
                $alert = ['success' => 'Responsive Menu Options Imported Successfully.'];
            } catch (\Exception $e) {
                $alert = ['danger' => $e->getMessage()];
            }
        else:
            $alert['danger'] = 'No import file selected';
        endif;

        return $this->view->render(
            'admin/main.html',
            [
                'options' => $this->manager->all(),
                'alert' => $alert
            ]
        );
    }

    public function export() {
        return json_encode(
            $this->manager->all()
        );
    }

}
