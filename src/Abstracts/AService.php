<?php


namespace Josebasmtz\MvcSrCore\Abstracts;


use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Josebasmtz\MvcSrCore\Criteria\SearchCriteria;
use Josebasmtz\MvcSrCore\Exceptions\NotifiableException;
use Josebasmtz\MvcSrCore\Exceptions\ValidatorException;
use Josebasmtz\MvcSrCore\Interfaces\IRepository;
use Josebasmtz\MvcSrCore\Interfaces\IValidator;

abstract class AService
{
    /**
     * @var IRepository
     */
    protected $repository;

    /**
     * @var IValidator
     */
    protected $validator;

    /**
     * @var MessageBag
     */
    protected $errors;

    public function create($data)
    {
        $response = false;
        try {
            if ($this->validator->withData($data)->fails('create'))
            {
                throw new ValidatorException($this->validator->errors(true));
            }
            $response = $this->repository->create($data);
        }
        catch (\Throwable $e)
        {
            $this->treatException($e);
        }
        return $response;
    }

    public function update($key, $data)
    {
        $response = null;
        try {
            $model = $this->repository->find($key);
            if ($model === null)
            {
                throw new NotifiableException(_i("El registro no existe"));
            }
            if ($this->validator->withData($data)->fails('update'))
            {
                throw new ValidatorException($this->validator->errors(true));
            }
            $response = $this->repository->update($key, $data);
        }
        catch (\Throwable $e)
        {
            $this->treatException($e);
        }
        return $response;
    }

    public function delete($key)
    {
        $response = null;
        try {
            $model = $this->repository->find($key);
            if ($model === null)
            {
                throw new NotifiableException(_i("El registro no existe"));
            }
            $response = $this->repository->delete($key);
        }
        catch (\Throwable $e)
        {
            $this->treatException($e);
        }
        return $response;
    }

    public function find($key)
    {
        return $this->repository->find($key);
    }

    protected function treatException(\Throwable $e)
    {
        \Log::error($e);
        if ($e instanceof ValidatorException)
        {
            $this->addError(_i("La información no es válida"));
            return;
        }

        if ($e instanceof NotifiableException)
        {
            $this->addError($e->getMessage());
            return;
        }
    }

    protected function addError(string $message)
    {
        $this->errors()->add(errors_key(), $message);
    }

    /**
     * @param array $terms
     * @param int|string|null $limit
     * @param array|null $paginationAttrs
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|null
     */
    public function search(array $terms, $limit = null, array $paginationAttrs = null)
    {
        $this->repository->pushCriteria(new SearchCriteria($terms));

        return $this->withLimit($limit, $paginationAttrs);
    }

    /**
     * @param int|string|null $limit
     * @param null|array|Collection $paginationAttrs
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|null
     */
    public function withLimit($limit = null, $paginationAttrs = null)
    {
        if ($limit !== null)
        {
            $collectionPagAttrs = collect($paginationAttrs??[]);

            return $this->repository->applyCriteria()->paginate(
                $limit,
                $collectionPagAttrs->get('columns')??['*'],
                $collectionPagAttrs->get('pageName') ?? 'page',
                $collectionPagAttrs->get('page') ?? 1
            );
        }
        return $this->repository->all();
    }

    public function errors()
    {
        if ($this->errors === null)
        {
            $this->errors = new MessageBag();
        }
        return $this->errors;
    }
}