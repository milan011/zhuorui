<?php
namespace App\Repositories\InfoDianxin;
 
interface InfoDianxinRepositoryContract
{
    
    public function find($id);
    
    public function getAllGoods($requestData);

    public function create($requestData);

    public function update($id, $requestData);

    public function destroy($id);
}
