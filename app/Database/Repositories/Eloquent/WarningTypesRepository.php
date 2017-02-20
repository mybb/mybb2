<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Repositories\WarningTypesRepositoryInterface;
use MyBB\Core\Database\Models\WarningType;

class WarningTypesRepository implements WarningTypesRepositoryInterface
{
    /**
     * @var WarningType $warning
     */
    protected $warningTypeModel;

    /**
     * WarningsRepository constructor.
     * @param WarningType $warningType
     * @internal param Warning $warning
     */
    public function __construct(WarningType $warningType)
    {
        $this->warningTypeModel = $warningType;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $details = []) : WarningType
    {
        $warningType = $this->warningTypeModel->create($details);
        return $warningType;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->warningTypeModel->all();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id)
    {
        return $this->warningTypeModel->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id)
    {
        return $this->warningTypeModel->newQuery()->getQuery()->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function edit(WarningType $warningType, array $warningTypeDetails = [])
    {
        $warningType->update($warningTypeDetails);
        return $warningType;
    }
}
