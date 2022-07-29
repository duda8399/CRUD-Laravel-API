<?php


namespace App\Repositories;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class CompaniesRepository
{

    protected $repository;

    public function __construct() {}

    public function getCompanies() {
        return response()->json(Company::all());
    }

    public function register( $data )
    {
        try {
            return DB::transaction( function () use ( $data ) {
                $company = new Company();
                $company->social_name = $data['social_name'];
                $company->cnpj        = $data['cnpj'];
                $company->email       = $data['email'];
                $company->address     = $data['address'];
                $company->save();

                foreach($data['contacts'] as $contact) {
                    $newContact             = new Contact();
                    $newContact->name       = $contact['name'];
                    $newContact->last_name  = $contact['last_name'];
                    $newContact->phone      = $contact['phone'];
                    $newContact->email      = $contact['email'];
                    $newContact->company_id = $company->id;

                    $newContact->save();
                }

                return [
                    'message' => 'Empresa e contato(s) cadastrado(s) com sucesso.',
                    'code' => '201',
                    'type' => 'success'
                ];
            } );
        } catch ( Exception $exception ) {
            return [
                'message' => 'Erro ao cadastrar empresa.',
                'code' => '500',
                'type' => 'error'
            ];
        }
    }

    public function edit( $id ) {
        try {
            $company = Company::with('contacts')->where('id', $id)->first();
            if ( !$company ) {
                return [
                    'message' => 'Empresa não encontrada.',
                    'code'    => '500',
                    'type'    => 'error'
                ];
            }

            return $company;
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
            $company = Company::find($id);
            if ( !$company ) {
                return [
                    'message' => 'Empresa não encontrada.',
                    'code'    => '500',
                    'type'    => 'error'
                ];
            }

            $items = $data['contacts'];

            $company->social_name = $data['social_name'];
            $company->cnpj        = $data['cnpj'];
            $company->email       = $data['email'];
            $company->address     = $data['address'];
            $company->save();

            $contactItems = $company->contacts;
            $contactItems = $contactItems->keyBy('id');

            foreach($items as $item) {
                if($item['id'] == null) {
                    $newContact             = new Contact();
                    $newContact->name       = $item['name'];
                    $newContact->last_name  = $item['last_name'];
                    $newContact->phone      = $item['phone'];
                    $newContact->email      = $item['email'];
                    $newContact->company_id = $company->id;

                    $newContact->save();
                } else {
                    $contact = $contactItems->find($item['id']);

                    if( $contact ) {
                        $contact->name      = $item['name'];
                        $contact->last_name = $item['last_name'];
                        $contact->phone     = $item['phone'];
                        $contact->email     = $item['email'];
                        $contact->save();
                    }
                }
            }

            if(count($data['contactsDelete']) > 0) {
                foreach ($data['contactsDelete'] as $contact_id) {
                    $contactItem = $contactItems->find($contact_id);
                    $contactItem->delete();
                }
            }
            
            return [
                'message' => 'Empresa e contato(s) atualizado(s) com sucesso.',
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
            $company = Company::find($id);
            if ( !$company ) {
                return [
                    'message' => 'Empresa não encontrada.',
                    'code'    => '500',
                    'type'    => 'error'
                ];
            }

            $company->contacts()->delete();
            $company->delete();

            return [
                'message' => 'Empresa e contato(s) excluídos com sucesso.',
                'code' => '200' ,
                'type'    => 'success'
            ];
        } catch ( Exception $exception ) {
            return [
                'message' => 'Erro ao excluir empresa.',
                'code' => '500',
                'type' => 'error'
            ];
        }
    }

}
