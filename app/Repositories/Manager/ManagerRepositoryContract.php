<?php
namespace App\Repositories\Manager;
 
interface ManagerRepositoryContract
{
    
    public function find($id);
    
    public function getAllManagers($requestData);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);

}
