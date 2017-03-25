<?php

namespace ResponsiveMenuTest\Controllers;
use ResponsiveMenuTest\View\View;
use ResponsiveMenuTest\Management\OptionManager;
use ResponsiveMenuTest\Validation\Validator;
use ResponsiveMenuTest\Tasks\UpdateOptionsTask;


class AdminController {

    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    public function index($nav_menus, $location_menus) {
        return $this->view->render(
            'admin/main.html',
            [
                'options' => $this->manager->all(),
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus
            ]
        );
    }

    public function update($new_options, $nav_menus, $location_menus) {
        $validator = new Validator();

        if($validator->validate($new_options)):
            try {
                $this->manager->updateOptions($new_options);
                $task = new UpdateOptionsTask;
                $task->run($new_options, $this->view);
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
                'alert' => $alert,
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus
            ]
        );
    }

    public function reset($default_options, $nav_menus, $location_menus) {

        try {
            $this->manager->updateOptions($default_options);
            $task = new UpdateOptionsTask;
            $task->run($default_options, $this->view);
            $alert = ['success' => 'Responsive Menu Options Reset Successfully'];
        } catch (\Exception $e) {
            $alert = ['danger' => $e->getMessage()];
        }
        return $this->view->render(
            'admin/main.html',
            [
                'options' => $this->manager->all(),
                'alert' => $alert,
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus
            ]
        );
    }

    public function import($imported_options, $nav_menus, $location_menus) {
        if(!empty($imported_options)):
            try {
                $this->manager->updateOptions($imported_options);
                $task = new UpdateOptionsTask;
                $task->run($imported_options, $this->view);
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
                'alert' => $alert,
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus
            ]
        );
    }

    public function export() {
        return json_encode(
            $this->manager->all()
        );
    }

}
