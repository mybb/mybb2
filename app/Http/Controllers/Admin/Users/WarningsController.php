<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin\Users;

use MyBB\Core\Database\Models\WarningType;
use MyBB\Core\Http\Controllers\Admin\AdminController;
use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\WarningTypesRepositoryInterface as WarningTypes;
use MyBB\Core\Http\Requests\Warnings\CreateWarningTypeRequest;
use MyBB\Core\Exceptions\WarningTypeNotFoundException;
use Illuminate\Http\Request;

class WarningsController extends AdminController
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * @var WarningTypes
     */
    protected $warningTypesRepository;

    /**
     * UserController constructor.
     * @param Breadcrumbs $breadcrumbs
     * @param WarningTypes $warningTypes
     */
    public function __construct(
        Breadcrumbs $breadcrumbs,
        WarningTypes $warningTypes
    ){
        $this->breadcrumbs = $breadcrumbs;
        $this->warningTypesRepository = $warningTypes;
    }

    /**
     * @return mixed
     */
    public function warningTypes()
    {
        $warningTypes = $this->warningTypesRepository->all();
        return view('admin.warnings.warning_types.list', compact('warningTypes'))->withActive('warnings');
    }

    /**
     * @return mixed
     */
    public function addWarningType()
    {
        return view('admin.warnings.warning_types.add')->withActive('warnings');
    }

    /**
     * @param CreateWarningTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createWarningType(CreateWarningTypeRequest $request)
    {
        $this->warningTypesRepository->create([
            'reason'              => $request->input('reason'),
            'points'              => $request->input('points'),
            'expiration_type'     => $request->input('type'),
            'expiration_multiple' => $request->input('multiple'),
            'must_acknowledge'    => $request->input('must_acknowledge'),
        ]);

        return redirect()->route('admin.warnings.warning_types')
            ->withSuccess(trans('admin::warnings.warning_type_create_success'));
    }

    /**
     * @param $id WarningType id
     * @return mixed
     */
    public function editWarningType($id)
    {
        $warningType = $this->warningTypesRepository->find($id);
        if (!$warningType) {
            throw new WarningTypeNotFoundException;
        }
        return view('admin.warnings.warning_types.edit', compact('warningType', 'id'))->withActive('warnings');
    }

    /**
     * @param $id
     * @param CreateWarningTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWarningType($id, CreateWarningTypeRequest $request)
    {
        $warningType = $this->warningTypesRepository->find($id);
        if (!$warningType) {
            throw new WarningTypeNotFoundException;
        }

        $this->warningTypesRepository->edit($warningType, [
            'reason'              => $request->input('reason'),
            'points'              => $request->input('points'),
            'expiration_type'     => $request->input('type'),
            'expiration_multiple' => $request->input('multiple'),
            'must_acknowledge'    => $request->input('must_acknowledge'),
        ]);

        return redirect()->route('admin.warnings.warning_types')
            ->withSuccess(trans('admin::warnings.warning_type_edit_success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteWarningType(Request $request)
    {
        $this->warningTypesRepository->delete($request->input('warning_type_id'));

        return redirect()->back()->withSuccess(trans('admin::general.success_deleted'));
    }
}
