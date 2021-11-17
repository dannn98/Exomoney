<?php

namespace App\Service\DataObject;

use App\DataObject\DataObjectAbstract;
use Symfony\Component\HttpFoundation\Request;

interface DataObjectServiceInterface
{
    public function create(Request $request, string $dtoClassName): DataObjectAbstract;
}