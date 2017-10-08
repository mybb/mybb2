<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\View\View;
use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\WordFilterRepositoryInterface;
use MyBB\Core\Http\Controllers\Admin\AdminController;

class WordFilterController extends AdminController
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;
    
    /**
     * @var WordFilterRepositoryInterface
     */
    private $wordFilterRepository;
    
    public function __construct(
        Breadcrumbs $breadcrumbs,
        WordFilterRepositoryInterface $wordFilterRepository
    ) {
        $this->breadcrumbs = $breadcrumbs;
        $this->wordFilterRepository = $wordFilterRepository;
    }
    
    public function index() : \Illuminate\View\View
    {
        $this->breadcrumbs->setCurrentRoute('admin.word_filters.index');
        
		return view('admin.users.word_filters', [
            'word_filter_items' => $this->wordFilterRepository->getAll(),
        ])->withActive('word-filters');
    }
    
    // TODO Implement this properly, this is a placeholder
    public function add() : \Illuminate\View\View
    {
        $this->breadcrumbs->setCurrentRoute('admin.word_filters.index');
        
		return view('admin.users.word_filters', [
            'word_filter_items' => $this->wordFilterRepository->getAll(),
        ])->withActive('word-filters');
    }
    
    // TODO Implement this properly, this is a placeholder
    /*public function addSubmit(WordFilterRequest $request) : \Illuminate\View\View
    {
        $this->breadcrumbs->setCurrentRoute('admin.word_filters.index');
        
		return view('admin.users.word_filters', [
            'word_filter_items' => $this->wordFilterRepository->getAll(),
        ])->withActive('word-filters');
    }*/
    
    // TODO Implement this properly, this is a placeholder
    public function edit() : \Illuminate\View\View
    {
        $this->breadcrumbs->setCurrentRoute('admin.word_filters.index');
        
		return view('admin.users.word_filters', [
            'word_filter_items' => $this->wordFilterRepository->getAll(),
        ])->withActive('word-filters');
    }
    
    // TODO Implement this properly, this is a placeholder
    public function delete() : \Illuminate\View\View
    {
        $this->breadcrumbs->setCurrentRoute('admin.word_filters.index');
        
		return view('admin.users.word_filters', [
            'word_filter_items' => $this->wordFilterRepository->getAll(),
        ])->withActive('word-filters');
    }
}
