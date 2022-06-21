<?php

namespace App\Controllers;

use App\Flash;
use App\Models\Category;
use Core\View;

class Categories extends \Core\Controller
{
    public function showAction()
    {
        $categories = Category::getAll();
        View::renderTemplate('Category/index.html', [
            'categories' => $categories
        ]);
    }

    public function createAction()
    {
        $category = new Category($_POST);
        if ($category->save()) {
            Flash::addMessage('Successfully added new!');
        } else {
            Flash::addMessage('New addition failed!');
        }
        $this->redirect('/categories/show');
    }

    public function editAction()
    {
        $categories = Category::getAll();
        $category_edit = Category::findById($_GET['id']);
        View::renderTemplate('Category/index.html', [
            'categories' => $categories,
            'category_edit' => $category_edit,
            'edit_path' => '/categories/update'
        ]);
    }

    public function updateAction()
    {
        $category = new Category();
        if ($category->update($_POST)) {
            Flash::addMessage('Changes saved');
            $this->redirect('/categories/show');

        } else {
            Flash::addMessage('Changes failed!');
            $this->redirect('/categories/show');
        }
    }

    public function destroyAction()
    {
        $category = new Category();
        if ($category->delete($_GET)) {
            Flash::addMessage('Successfully deleted new!');
        } else {
            Flash::addMessage('Delete failed!');
        }
        $this->redirect('/categories/show');
    }
}