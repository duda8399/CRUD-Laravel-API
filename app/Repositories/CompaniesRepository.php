<?php


namespace App\Repositories;

use App\Models\Companies;
use Illuminate\Support\Facades\DB;

class CompaniesRepository
{

    protected $repository;

    public function __construct() {}

    public function getCompanies() {
        return response()->json(Companies::all());
    }

    public function register( $data )
    {
        try {
            return DB::transaction( function () use ( $data ) {
                $product = new Product();
                $product->name = $data['name'];
                $product->description = $data['description'];
                $product->quantity = $data['quantity'];
                $product->save();

                return [
                    'message' => 'Produto cadastrado com sucesso.',
                    'code' => '201',
                    'type' => 'success'
                ];
            } );
        } catch ( Exception $exception ) {
            return [
                'message' => 'Erro ao cadastrar produto.',
                'code' => '500',
                'type' => 'error'
            ];
        }
    }

    public function show($id)
    {
        try {
            $product = Product::find($id);
            if ( !$product ) {
                return [
                    'message' => 'Produto não encontrado.',
                    'code'    => '500',
                    'type'    => 'error'
                ];
            }

            return $product;
        } catch ( Exception $exception ) {
            return [
                'message' => 'Erro ao encontrar produto.',
                'code' => '500',
                'type' => 'error'
            ];
        }
    }

    public function edit( $id ) {
        try {
            $product = Product::find($id);
            if(isset($product)){
                return view('products.edit', compact(['product']));
            }
            return redirect('products.list');
        } catch ( Exception $exception) {
            return [
                'message' => 'Erro ao encontrar produto.',
                'code' => '500',
                'type' => 'error'
            ];
        }
    }

    public function update( $data, $id )
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return [
                    'code'    => '500',
                    'message' => 'Produto não encontrado.',
                    'type'    => 'error',
                ];
            }

            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->quantity = $data['quantity'];
            $product->save();
            
            return [
                'message' => 'Produto atualizado com sucesso.',
                'code'    => '200',
                'type'    => 'success'
            ];
        } catch ( Exception $exception ) {
            return [
                'message' => 'Erro ao atualizar produto.',
                'code' => '500',
                'type' => 'error'
            ];
        }
    }


    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if ( !$product ) {
                return [
                    'message' => 'Produto não encontrado.',
                    'code'    => '500',
                    'type'    => 'error'
                ];
            }
            $product->delete();

            return [
                'message' => 'Produto excluído com sucesso.',
                'code' => '200' ,
                'type'    => 'success'
            ];
        } catch ( Exception $exception ) {
            return [
                'message' => 'Erro ao excluir produto.',
                'code' => '500',
                'type' => 'error'
            ];
        }
    }

}
