<?php


namespace Josebasmtz\MvcSrCore\Interfaces;


interface IValidator
{
    /**
     * @param array $data
     * @return IValidator
     */
    public function withData(array $data);

    /**
     * @param bool $single
     * @return \Illuminate\Support\MessageBag|string|null
     */
    public function errors($single = false);

    /**
     * @param string $key
     * @return bool|null
     */
    public function fails(string $key);

    /**
     * @param string $key
     * @return bool|null
     */
    public function success(string $key);
}