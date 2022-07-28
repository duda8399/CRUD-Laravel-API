<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CompaniesRepository;

class CompaniesController extends Controller
{
    protected $repository;

    public function __construct(CompaniesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->getCompanies();
    }

    public function store( Request $request )
    {
        return $this->repository->register( $request->all() );
    }

    public function show($id)
    {
        return $this->repository->show( $id );
    }

    public function edit($id)
    {
        return $this->repository->edit( $id );
    }

    public function update(Request $request, $id)
    {
        return $this->repository->update( $request->all(), $id );
    }

    public function destroy($id)
    {
        return $this->repository->destroy( $id );
    }
}
