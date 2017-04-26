<?php

namespace ResponsiveMenu\Controllers;
use ResponsiveMenu\View\View;
use ResponsiveMenu\Management\OptionManager;
use ResponsiveMenu\Validation\Validator;
use ResponsiveMenu\Tasks\UpdateOptionsTask;
use ResponsiveMenu\Collections\OptionsCollection;

class AdminController {

    public function __construct(OptionManager $manager, View $view) {
        $this->manager = $manager;
        $this->view = $view;
    }

    public function index($nav_menus, $location_menus) {
        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $this->manager->all(),
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus
            ]
        );
    }

    public function rebuild($nav_menus, $location_menus) {
        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $this->manager->all(),
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus,
                'alert' => ['success' => 'Responsive Menu Database Rebuilt Successfully.']
            ]
        );
    }

    public function update($new_options, $nav_menus, $location_menus) {
        $validator = new Validator();
        $errors = [];
        if($validator->validate($new_options)):
            try {
                $options = $this->manager->updateOptions($new_options);
                $task = new UpdateOptionsTask;
                $task->run($options, $this->view);
                $alert = ['success' => 'Responsive Menu Options Updated Successfully.'];
            } catch (\Exception $e) {
                $alert = ['danger' => $e->getMessage()];
            }
        else:
            $options = new OptionsCollection($new_options);
            $errors = $validator->getErrors();
            $alert = ['danger' => $errors];
        endif;

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $options,
                'alert' => $alert,
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus,
                'errors' => $errors
            ]
        );
    }

    public function reset($default_options, $nav_menus, $location_menus) {
        try {
            $options = $this->manager->updateOptions($default_options);
            $task = new UpdateOptionsTask;
            $task->run($options, $this->view);
            $alert = ['success' => 'Responsive Menu Options Reset Successfully'];
        } catch(\Exception $e) {
            $alert = ['danger' => $e->getMessage()];
        }
        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $options,
                'alert' => $alert,
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus
            ]
        );
    }

    public function import($imported_options, $nav_menus, $location_menus) {
        if(!empty($imported_options)):
            $validator = new Validator();
            $errors = [];
            if($validator->validate($imported_options)):
                try {
                    unset($imported_options['button_click_trigger']);
                    $options = $this->manager->updateOptions($imported_options);
                    $task = new UpdateOptionsTask;
                    $task->run($options, $this->view);
                    $alert = ['success' => 'Responsive Menu Options Imported Successfully.'];
                } catch(\Exception $e) {
                    $options = $this->manager->all();
                    $alert = ['danger' => $e->getMessage()];
                }
            else:
                $options = new OptionsCollection($imported_options);
                $errors = $validator->getErrors();
                $alert = ['danger' => $errors];
            endif;
        else:
            $options = $this->manager->all();
            $alert = ['danger' => 'No import file selected'];
        endif;

        return $this->view->render(
            'admin/main.html.twig',
            [
                'options' => $options,
                'alert' => $alert,
                'nav_menus' => $nav_menus,
                'location_menus' => $location_menus,
                'errors' => $errors
            ]
        );
    }

    public function export() {
        return json_encode(
            $this->manager->all()->toArray()
        );
    }

}
