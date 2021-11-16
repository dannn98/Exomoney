<?php

namespace App\Service\DataObject;

use App\DataObject\DataObjectAbstract;
use Symfony\Component\HttpFoundation\Request;

class DataObjectService
{
    /**
     * Create DTO object form request
     *
     * @param Request $request
     * @param string $dtoClassName
     *
     * @return DataObjectAbstract
     */
    public function create(Request $request, string $dtoClassName): DataObjectAbstract
    {
        if ($request->getContentType() === 'json') {
            return new $dtoClassName(json_decode($request->getContent(), true));
        } else {
            return new $dtoClassName(array_merge(
                $request->request->all(),
                $request->files->all()
            ));
        }
    }
}